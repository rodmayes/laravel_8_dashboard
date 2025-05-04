<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class PlaytomicBookingsSetStatusClosed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'playtomic:bookings-status-closed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if are bookings on date';

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Booking status init');
        $bookings = Booking::notClosed()->orderBy('started_at','desc')->get();
        foreach($bookings as $booking){
            $day_to_date = (Carbon::createFromDate($booking->started_at))->subDays((int)$booking->club->days_min_booking);
            if($day_to_date->startOfDay() < Carbon::now('Europe/Andorra')->startOfDay()){
                try{
                    $booking->setStatusTimeOut();
                    $this->line('Status Time out: '.$booking->name.' '.$booking->started_at->format('d-m-Y'));
                }catch(\Exception $e){
                    Log::error($e->getMessage());
                }
            }else{
                try{
                    $booking->setStatusOnTime();
                    $this->line('Status On time: '.$booking->name.' '.$booking->started_at->format('d-m-Y'));
                }catch(\Exception $e){
                    Log::error($e->getMessage());
                }
            }
        }
        $this->info('Booking status finish');

    }
}
