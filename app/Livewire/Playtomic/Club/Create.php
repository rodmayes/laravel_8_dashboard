<?php

namespace App\Livewire\Playtomic\Club;

use App\Models\Club;
use App\Models\Resource;
use Livewire\Component;

class Create extends Component
{
    public $club;

    public $resources = [];

    public $listsForFields = [];

    public function mount(Club $club)
    {
        $this->club = $club;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.playtomic.club.create');
    }

    public function submit()
    {
        $this->validate();
        $this->club->save();

        return redirect()->route('playtomic.clubs.index');
    }

    protected function rules(): array
    {
        return [
            'club.name' => [
                'string',
                'required',
            ],
            'club.playtomic_id' => [
                'string',
                'required'
            ],
            'club.days_min_booking' => [
                'integer',
                'required',
            ],
            'club.timetable_summer' => [
                'integer',
                'required',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['resources'] = Resource::pluck('id');
    }
}
