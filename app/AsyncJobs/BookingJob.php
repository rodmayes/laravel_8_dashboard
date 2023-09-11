<?php

namespace App\AsyncJobs;

use App\Http\Controllers\Playtomic\BookingController;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\Timetable;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BookingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    protected $booking;
    protected $resource;
    protected $timetable;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Booking $booking, Resource $resource, Timetable $timetable, $user)
    {
        $this->booking = $booking;
        $this->resource = $resource;
        $this->timetable = $timetable;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->batch()->cancelled()) return;
        $log = (new BookingController())->startBooking($this->booking, $this->resource, $this->timetable, $this->user);
        Log::info('Process '.(!is_array($log) ? $log : ''), is_array($log) ? $log : []);
        if(isset($log['status']) && isset($log['status']['success'])) {
            return $this->batch()->cancel();
        }
    }
}
