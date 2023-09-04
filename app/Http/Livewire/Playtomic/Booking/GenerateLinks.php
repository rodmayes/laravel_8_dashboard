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
    public $prebooking_url;
    public $execution_response;

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function render()
    {
        $links = $this->generateLinks();
        return view('livewire.playtomic.booking.generate-links',compact('links'));
    }

    public function generateLinks(){
        $urls = [];
        $resources = Resource::byClub($this->booking->club_id)->orderByPriority()->get();
        $url_checkout = env('PLAYTOMIC_URL_CHECKOUT','https://playtomic.io/checkout/booking');

        foreach ($resources as $resource) {
            $urls[] = [
                'name' => 'Resource ' . $resource->name . ' ' . $this->booking->timetable->name,
                'url' => $url_checkout . "?s=" . $this->booking->club->playtomic_id . "~" . $resource->playtomic_id . "~" . $this->booking->started_at->format('Y-m-d') . $this->booking->timetable->playtomic_id . "~90"
            ];
            $timetableBefore = Timetable::before($this->booking->timetable)->first();
            $urls[] = [
                'name' => 'Resource ' . $resource->name . ' ' . $timetableBefore->name,
                'url' => $url_checkout . "?s=" . $this->booking->club->playtomic_id . "~" . $resource->playtomic_id . "~" . $this->booking->started_at->format('Y-m-d') . $timetableBefore->playtomic_id . "~90"
            ];
            $timetablAfter = Timetable::after($this->booking->timetable)->first();
            $urls[] = [
                'name' => 'Resource ' . $resource->name . ' ' . $timetablAfter->name,
                'url' => $url_checkout . "?s=" . $this->booking->club->playtomic_id . "~" . $resource->playtomic_id . "~" . $this->booking->started_at->format('Y-m-d') . $timetablAfter->playtomic_id . "~90"
            ];
        }
        return $urls;
    }

    public function preBooking(){
        $this->execution_response = '<p>Prebooking Init</p>';
        $service = (new PlaytomicHttpService(Auth::user()));
        $response_login = $service->login();
        if($response_login){
            $this->execution_response .= '<p>Logged</p>';
            try{
                $this->execution_response .= '<p>Prebooking</p>';
                $this->prebooking = $service->preBooking($this->booking);
                if($this->prebooking['status'] !== 'fail') {
                    $this->url_prebooking = [
                        'name' => 'Resource ' . $this->booking->resource->name . ' ' . $this->booking->timetable->name,
                        'url' => $this->playtomic_url_checkout."?s=".$this->booking->club->playtomic_id. "~".$this->booking->resource->playtomic_id."~".$this->booking->started_at->format('Y-m-d').$this->booking->timetable->playtomic_id ."~90"."~".$this->prebooking['payment_intent_id']
                    ];
                    $this->url_prebooking .= $this->url_prebooking['url'];
                }else $this->execution_response .= '<p>'.$this->prebooking['message'].'</p>';
                $this->execution_response .= '<p>Prebooking finish</p>';
            }catch(\Exception $e){
                Log::error($e->getMessage());
            }
        }else $this->addError('action','No logged!');
        return ['status' => 'fail'];
    }

    public function confirmation($prebooking){
        $this->execution_response .= '<p>Confirmation init</p>';
        return (new PlaytomicHttpService(Auth::user()))->confirmation();
    }

    public function booking(){
        (new BookingController())->makeBooking($this->booking);
        return response()->json('');
    }
}
