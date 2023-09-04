<?php

namespace App\Http\Livewire\Playtomic\Club;

use App\Models\Club;
use Livewire\Component;

class Edit extends Component
{
    public $club;

    public function mount(Club $club)
    {
        $this->club = $club;
    }

    public function render()
    {
        return view('livewire.playtomic.club.edit');
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
                'required',
            ],
            'club.days_min_booking' => [
                'integer',
                'required',
            ]
        ];
    }
}
