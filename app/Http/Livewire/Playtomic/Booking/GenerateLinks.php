<?php

namespace App\Http\Livewire\Playtomic\Booking;

use App\Http\Controllers\Playtomic\BookingController;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\Timetable;
use App\Services\PlaytomicHttpService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class GenerateLinks extends Component
{
    public $booking;
    public $resources;
    public $timetables;
    public $log = [];
    public $prebooking;

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        $this->resources = explode(",",$this->booking->resources);
        $this->timetables = explode(",",$this->booking->timetables);
        $this->prebooking = ['status' => null, 'message' => null];
    }

    public function render()
    {
        $links = $this->generateLinks();
        return view('livewire.playtomic.booking.generate-links',compact('links'));
    }

    public function generateLinks(){
        $urls = [];
        $timetables = Timetable::whereIn('id', $this->timetables)->get();
        $resources = Resource::whereIn('id', $this->resources)->get();
        $url_checkout = env('PLAYTOMIC_URL_CHECKOUT','https://playtomic.io/checkout/booking');

        foreach ($resources as $resource) {
            foreach ($timetables as $timetable) {
                $urls[] = [
                    'name' => 'Resource ' . $resource->name . ' ' . $timetable->name,
                    'url' => $url_checkout . "?s=" . $this->booking->club->playtomic_id . "~" . $resource->playtomic_id . "~" . $this->booking->started_at->format('Y-m-d') . $timetable->playtomic_id . "~90",
                    'resource' => $resource,
                    'timetable' => $timetable
                ];
            }
        }
        return $urls;
    }

    public function preBooking(Booking $booking, Resource $resource, Timetable $timetable){
        $this->log = [];
        $this->log[] = 'Prebooking Init';
        $service = (new PlaytomicHttpService(Auth::user()));
        $response_login = $service->login();
        if($response_login){
            $this->log[] = 'Logged';
            try{
                $this->log[] = 'Prebooking';
                $this->prebooking = $service->preBooking($booking, $resource, $timetable);
                if(isset($this->prebooking['status']) && $this->prebooking['status'] === 'fail') {
                    $this->log[] = $this->prebooking['message'];
                    return $this->prebooking = ['status' => 'fail', 'message' => 'Error!'];
                }
                /*$url_prebooking = [
                    'name' => 'Resource ' . $resource->name . ' ' . $timetable->name,
                    'url' => $this->playtomic_url_checkout."?s=".$this->booking->club->playtomic_id. "~".$resource->playtomic_id."~".$this->booking->started_at->format('Y-m-d').$timetable->playtomic_id ."~90"."~".$this->prebooking['payment_intent_id']
                ];*/
                $this->log[] = 'Prebooking finish';
            }catch(\Exception $e){
                Log::error($e->getMessage());
            }
            return $this->prebooking = ['status' => 'success', 'message' => 'Prebooked!'];
        }else { $this->log[] = 'Not logged'; $this->prebooking = ['status' => 'fail', 'message' => 'Not logged']; }
    }

    public function booking(Booking $booking, Resource $resource, Timetable $timetable){
        $this->log[] = (new BookingController())->startBooking($booking, $resource, $timetable);
        return $this->log;
    }
}
