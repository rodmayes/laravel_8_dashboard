<?php

namespace App\Livewire\Playtomic\Timetable;

use App\Models\Timetable;
use Livewire\Component;

class Edit extends Component
{
    public $timetable;

    public function mount(Timetable $timetable)
    {
        $this->timetable = $timetable;
    }

    public function render()
    {
        return view('livewire.playtomic.timetable.edit');
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
