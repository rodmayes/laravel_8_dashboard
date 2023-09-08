<?php

namespace App\Http\Livewire\Playtomic\Booking;

use App\Http\Resources\BookingCalendarResource;
use App\Models\Booking;
use Livewire\Component;
use Asantibanez\LivewireCalendar\LivewireCalendar;

class PlaytomicCalendar extends LivewireCalendar
{
    public function render()
    {
        return view('livewire.playtomic.booking.playtomic-calendar');
    }

    public function events(): \Illuminate\Support\Collection
    {
        $bookings = Booking::all(); //notCLosed()->get()();
        return collect(BookingCalendarResource::collection($bookings));
    }
}
