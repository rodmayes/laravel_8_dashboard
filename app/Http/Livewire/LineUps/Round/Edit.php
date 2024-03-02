<?php

namespace App\Http\Livewire\LineUps\Round;

use App\Models\LineUps\Competition;
use App\Models\LineUps\Round;
use App\Models\LineUps\Team;
use Livewire\Component;

class Edit extends Component
{
    public $round;
    public $listsForFields;

    protected $listeners = ['dateSelected' => 'updateDate'];

    public function updateDate($date)
    {
        $this->round->match_day = \Carbon\Carbon::createFromTimeString($date)->addDay();
    }

    public function mount(Round $round)
    {
        $this->round = $round;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.line-ups.round.edit');
    }

    public function submit()
    {
        $this->validate();
        $this->round->save();

        return redirect()->route('line-ups.administration.round.index');
    }

    protected function rules(): array
    {
        return [
            'round.competition_id' => [
                'integer',
                'exists:line-ups_competitions,id',
                'required',
            ],
            'round.team_id' => [
                'integer',
                'exists:line-ups_teams,id',
                'required',
            ],
            'round.match_day' => [
                'required'
            ],
            'round.round_number' => [
                'integer',
                'required'
            ]
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['competitions'] = Competition::orderBy('name')->get();
        $this->listsForFields['teams'] = Team::orderBy('name')->get();
    }
}
