<?php

namespace App\Console\Commands;

use App\AsyncJobs\BookingJob;
use App\Mail\PlaytomicBookingConfirmation;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\Timetable;
use App\Models\User;
use App\Services\PlaytomicHttpService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use VXM\Async\AsyncFacade;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;

class PlaytomicBookingBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'playtomic:booking-batch {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Booking on-time with batch';
    private $service;
    private $log;
    private $user;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return void|null
     */
    public function handle()
    {
        $this->user = User::byEmail($this->argument('user'))->first();
        if(!$this->user) return $this->displayMessage('No user found');

        $this->service = new PlaytomicHttpService($this->user);
        $this->displayMessage('Init process', 'info');
        $bookings = Booking::ontime()->orderBy('started_at', 'DESC')->get();
        foreach ($bookings as $booking) {
            $day_to_date = $booking->started_at->subDays((int)$booking->club->days_min_booking);
            if ($day_to_date->startOfDay()->format('d-m-Y') == Carbon::now('Europe/Andorra')->startOfDay()->format('d-m-Y')) {
                $this->booking($booking);
            }
            $booking->log = json_encode($this->log);
            $booking->save();
        }
        $this->displayMessage('Booking scheduled finish', 'info', $this->log);
    }

    public function booking($booking){
        $booked = false;
        $jobs = [];
        $timetables = Timetable::whereIn('id', explode(",", $booking->timetables))->orderByRaw(DB::raw("FIELD(id, ".$booking->timetables.")"))->get();
        $resources = Resource::whereIn('id', explode(",", $booking->resources))->orderByRaw(DB::raw("FIELD(id, ".$booking->resources.")"))->get();
        $booking_preference = ["timetable" => $timetables, "resource" => $resources];

        foreach ($booking_preference[$booking->booking_preference] as $preference){
            if($booked) break;
            foreach ($booking_preference[$booking->booking_preference === 'timetable' ? 'resource' : 'timetable'] as $preference2) {
                if ($booking->booking_preference === 'timetable') {
                    $resource = $preference2;
                    $timetable = $preference;
                } else {
                    $resource = $preference;
                    $timetable = $preference2;
                }
                $this->displayMessage('Booking chained: ' . $booking->name . ' ' . $resource->name.' '.$booking->started_at->format('d-m-Y') . ' ' . $timetable->name, 'info');
                // Adding jobs
                $jobs[] = new BookingJob($booking, $resource, $timetable, $this->user);
            }
        }
        $this->displayMessage('Booking batch process', 'info');
        Bus::batch($jobs)->dispatch()->allowsFailures(); // ->onQueue('sync')
    }

    public function displayMessage($message, $type = 'error', $detail_log = []){
        $this->log[] = $message;
        if($type === 'error') {
            $this->error($message);
            Log::error($message, $detail_log);
        }else {
            $this->line($message);
            Log::info($message, $detail_log);
        }
    }
}
