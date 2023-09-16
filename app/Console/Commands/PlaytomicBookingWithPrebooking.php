<?php

namespace App\Console\Commands;

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

class PlaytomicBookingWithPrebooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'playtomic:booking-with-availability {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Booking with availability';
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
                try {
                    $this->booking($booking);
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                }
            }
            $booking->log = json_encode($this->log);
            $booking->save();
        }
        $this->displayMessage('Booking scheduled finish', 'info', $this->log);
    }

    public function booking($booking){
        $booked = false;
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
                $this->displayMessage('Prebooking availability: ' . $booking->name . ' ' . $resource->name.' '.$booking->started_at->format('d-m-Y') . ' ' . $timetable->name, 'info');
                $prebooking = $this->availability($booking, $resource, $timetable);
                Log::notice('Prebooking availability: '.(!is_array($prebooking) ? $prebooking : ''), is_array($prebooking) ? $prebooking : []);
                if(isset($prebooking['status']) && $prebooking['status'] === 'fail') $this->displayMessage ('No availibility '.$prebooking['message']);
                else {
                    $response = $this->makeBooking($prebooking, $timetable);
                    if (!isset($response['error'])) {
                        Mail::to($this->user)->send(new PlaytomicBookingConfirmation($booking, $resource, $timetable, $response));
                        $this->displayMessage('Mail sent to ' . $this->user->email, 'info');
                        $booked = true;
                        break;
                    } else {
                        Log::error($response['error'], $this->log);
                        $this->displayMessage('Error: ' . $response['error']);

                    }
                }
            }
        }
        $this->displayMessage('Booking end: ' . $booking->name . ' ' . $resource->name.' '.$booking->started_at->format('d-m-Y') . ' ' . $timetable->name, 'info', $this->log);
    }

    public function availability(Booking $booking, Resource $resource, Timetable $timetable)
    {
        if($this->service->login()){
            try{
                $response = $this->service->preBooking($booking, $resource, $timetable);
                if(isset($response['status']) && $response['status'] === 'fail') {
                    $this->displayMessage('Prebooking error: '.$timetable->name.' '.$response['message']);
                    return ['status' => 'fail', 'message' => 'Prebooking error: '.$timetable->name.' '.$response['message']];
                }
                $this->displayMessage('Prebooking ok: '.$resource->name.' at '.$timetable->name, 'info');
                return $response;
            }catch(\Exception $e){
                $this->displayMessage('Prebooking error: '.$timetable->name.' '.$e->getMessage());
                return ['status' => 'fail', 'message' => 'Prebooking error: '.$timetable->name.' '.$e->getMessage()];
            }
        }else return ['status' => 'fail', 'message' => 'No logged'];
    }

    public function makeBooking($prebooking, Timetable $timetable){
        $this->displayMessage('Payment method '.$timetable->name, 'info');
        $prebooking = $this->paymentMethodSelection($prebooking);
        Log::notice('Payment method: '.(!is_array($prebooking) ? $prebooking : ''), is_array($prebooking) ? $prebooking : []);
        if(isset($prebooking['status']) && $prebooking['status'] === 'fail') return ['error' => $prebooking['message']];
        $this->displayMessage('Confirmation '.$timetable->name, 'info');
        $prebooking = $this->confirmation(is_array($prebooking) ? $prebooking : []);
        Log::notice('Confirmation: '.(!is_array($prebooking) ? $prebooking : ''), is_array($prebooking) ? $prebooking : []);
        if(isset($prebooking['status']) && $prebooking['status'] === 'fail') return ['error' => $prebooking['message']];
        $this->displayMessage('Confirmation Match '.$timetable->name, 'info');
        $prebooking = $this->confirmationMatch($prebooking);
        Log::notice('Confirmation match: '.(!is_array($prebooking) ? $prebooking : ''), is_array($prebooking) ? $prebooking : []);
        if(isset($prebooking['status']) && $prebooking['status'] === 'fail') return ['error' => $prebooking['message']];
        $this->displayMessage('Confirmation match '.$timetable->name, 'info');
        return $prebooking;
    }

    public function paymentMethodSelection($prebooking)
    {
        try{
            if(!isset($prebooking["payment_intent_id"])) return ['status' => 'fail', 'message' => 'Payment method error: No payment_intent_id'];
            $response = $this->service->paymentMethodSelection($prebooking["payment_intent_id"]);
            if(isset($response['status']) && $response['status'] === 'fail') {
                $this->displayMessage('Payment method error: '.$response['message']);
                return ['status' => 'fail', 'message' => 'Payment method error: ' . $response['message']];
            }
            $this->line(  'Payment method Ok');
            return $response;
        }catch(\Exception $e){
            $this->displayMessage('Payment method error: '.$e->getMessage());
            return ['status' => 'fail', 'message' => 'Payment method error: '.$e->getMessage()];
        }
    }

    public function confirmation($prebooking)
    {
        try{
            $response = $this->service->confirmation($prebooking['payment_intent_id']);
            if(isset($response['status']) && $response['status'] === 'fail') {
                $this->displayMessage('Confirmation error: '.$response['message']);
                return ['status' => 'fail', 'message' => 'Confirmation error: ' . $response['message']];
            }
            $this->line('Confirmation Ok');
            return $response;
        }catch (\Exception $e) {
            $this->displayMessage('Confirmation error: '.$e->getMessage());
            return ['status' => 'fail', 'message' => 'Confirmation error: '.$e->getMessage()];
        }
    }

    public function confirmationMatch($prebooking)
    {
        try{
            $response = $this->service->confirmationMatch($prebooking['cart']['item']['cart_item_data']['match_id']);
            if(isset($response['status']) && $response['status'] === 'fail') {
                $this->displayMessage('Confirmation match error: '.$response['message']);
                return ['status' => 'fail', 'message' => 'Confirmation match error ' . $response['message']];
            }
            $this->line('Confirmation match Ok');
            return $response;
        }catch (\Exception $e) {
            $this->displayMessage('Confirmation match error: '.$e->getMessage());
            return ['status' => 'fail', 'message' => 'Confirmation match error: '.$e->getMessage()];
        }
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
