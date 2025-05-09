<?php

namespace App\Jobs;

use App\Mail\PlaytomicBookingConfirmation;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\Timetable;
use App\Models\User;
use App\Services\Playtomic\PlaytomicHttpService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LaunchPrebookingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $userId;
    protected int $bookingId;
    protected int $resourceId;
    protected int $timetableId;

    public function __construct(int $userId, int $bookingId, int $resourceId, int $timetableId)
    {
        $this->userId = $userId;
        $this->bookingId = $bookingId;
        $this->resourceId = $resourceId;
        $this->timetableId = $timetableId;
    }

    public function handle(): void
    {
        $user = User::find($this->userId);
        $booking = Booking::find($this->bookingId);
        $resource = Resource::find($this->resourceId);
        $timetable = Timetable::find($this->timetableId);

        if (!$user || !$booking || !$resource || !$timetable) {
            $this->appendLog($booking ?? null, "[Prebooking] Invalid data - Job skipped");
            return;
        }

        $log = [];
        $service = new PlaytomicHttpService($user);

        $this->appendLog($booking, 'Start prebooking for booking ID ' . $booking->id);

        try {
            $response = $service->preBooking($booking, $resource, $timetable);

            if (isset($response['status']) && $response['status'] === 'fail') {
                $this->appendLog($booking, 'Prebooking failed: ' . $response['message']);
                return;
            }

            $this->appendLog($booking, 'Prebooking OK');
            $bookingResult = $this->makeBooking($response, $timetable, $service, $booking);

            if (!isset($bookingResult['error'])) {
                Mail::to($user->email)->send(new PlaytomicBookingConfirmation($booking, $resource, $timetable, $bookingResult));
                $this->appendLog($booking, 'Email sent');
                $booking->setBooked();
            } else {
                $this->appendLog($booking, 'Booking failed: ' . $bookingResult['error']);
            }
        } catch (\Throwable $e) {
            $this->appendLog($booking, 'Job error: ' . $e->getMessage());
        }
    }

    protected function makeBooking(array $prebooking, Timetable $timetable, PlaytomicHttpService $service, Booking $booking): array
    {
        if (!isset($prebooking['payment_intent_id'])) {
            $this->appendLog($booking, 'Missing payment_intent_id');
            return ['error' => 'Missing payment_intent_id'];
        }

        try {
            $step1 = $service->paymentMethodSelection($prebooking);
            if ($step1['status'] === 'fail') {
                $this->appendLog($booking, 'Payment method failed: ' . $step1['message']);
                return ['error' => $step1['message']];
            }

            $step2 = $service->confirmation($step1['payment_intent_id']);
            if ($step2['status'] === 'fail') {
                $this->appendLog($booking, 'Confirmation failed: ' . $step2['message']);
                return ['error' => $step2['message']];
            }

            $matchId = $step2['cart']['item']['cart_item_data']['match_id'] ?? null;
            if (!$matchId) {
                $this->appendLog($booking, 'Missing match_id');
                return ['error' => 'Missing match_id'];
            }

            $step3 = $service->confirmationMatch($matchId);
            if (isset($step3['status']) && $step3['status'] === 'fail') {
                $this->appendLog($booking, 'Confirmation match failed: ' . $step3['message']);
                return ['error' => $step3['message']];
            }

            $this->appendLog($booking, 'Booking confirmed');
            return $step3;

        } catch (\Throwable $e) {
            $this->appendLog($booking, 'makeBooking exception: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    protected function appendLog(?Booking $booking, string $message): void
    {
        Log::info($message);

        if ($booking) {
            $logArray = json_decode($booking->log ?? '[]', true);
            $logArray[] = now()->format('Y-m-d H:i:s') . ' - ' . $message;
            $booking->log = json_encode($logArray);
            $booking->save();
        }
    }
}
