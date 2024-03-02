<?php

namespace App\View\Components;

use App\Models\LineUps\CompetitionTeam;
use Illuminate\View\Component;

class SelectCompetitions extends Component
{
    public $options;

    public function __construct()
    {
        $this->options = CompetitionTeam::orderBy('fullname')->get();
    }

    public function render()
    {
        return view('components.lineups.select-competitions');
    }
}
