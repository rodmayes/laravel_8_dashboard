<?php

namespace App\Http\Livewire\Playtomic\Timetable;

use App\Models\Club;
use App\Models\Resource;
use App\Models\Timetable;
use Livewire\Component;

class Create extends Component
{
    public $club;

    public $resources = [];

    public $listsForFields = [];

    public function mount(Timetable $timetable)
    {
        $this->timetable = $timetable;
    }

    public function render()
    {
        return view('livewire.playtomic.timetable.create');
    }

    public function submit()
    {
        $this->validate();
        $this->timetable->save();

        return redirect()->route('playtomic.timetables.index');
    }

    protected function rules(): array
    {
        return [
            'timetable.name' => [
                'string',
                'required',
            ],
            'timetable.playtomic_id' => [
                'string',
                'required',
            ],
            'timetable.playtomic_id_summer' => [
                'string',
                'required',
            ],
        ];
    }
}
