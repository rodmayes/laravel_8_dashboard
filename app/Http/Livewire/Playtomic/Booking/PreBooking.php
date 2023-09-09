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
    public $timetables;
    public $log;

    public $listsForFields = [];

    public function updatedClubId(){
        //$this->initListsForFields();
    }

    public function mount(Booking $booking)
    {
        $this->log = [];
        $this->playtomic_url_checkout = env('PLAYTOMIC_URL_CHECKOUT','https://playtomic.io/checkout/booking');
        $this->booking = $booking;
        $this->resources = explode(",",$this->booking->resources);
        $this->timetables = explode(",",$this->booking->timetables);
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.playtomic.booking.pre-booking');
    }

    public function generate()
    {
        $this->log = [];
        $this->validate();
        try {
            $this->execution_response = null;
            $this->booking->resources = implode(",", $this->resources);
            $this->booking->timetables = implode(",", $this->timetables);
            $this->url_checkout = null;
            $this->url_prebooking = null;
            $this->booking->created_by = Auth::user()->id;
            $this->booking->public = $this->booking->public ?? false;
            $this->booking->name = $this->booking->club->name . ' ' . $this->booking->started_at->format('d-m-Y');
            $this->booking->status = 'on-time';

            $timetables = Timetable::whereIn('id', $this->timetables)->get();
            $resources = Resource::whereIn('id', $this->resources)->get();

            foreach ($resources as $resource) {
                foreach ($timetables as $timetable) {
                    $this->url_prebooking[] = [
                        'name' => 'Resource ' . $resource->name . ' ' . $timetable->name,
                        'url' => $this->playtomic_url_checkout . "?s=" . $this->booking->club->playtomic_id . "~" . $resource->playtomic_id . "~" . $this->booking->started_at->format('Y-m-d') . $timetable->playtomic_id . "~90",
                        'resource' => $resource->id
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->addError('action', 'Dramatic error you will die. '. $e->getMessage());
        }
    }

    public function booking($resource_id){
        $resource = Resource::find($resource_id);
        $this->log = (new BookingController())->startBooking($this->booking, $resource);
    }

    protected function rules(): array
    {
        return [
            'booking.started_at' => [
                'required',
                //'date_format:' . config('project.date_format'),
            ],
            'timetables' => [
                'array',
                //'exists:playtomic_timetable,id',
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
