<?php

namespace App\Livewire\Playtomic\Club;

use App\Models\Club;
use Livewire\Component;

class Availability extends Component
{
    public $club;
    public $resources = [];
    public $availabilities = [];
    public $slots =  [];
    public $started_at = null;

    public function mount(Club $club)
    {
        $this->club = $club;
        $this->getDataAvailability();
    }

    public function render()
    {
        return view('livewire.playtomic.club.availability');
    }

    public function getDataAvailability(){
        $this->started_at = $this->started_at ?: date('Y-m-d');
        $this->resources = $this->club->resources()->get();
        foreach ($this->resources AS $resource){
            $slots = [];
            foreach ($resource->availabilities()->where('start_date', $this->started_at)->get() as $availibility)
                $slots = $availibility->slots()->orderBy('start_time')->get()->toArray();
            $this->availabilities[$resource->id]= ['resource' => $resource->name, 'slots' => $slots];
        }
       //dd($this->availabilities);
    }
}
