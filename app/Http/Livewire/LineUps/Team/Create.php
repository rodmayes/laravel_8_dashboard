<?php

namespace App\Http\Livewire\LineUps\Team;

use App\Models\LineUps\Competition;
use App\Models\LineUps\Team;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class Create extends ModalComponent
{
    use Actions;

    public $team;
    public $competitions = [];
    public $listsForFields = [];

    public function mount()
    {
        $this->team = new Team;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.line-ups.team.modal.create');
    }

    public function submit()
    {
        try {
            $this->validate();
            $this->team->save();
            $this->team->competitions()->attach($this->competitions);
            $this->emit('item-updated');
            $this->notification(['timeout' => 1500, 'title' => 'Action', 'description' => 'Team created!', 'icon' => 'success']);
        }catch(\Exception $e){
            $this->emit('closeModal');
            $this->notification()->error('Error',$e->getMessage());
        }
    }

    protected function rules(): array
    {
        return [
            'team.name' => [
                'string',
                'required',
            ],
            'competitions' => [
                'array',
                'required'
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['competitions'] = Competition::orderBy('id')->get();
    }
}
