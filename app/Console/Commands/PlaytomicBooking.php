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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PlaytomicBooking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'playtomic:booking-on-date {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Booking opens';
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
        if(!$this->user) return $this->error('No user found');

        $this->service = new PlaytomicHttpService($this->user);
        $this->line('Login attempt');
        $this->log[] = 'Login attempt';
        $bookings = Booking::ontime()->orderBy('started_at')->get();
        foreach($bookings as $booking){
            $day_to_date = (Carbon::now('Europe/Andorra'))->addDays((int)$booking->club->days_min_booking);
            if($booking->started_at->format('d-m-Y') === $day_to_date->format('d-m-Y')){
                try{
                    $this->booking($booking);
                }catch(\Exception $e){
                    Log::error($e->getMessage());
                }
            }
        }
        $this->info('Booking scheduled finish');
    }

    public function booking($booking){
        $timetables = [$booking->timetable, Timetable::before($booking->timetable)->first(), Timetable::after($booking->timetable)->first()];
        $resources_ids = explode(",",$booking->resources);
        foreach ($resources_ids as $id)
            $resources[] = Resource::find($id);
        $booking_preference = ["timetable" => $timetables, "resource" => $resources];

        foreach ($booking_preference[$booking->booking_preference] as $preference){
            foreach ($booking_preference[$booking->booking_preference === 'timetable' ? 'resource' : 'timetable'] as $preference2) {
                if ($booking->booking_preference === 'timetable') {
                    $resource = $preference2;
                    $timetable = $preference;
                } else {
                    $resource = $preference;
                    $timetable = $preference2;
                }
                $this->log[] = 'Booking start: ' . $booking->name . ' ' . $resource->name.' '.$booking->started_at->format('d-m-Y') . ' ' . $timetable->name;
                $this->line('Booking start : ' . $booking->name . ' ' . $resource->name.' '.$booking->started_at->format('d-m-Y') . ' ' . $timetable->name);
                $response = $this->makeBooking($booking, $resource, $timetable);
                $this->log[] = 'Booking end: ' . $booking->name . ' ' . $resource->name.' '.$booking->started_at->format('d-m-Y') . ' ' . $timetable->name . ' Do it!';
                if (!isset($response['error'])) {
                    Mail::to($this->user)->send(new PlaytomicBookingConfirmation($booking, $resource, $timetable, $response));
                    $this->line('Mail sent to ' . $this->user->email);
                    $this->log[] = 'Mail sent to ' . $this->user->email;
                    break;
                }
            }
        }
        Log::info('Booking processed', $this->log);
    }

    public function makeBooking(Booking  $booking, Resource $resource, Timetable $timetable){
        $this->log[] = 'Prebooking '.$timetable->name;
        $this->line('PreBooking '.$timetable->name);
        $prebooking = $this->preBooking($booking, $resource, $timetable);
        if(isset($prebooking['status']) && $prebooking['status'] === 'fail') return ['error' => 'Prebooking error '.$prebooking['message']];
        $this->log[] = 'Payment method '.$timetable->name;
        $this->line('Payment method '.$timetable->name);
        $prebooking = $this->paymentMethodSelection($prebooking);
        if(isset($prebooking['status']) && $prebooking['status'] === 'fail') return ['error' => 'Payment method selection error '.$prebooking['message']];
        $this->log[] = 'Confirmation '.$timetable->name;
        $this->line('Confirmation '.$timetable->name);
        $prebooking = $this->confirmation($prebooking);
        if(isset($prebooking['status']) && $prebooking['status'] === 'fail') return ['error' => 'Confirmation error '.$prebooking['message']];
        $this->log[] = 'Confirmation Match '.$timetable->name;
        $this->line('Confirmation Match '.$timetable->name);
        $prebooking = $this->confirmationMatch($prebooking);
        if(isset($prebooking['status']) && $prebooking['status'] === 'fail') return ['error' => 'Confirmation match error '.$prebooking['message']];
        return $prebooking;
    }

    public function preBooking(Booking $booking, Resource $resource, Timetable $timetable)
    {
        if($this->service->login()){
            try{
                $response = $this->service->preBooking($booking, $resource, $timetable);
                if(isset($response['status']) && $response['status'] === 'fail') {
                    $this->line('Prebooking error '. $response['message']);
                    Log::error('Prebooking error '.$timetable->name.' '.$response['message']);
                    return ['status' => 'fail', 'message' => 'Prebooking '.$timetable->name.' '.$response['message']];
                }
                $this->line('Prebooking Ok');
                return $response;
            }catch(\Exception $e){
                Log::error('Prebooking error '.$timetable->name.' '.$e->getMessage());
                return ['status' => 'fail', 'message' => 'Prebooking '.$timetable->name.' '.$e->getMessage()];
            }
        }else return ['status' => 'fail', 'message' => 'No logged'];
    }

    public function paymentMethodSelection($prebooking)
    {
        try{
            $response = $this->service->paymentMethodSelection($prebooking["payment_intent_id"]);
            if(isset($response['status']) && $response['status'] === 'fail') {
                $this->line('Payment method error'. $response['message']);
                Log::error('Payment method error ' . $response['message']);
                return ['status' => 'fail', 'message' => 'Payment method error ' . $response['message']];
            }
            $this->line('Payment method Ok');
            return $response;
        }catch(\Exception $e){
            $this->line('Payment method error'. $e->getMessage());
            Log::error('Payment method error '.$e->getMessage());
            return ['status' => 'fail', 'message' => 'Payment method error '.$e->getMessage()];
        }
    }

    public function confirmation($prebooking)
    {
        try{
            $response = $this->service->confirmation($prebooking['payment_intent_id']);
            if(isset($response['status']) && $response['status'] === 'fail') {
                $this->line('Confirmation error'. $response['message']);
                Log::error('Confirmation error ' . $response['message']);
                return ['status' => 'fail', 'message' => 'Confirmation error ' . $response['message']];
            }
            $this->line('Confirmation Ok');
            return $response;
        }catch (\Exception $e) {
            $this->line('Confirmation error'. $e->getMessage());
            Log::error('Confirmation error '.$e->getMessage());
            return ['status' => 'fail', 'message' => 'Confirmation error '.$e->getMessage()];
        }
    }

    public function confirmationMatch($prebooking)
    {
        try{
            $response = $this->service->confirmationMatch($prebooking['cart']['item']['cart_item_data']['match_id']);
            if(isset($response['status']) && $response['status'] === 'fail') {
                $this->line('Confirmation match error'. $response['message']);
                Log::error('Confirmation match error ' . $response['message']);
                return ['status' => 'fail', 'message' => 'Confirmation match error ' . $response['message']];
            }
            $this->line('Confirmation match Ok');
            return $response;
        }catch (\Exception $e) {
            $this->line('Confirmation match error'. $e->getMessage());
            Log::error('Confirmation match error '.$e->getMessage());
            return ['status' => 'fail', 'message' => 'Confirmation match error '.$e->getMessage()];
        }
    }
}
