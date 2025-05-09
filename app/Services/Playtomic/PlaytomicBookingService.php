<?php

namespace App\Services\Playtomic;

use App\Mail\PlaytomicBookingConfirmation;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Spatie\Async\Pool;

class PlaytomicBookingService
{
    protected $log = [];
    private $service;
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function processBookingsForUser($bookings): array
    {
        Log::debug("[Start] Booking process for user: {$this->user->email}");
        $this->log[] = "Start booking ".now()->format('Y-m-d H:i:s');
        $this->service = new PlaytomicHttpService($this->user);

        foreach ($bookings as $booking) {
            Log::debug("[Booking Check] {$booking->id} for user {$this->user->email}");

            $day_to_date = $booking->started_at->copy()->subDays((int)$booking->club->days_min_booking);
            if ($day_to_date->isSameDay(Carbon::now(env('APP_DATETIME_ZONE'), 'Europe/Andorra'))) {
                try {
                    $this->handleBooking($booking);
                } catch (\Exception $e) {
                    Log::error("[Exception] Booking ID {$booking->id}: {$e->getMessage()}");
                    $this->log[] = '[Exception] ' . $e->getMessage();
                }
            } else {
                $this->log[] = '[Skipped] Not eligible for booking today.';
            }
            $booking->log = json_encode($this->log);
            $booking->save();
        }

        Log::info('Booking process finished', $this->log);
        return $this->log;
    }

    protected function handleBooking($booking)
    {
        $this->log[] = "[HandleBooking] Start for booking ID {$booking->id}";

        $timetables = Timetable::whereIn('id', explode(",", $booking->timetables))
            ->orderByRaw(DB::raw("FIELD(id, {$booking->timetables})"))
            ->get()
            ->keyBy('id');

        $resources = Resource::whereIn('id', explode(",", $booking->resources))
            ->orderByRaw(DB::raw("FIELD(id, {$booking->resources})"))
            ->get()
            ->keyBy('id');

        $pref = $booking->booking_preference;
        $primaryItems = $pref === 'timetable' ? $timetables : $resources;
        $secondaryItems = $pref === 'timetable' ? $resources : $timetables;

        $combinations = [];
        $index = 0;

        foreach ($primaryItems as $p1) {
            foreach ($secondaryItems as $p2) {
                [$resource, $timetable] = $pref === 'timetable' ? [$p2, $p1] : [$p1, $p2];
                $combinations[] = [
                    'combo_index'   => $index++,
                    'booking_id'    => $booking->id,
                    'user_id'       => $this->user->id,
                    'resource_id'   => $resource->id,
                    'timetable_id'  => $timetable->id,
                ];
            }
        }

        $results = [];
        $pool = Pool::create();

        foreach ($combinations as $combo) {
            $pool->add(function () use ($combo) {
                try {
                    $booking   = Booking::find($combo['booking_id']);
                    $user      = User::find($combo['user_id']);
                    $resource  = Resource::find($combo['resource_id']);
                    $timetable = Timetable::find($combo['timetable_id']);

                    if (!$booking || !$user || !$resource || !$timetable) {
                        return ['combo_index' => $combo['combo_index'], 'prebooking' => ['status' => 'fail', 'message' => 'Invalid combo data']];
                    }

                    $response = $this->service->preBooking($booking, $resource, $timetable);
                    Log::warning('Response prebooking', $response);

                    return [
                        'combo_index' => $combo['combo_index'],
                        'prebooking'  => $response,
                        'resource'    => $resource,
                        'timetable'   => $timetable,
                        'booking'     => $booking,
                        'user'        => $user,
                    ];
                } catch (\Throwable $e) {
                    return ['combo_index' => $combo['combo_index'], 'prebooking' => ['status' => 'fail', 'message' => $e->getMessage()]];
                }
            })->then(function ($result) use (&$results) {
                $results[$result['combo_index']] = $result;
            })->catch(function (\Throwable $e) {
                $this->log[] = 'Async booking error: ' . $e->getMessage();
            });
        }

        $pool->wait();
        ksort($results);

        foreach ($results as $result) {
            if ($result['prebooking']['status'] === 'fail') {
                $this->log[] = 'Prebooking failed: ' . $result['prebooking']['message'];
                continue;
            }

            $booking   = $result['booking'];
            $user      = $result['user'];
            $resource  = $result['resource'];
            $timetable = $result['timetable'];

            $this->log[] = "[MakeBooking] For booking {$booking->id} and timetable {$timetable->id}";
            $response = $this->makeBooking($result['prebooking']);

            if (!isset($response['error'])) {
                try {
                    Mail::to($user->email)->send(new PlaytomicBookingConfirmation($booking, $resource, $timetable, $response));
                    $this->log[] = "Mail sent to {$user->email}";
                } catch (\Exception $e) {
                    $this->log[] = "Mail send error: {$e->getMessage()}";
                }
                $booking->setBooked();
                break;
            } else {
                $this->log[] = 'Booking error: ' . $response['error'];
            }
        }

        $booking->log = json_encode($this->log);
        $booking->save();
    }


    protected function availability(Booking $booking, Resource $resource, Timetable $timetable): array
    {
        try {
            $this->log[] = 'Availibility '.$timetable->name.' '.now()->format('Y-m-d H:i:s');
            $response = $this->service->preBooking($booking, $resource, $timetable);
            if (isset($response['status']) && $response['status'] === 'fail') {
                $this->log[] = 'Prebooking error: ' . $timetable->name . ' ' . $response['message'];
                return ['status' => 'fail', 'message' => 'Prebooking error: ' . $timetable->name . ' ' . $response['message']];
            }
            $this->log[] = 'Prebooking ok: ' . $resource->name . ' at ' . $timetable->name;
            return $response;
        } catch (\Exception $e) {
            $this->log[] = 'Prebooking catch error: ' . $timetable->name . ' ' . $e->getMessage();
            return ['status' => 'fail', 'message' => 'Prebooking catch error: ' . $timetable->name . ' ' . $e->getMessage()];
        }
    }

    protected function makeBooking(array $prebooking): array
    {
        if (!isset($prebooking['payment_intent_id'])) {
            $this->log[] = 'Missing payment_intent_id';
            return ['error' => 'Missing payment_intent_id'];
        }

        try {
            $step1 = $this->service->paymentMethodSelection($prebooking);
            if ($step1['status'] === 'fail') {
                $this->log[] = 'Payment method failed: ' . $step1['message'];
                return ['error' => $step1['message']];
            }

            $step2 = $this->service->confirmation($step1['payment_intent_id']);
            if ($step2['status'] === 'fail') {
                $this->log[] = 'Confirmation failed: ' . $step2['message'];
                return ['error' => $step2['message']];
            }

            $matchId = $step2['cart']['item']['cart_item_data']['match_id'] ?? null;
            if (!$matchId) {
                $this->log[] = 'Missing match_id';
                return ['error' => 'Missing match_id'];
            }

            $step3 = $this->service->confirmationMatch($matchId);
            if (isset($step3['status']) && $step3['status'] === 'fail') {
                $this->log[] = 'Confirmation match failed: ' . $step3['message'];
                return ['error' => $step3['message']];
            }

            $this->log[] = 'Booking confirmed successfully';
            return $step3;

        } catch (\Exception $e) {
            $this->log[] = 'MakeBooking exception: ' . $e->getMessage();
            return ['error' => $e->getMessage()];
        }
    }
}
