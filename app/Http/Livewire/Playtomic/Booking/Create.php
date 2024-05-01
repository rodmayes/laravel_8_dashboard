<?php

namespace App\Http\Livewire\Playtomic\Booking;

use App\Models\Booking;
use App\Models\Club;
use App\Models\Resource;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\Actions;

class Create extends Component
{
    use Actions;

    public $listsForFields = [];
    public $booking;
    public $resources = [];
    public $timetables = [];
    public $start_date = null;

    protected $listeners = ['dateSelected' => 'updateDate'];

    public function updateDate($date)
    {
        $this->booking->started_at = \Carbon\Carbon::createFromTimeString($date)->addDay();
    }

    public function updated($name){
        if($name === 'booking.club_id') {
            $this->initListsForFields();
        }
    }

    public function mount($start_date = null, Booking $booking)
    {
        $this->start_date = !is_null($start_date) ? $start_date : null;
        $this->booking = $booking;
        $this->booking->player_email = Auth::user()->email;
        $this->booking->duration = 90;
        $this->initListsForFields();
    }

    public function render()
    {
        if(!empty($this->booking->club_id)) {
            $this->listsForFields['resource'] = Resource::byClub($this->booking->club_id)->get();
        }
        return view('livewire.playtomic.booking.create');
    }

    public function submit()
    {
        $this->validate();
        try{
            $this->booking->resources = implode(",",$this->resources);
            $this->booking->timetables = implode(",", $this->timetables);
            $this->booking->created_by = Auth::user()->id;
           // $this->booking->public = isset($this->public) ? $this->public : false;
            $this->booking->name = $this->booking->club->name.' '.$this->booking->started_at;
            $this->booking->started_at = $this->booking->started_at ?: Carbon::now()->addDays((int)$this->booking->club->days_min_booking);
            if(Carbon::now('Europe/Andorra')->startOfDay()->diffInDays($this->booking->started_at->startOfDay()) >= (int)$this->booking->club->days_min_booking) $this->booking->status = 'on-time';
            else $this->booking->status = 'time-out';

            if(is_null($this->booking->player_email)) $this->booking->player_email = Auth::user()->email;
            $this->booking->save();

            $this->notification()->success('Item saved', 'This items has been saved successfully');
            return redirect()->route('playtomic.bookings.index');
        }catch (\Exception $e){
            $this->notification()->error('Error !!!', $e->getMessage());
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
                'nullable',
                //'date_format:' . config('project.date_format'),
            ],
            'booking.public' => [
                'nullable'
            ],
            'booking.booking_preference' => [
                'nullable'
            ],
            'booking.player_email' => [
                'string',
                'exists:users,email',
                'required',
            ],
            'booking.duration' => [
                'integer',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['players'] = User::all();
        $this->listsForFields['club'] = Club::all();
        $this->listsForFields['resource'] = Resource::all();
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
        $this->listsForFields['duration'] = collect(
            [
                ['id' => 30, 'name' => '30min'],
                ['id' => 60, 'name' => '1h'],
                ['id' => 90, 'name' => '1h 30h'],
                ['id' => 120, 'name' => '2h'],
                ['id' => 150, 'name' => '2h 30h'],
                ['id' => 180, 'name' => '3h']
            ]
        );
    }
}
