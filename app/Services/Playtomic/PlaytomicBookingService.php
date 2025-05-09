<?php

namespace App\Services\Playtomic;

use App\Jobs\LaunchPrebookingJob;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlaytomicBookingService
{
    protected $log = [];
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function processBookingsForUser($bookings): void
    {
        Log::debug("[Start] Booking process for user: {$this->user->email}");
        $this->log[] = "Start booking " . now()->format('Y-m-d H:i:s');

        foreach ($bookings as $booking) {
            $day_to_date = $booking->started_at->copy()->subDays((int) $booking->club->days_min_booking);

            if ($day_to_date->isSameDay(Carbon::now(env('APP_DATETIME_ZONE'), 'Europe/Andorra'))) {
                $this->enqueuePrebookingJobs($booking);
                $this->log[] = "Jobs enqueued for booking {$booking->id}";
            } else {
                $this->log[] = "[Skipped] Booking {$booking->id} not scheduled for today";
            }

            $booking->log = json_encode($this->log);
            $booking->save();
        }

        Log::info('Booking job scheduling finished', $this->log);
    }

    protected function enqueuePrebookingJobs(Booking $booking): void
    {
        $timetables = Timetable::whereIn('id', explode(",", $booking->timetables))
            ->orderByRaw(DB::raw("FIELD(id, {$booking->timetables})"))
            ->get();

        $resources = Resource::whereIn('id', explode(",", $booking->resources))
            ->orderByRaw(DB::raw("FIELD(id, {$booking->resources})"))
            ->get();

        $pref = $booking->booking_preference;
        $primaryItems = $pref === 'timetable' ? $timetables : $resources;
        $secondaryItems = $pref === 'timetable' ? $resources : $timetables;

        foreach ($primaryItems as $p1) {
            foreach ($secondaryItems as $p2) {
                [$resource, $timetable] = $pref === 'timetable' ? [$p2, $p1] : [$p1, $p2];

                LaunchPrebookingJob::dispatch(
                    $this->user->id,
                    $booking->id,
                    $resource->id,
                    $timetable->id
                )->delay($booking->started_at->subMinutes((int) $booking->club->minutes_before_open)->timezone('Europe/Andorra'));
            }
        }
    }
}
