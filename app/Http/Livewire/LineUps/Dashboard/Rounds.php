<?php

namespace App\Http\Livewire\LineUps\Dashboard;

use App\Models\LineUps\Competition;
use App\Models\LineUps\Round;
use App\Models\LineUps\Team;
use App\Models\LineUps\Year;
use Livewire\Component;

class Rounds extends Component
{
    public $year;
    public $competition;
    public $team;
    public $round;
    public $rounds = [];

    protected $listeners = ['display-rounds' => 'displayRounds'];

    public function mount(Year $year){
        $this->year = $year;
    }

    public function render(){
        return view('livewire.line-ups.dashboard.rounds');
    }

    public function displayRounds(Competition $competition, Team $team){
        $this->competition = $competition;
        $this->team = $team;
        $this->rounds = Round::byYear($this->year->id)->byCompetition($this->competition->id)->orderBy('match_day', 'desc')->get();
        $this->emit('$refresh');
    }
}
