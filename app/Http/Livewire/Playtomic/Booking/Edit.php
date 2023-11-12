<?php

namespace App\Http\Livewire\Playtomic\Booking;

use App\Models\Booking;
use App\Models\Club;
use App\Models\Resource;
use App\Models\Timetable;
use Carbon\Carbon;
use Livewire\Component;
use WireUi\Traits\Actions;

class Edit extends Component
{
    use Actions;

    public $listsForFields = [];
    public $booking;
    public $resources = [];
    public $timetables = [];

    protected $listeners = ['dateSelected' => 'updateDate'];

    public function updateDate($date)
    {
        $this->booking->started_at = Carbon::createFromTimeString($date)->addDay();
    }

    public function updated($name){
        if($name === 'booking.club_id') {
            $this->resources = [];
        }
    }

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        $this->resources = array_map('intval',explode(",",$this->booking->resources));
        $this->timetables = array_map('intval',explode(",",$this->booking->timetables));
        $this->initListsForFields();
    }

    public function render()
    {
        if(!empty($this->booking->club_id)) {
            $this->listsForFields['resource'] = Resource::byClub($this->booking->club_id)->get();
        }
        return view('livewire.playtomic.booking.edit');
    }

    public function submit()
    {
        $this->validate();
        try {
            $this->booking->resources = implode(",", $this->resources);
            $this->booking->timetables = implode(",", $this->timetables);
            $this->booking->name = $this->booking->club->name . ' ' . $this->booking->start_at;
            $this->booking->public = $this->booking->public ?? false;
            $this->booking->save();

            $this->notification()->success(
                $title = 'Item saved',
                $description = 'This items has been saved successfully'
            );
            return redirect()->route('playtomic.bookings.index');
        }catch (\Exception $e){
            $this->notification()->error(
                $title = 'Error !!!',
                $description = $e->getMessage()
            );
        }
    }

    protected function rules(): array
    {
        return [
            'booking.club_id' => [
                'integer',
                'exists:playtomic_club,id',
                'required',
            ],
            'resources' => [
                'array',
                'required',
            ],
            'timetables' => [
                'array',
                'required',
            ],
            'booking.started_at' => [
                'required',
                //'date_format:' . config('project.date_format'),
            ],
            'booking.public' => [
                'nullable'
            ],
            'booking.booking_preference' => [
                'string',
                'nullable'
            ],
            'booking.status' => [
                'string',
                'required',
            ],
        ];
    }

    public function initListsForFields(): void
    {
        $this->listsForFields['club'] = Club::all();
        if(!empty($this->booking->club_id)) {
            $this->listsForFields['resource'] = Resource::byClub($this->booking->club_id)->get();
        }
        $this->listsForFields['timetable'] = Timetable::all();
        $this->listsForFields['booking_preference'] = collect(
            [
                ['id' => 'timetable', 'name' => 'Time preference'],
                ['id' => 'resource', 'name' => 'Resource preference']
            ]
        );
        $this->listsForFields['status'] = collect(
            [
                ['id' => 'on-time', 'name' => 'On time'],
                ['id' => 'time-out', 'name' => 'Time out'],
                ['id' => 'closed', 'name' => 'Closed']
            ]
        );
    }

    public function toggleBooked(){
        try{
            $this->booking->toggleBooked();
            $text = $this->booking->isBooked ? "booked" : 'no booked';
            $this->notification()->success(
                $title = 'Item updated',
                $description = 'This items has been '.$text.' successfully'
            );
        }catch (\Exception $e){
            $this->notification()->error(
                $title = 'Error !!!',
                $description = $e->getMessage()
            );
        }
    }
}
