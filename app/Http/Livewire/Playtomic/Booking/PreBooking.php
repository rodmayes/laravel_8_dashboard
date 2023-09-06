<?php

namespace App\Http\Livewire\Playtomic\Booking;

use App\Http\Controllers\Playtomic\BookingController;
use App\Models\Booking;
use App\Models\Club;
use App\Models\Resource;
use App\Models\Timetable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PreBooking extends Component
{
    public $booking;
    public $prebooking;
    private $playtomic_url_checkout;
    public $url_prebooking;
    public $url_checkout;
    public $execution_response;
    public $resources;

    public $listsForFields = [];

    public function updatedClubId(){
        //$this->initListsForFields();
    }

    public function mount(Booking $booking)
    {
        $this->playtomic_url_checkout = env('PLAYTOMIC_URL_CHECKOUT','https://playtomic.io/checkout/booking');
        $this->booking = $booking;
        $this->resources = explode(",",$this->booking->resources);
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.playtomic.booking.pre-booking');
    }

    public function generate()
    {
        $this->validate();
        try{
            $this->execution_response = null;
            $this->booking->resources = implode(",",$this->resources);
            $this->url_checkout = null;
            $this->url_prebooking = null;
            $this->booking->created_by = Auth::user()->id;
            $this->booking->public = $this->booking->public ?? false;
            $this->booking->name = $this->booking->club->name.' '.$this->booking->resource->name.' '.$this->booking->start_at;
            $this->booking->status = 'on-time';

            $this->url_prebooking = [
                'name' => 'Resource ' . $this->booking->resource->name . ' ' . $this->booking->timetable->name,
                'url' => $this->playtomic_url_checkout . "?s=" . $this->booking->club->playtomic_id . "~" . $this->booking->resource->playtomic_id . "~" . $this->booking->started_at->format('Y-m-d') . $this->booking->timetable->playtomic_id . "~90"
            ];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->addError('action', 'Dramatic error you will die. '. $e->getMessage());
        }
    }

    public function booking(){
        (new BookingController())->startBooking($this->booking);
    }

    protected function rules(): array
    {
        return [
            'booking.started_at' => [
                'required',
                //'date_format:' . config('project.date_format'),
            ],
            'booking.timetable_id' => [
                'integer',
                'exists:playtomic_timetable,id',
                'required',
            ],
            'booking.club_id' => [
                'integer',
                'exists:playtomic_club,id',
                'required',
            ],
            'resources' => [
                'array',
                'required',
            ],
            'booking.public' => [
                'nullable'
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        /*
          when((int)$this->booking->resource_id > -1, function($q){
                return $q->byClub($this->booking->club_id);
            })->get()->map(function ($item) {
                return ['name' => $item->name.'-'.$item->club->name, 'id' => $item->id, 'club' => $item->club->name];
            })->pluck('name','id');
         */
        $this->listsForFields['club'] = Club::pluck('name','id');
        $this->listsForFields['resource'] = Resource::get()->map(function ($item) {
                return ['name' => $item->name.'-'.$item->club->name, 'id' => $item->id, 'club' => $item->club->name];
            })->pluck('name','id');
        $this->listsForFields['timetable'] = Timetable::pluck('name','id');
    }
}