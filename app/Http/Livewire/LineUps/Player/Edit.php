<?php

namespace App\Http\Livewire\LineUps\Player;

use App\Models\LineUps\CompetitionTeam;
use App\Models\LineUps\Player;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class Edit extends ModalComponent
{
    use Actions;

    public $player;
    public $listsForFields;
    public $competitions = [];
    public $ranking;

    public function mount(Player $player)
    {
        $this->player = $player;
        $this->competitions = $this->player->competitions->pluck('id')->toArray();
        $this->initListsForFields();
    }

    public function render()
    {
        $this->competitions = $this->player->competitions->pluck('id')->toArray();
        return view('livewire.line-ups.player.modal.edit');
    }

    public function submit()
    {
        try {
            $this->validate();
            $this->player->save();
            $this->player->competitions()->sync($this->competitions, ['ranking', $this->ranking]);
            $this->emit('item-updated');
            $this->notification(['timeout' => 1500, 'title' => 'Action', 'description' => 'Player updated!', 'icon' => 'success']);
        }catch(\Exception $e){
            $this->emit('closeModal');
            $this->notification()->error('Error',$e->getMessage());
        }
    }

    protected function rules(): array
    {
        return [
            'player.name' => [
                'string',
                'required',
            ],
            'player.email' => [
                'string',
                'required',
            ],
            'player.position' => [
                'string',
                'required'
            ],
            'competitions' => [
                'nullable'
            ]
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['competitions'] = CompetitionTeam::all();
        $this->listsForFields['positions'] = [
            ['id' => 'drive', 'name' => 'Drive'],
            ['id' => 'reverse', 'name' => 'Reverse'],
            ['id' => 'both', 'name' => 'Both']
        ];
    }
}
