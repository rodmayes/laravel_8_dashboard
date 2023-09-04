<?php

namespace App\Http\Livewire\Playtomic\Booking;

use App\Models\Booking;
use App\Models\Club;
use App\Models\Resource;
use App\Models\Timetable;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Edit extends Component
{
    public $listsForFields = [];
    public $booking;
   //public $timetables;
    public $resources;

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        $this->resources = explode(",",$this->booking->resources);
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
        $this->booking->name = $this->booking->club->name.' '.$this->booking->resource->name.' '.$this->booking->start_at;
        $this->booking->public = isset($this->public) ? $this->public : false;
        if(Carbon::now('Europe/Andorra')->startOfDay()->diffInDays($this->booking->started_at->startOfDay()) >= (int)$this->booking->club->days_min_booking) $this->booking->status = 'on-time';
        else $this->booking->status = 'time-out';
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
                //'exists:playtomic_resource,id',
                'required',
            ],
            'booking.timetable_id' => [
                'integer',
                'exists:playtomic_timetable,id',
                'required',
            ],
            'booking.started_at' => [
                'required',
                //'date_format:' . config('project.date_format'),
            ],
            'booking.public' => [
                'nullable'
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['club'] = Club::pluck('name','id');
        $this->listsForFields['resource'] = Resource::pluck('name','id');
        $this->listsForFields['timetable'] = Timetable::pluck('name','id');
    }
}
