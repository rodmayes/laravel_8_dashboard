<?php

namespace App\Http\Livewire\Playtomic\Booking;

use App\Models\Booking;
use App\Models\Club;
use App\Models\Resource;
use App\Models\Timetable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public $listsForFields = [];
    public $booking;
    public $resources;

    public function mount(Booking $booking)
    {
        $this->booking = $booking;
        $this->resources = explode(",",$this->booking->resources);
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.playtomic.booking.create');
    }

    public function submit()
    {
        try{
            $this->validate();
            $this->booking->resources = implode(",",$this->resources);
            $this->booking->created_by = Auth::user()->id;
            $this->booking->public = isset($this->public) ? $this->public : false;
            $this->booking->name = $this->booking->club->name.' '.$this->booking->start_at;
            if(Carbon::now('Europe/Andorra')->startOfDay()->diffInDays($this->booking->started_at->startOfDay()) >= (int)$this->booking->club->days_min_booking) $this->booking->status = 'on-time';
            else $this->booking->status = 'time-out';
            $this->booking->save();

            return redirect()->route('playtomic.bookings.index');
        } catch (\Exception $e) {
            $this->addError('error', 'Dramatic error you will die. '. $e->getMessage());
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

        /*
        $this->listsForFields['resource'] = Resource::
            when((int)$this->club > -1, function($q){
                return $q->byClub($this->club);
            })->get()->map(function ($item) {
                return ['name' => $item->name.'-'.$item->club->name, 'id' => $item->id, 'club' => $item->club->name];
            })->pluck('name','id');
        */
    }
}
