<?php

namespace App\Http\Livewire;
use Livewire\Component;
use Carbon\Carbon;

class Calendar extends Component
{
    public $date;
    public $bookings;

    public function mount($bookings = [], $date = null)
    {
        $this->bookings = $bookings;
        $this->date = Carbon::today();
        if ($date) {
            $this->date = Carbon::parse($date);
        }
    }

    public function render()
    {
        return view('livewire.calendar');
    }
    public function previousMonth()
    {
        $this->date = $this->date->subMonth();
    }
    public function nextMonth()
    {
        $this->date = $this->date->addMonth();
    }
}
