<?php

namespace App\Http\Livewire\LineUps\Team;

use App\Models\LineUps\Competition;
use App\Models\LineUps\Team;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class Edit extends ModalComponent
{
    use Actions;

    public $team;
    public $competitions;
    public $listsForFields = [];

    public function mount(Team $team)
    {
        $this->team = $team;
        $this->competitions = $team->competitions()->pluck('competition_id')->toArray();
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.line-ups.team.modal.edit');
    }

    public function submit()
    {
        try {
            $this->validate();
            $this->team->save();
            $this->team->competitions()->sync($this->competitions);
            $this->emit('item-updated');
            $this->notification(['timeout' => 1500, 'title' => 'Action', 'description' => 'Team updated!', 'icon' => 'success']);
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
                'required',
            ],
        ];
    }

    protected function initListsForFields(): void{
        $this->listsForFields['competitions'] = Competition::orderBy('name')->get();
    }
}
