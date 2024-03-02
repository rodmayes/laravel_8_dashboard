<?php

namespace App\Http\Livewire\LineUps\Dashboard;

use App\Models\LineUps\Competition;
use App\Models\LineUps\Configuration;
use App\Models\LineUps\Round;
use App\Models\LineUps\Team;
use App\Models\LineUps\Year;
use Livewire\Component;
use WireUi\Traits\Actions;

class Calendar extends Component
{
    use Actions;

    public $listsForFields = [];
    public $active_year;
    public $year;
    public $competition;
    public $team;
    public $round;
    public $match_day;
    public $years = [];

    protected $listeners = ['dateSelected' => 'updateDate'];

    public function updateDate($date){
        $this->match_day = \Carbon\Carbon::createFromTimeString($date);
    }

    public function updatedTeam(){
        if($this->competition) $this->emit('display-rounds', $this->competition, $this->team);
    }

    public function mount(){
        $this->active_year = Configuration::byActiveYear()->first();
        $this->year = Year::find($this->active_year->value);
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.line-ups.dashboard.calendar');
    }

    public function addCompetitionsYears(Competition $competition){
        try{
            $competition->years()->sync($this->years);
            $this->notification()->success('Action','Years updated!');
        }catch (\Exception $e){
            $this->notification()->error('Error !!!', $e->getMessage());
        }
    }

    public function addRound(){
        try{
            $rounds = Round::byYear($this->year->id)->byCompetition($this->competition)->get();
            $this->round = Round::create([
                'competition_id' => $this->competition,
                'year_id' => $this->year->id,
                'match_day' => \Carbon\Carbon::createFromTimeString($this->match_day)->addDay(),
                'round_number' => $rounds->count()+1
            ]);
            $this->emit('display-rounds', $this->competition, $this->team);
            $this->notification()->success('Action','Round created!');
        }catch (\Exception $e){
            $this->notification()->error('Error !!!', $e->getMessage());
        }
    }

    protected function initListsForFields(): void{
        $this->listsForFields['years'] = Year::orderBy('id')->get();
        $this->listsForFields['competitions'] = Competition::orderBy('name')->get();
        $this->listsForFields['teams'] = Team::orderBy('name')->get();
        $this->listsForFields['rounds'] = Round::orderBy('round_number', 'desc')->get();
    }
}
