<?php

namespace App\Http\Livewire\Playtomic\Booking;

use App\Models\Booking;
use App\Models\Club;
use App\Models\Resource;
use App\Models\Timetable;
use Livewire\Component;

class Edit extends Component
{
    public $listsForFields = [];
    public $booking;
    public $resources;
    public $timetables;

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        $this->resources = explode(",",$this->booking->resources);
        $this->timetables = explode(",",$this->booking->timetables);
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.playtomic.booking.edit');
    }

    public function submit()
    {
        $this->validate();
        $this->booking->resources = implode(",",$this->resources);
        $this->booking->timetables = implode(",", $this->timetables);
        $this->booking->name = $this->booking->club->name.' '.$this->booking->start_at;
        $this->booking->public = isset($this->public) ? $this->public : false;
        $this->booking->save();

        return redirect()->route('playtomic.bookings.index');
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
        $this->listsForFields['club'] = Club::pluck('name','id');
        $this->listsForFields['resource'] = Resource::get()->map(function ($item) {
            return ['name' => $item->name.'-'.$item->club->name, 'id' => $item->id, 'club' => $item->club->name];
        })->pluck('name','id');
        $this->listsForFields['timetable'] = Timetable::pluck('name','id');
        $this->listsForFields['booking_preference'] = collect(
            [
                ['id' => 'timetable', 'name' => 'Time preference'],
                ['id' => 'resource', 'name' => 'Resource preference']
            ]
        )->pluck('name','id');
        $this->listsForFields['status'] = collect(
            [
                ['id' => 'on-time', 'name' => 'On time'],
                ['id' => 'time-out', 'name' => 'Time out'],
                ['id' => 'closed', 'name' => 'Closed']
            ]
        )->pluck('name','id');
    }
}
