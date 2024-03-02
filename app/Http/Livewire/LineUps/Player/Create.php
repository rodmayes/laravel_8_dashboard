<?php

namespace App\Http\Livewire\LineUps\Player;

use App\Models\LineUps\CompetitionTeam;
use App\Models\LineUps\Player;
use App\Models\LineUps\Team;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class Create extends ModalComponent
{
    use Actions;

    public $player;
    public $listsForFields = [];
    public $participations = [];
    public $ranking;

    public function mount(Player $player)
    {
        $this->player = $player;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.line-ups.player.modal.create');
    }

    public function submit()
    {
        try {
            $this->validate();
            $this->player->save();
            $this->player->competitions()->attach($this->participations, ['ranking', $this->ranking]);
            $this->emit('item-updated');
            $this->notification(['timeout' => 1500, 'title' => 'Action', 'description' => 'Player created!', 'icon' => 'success']);
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
            'participations' => [
                'nullable'
            ]
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['participations'] = CompetitionTeam::all();
        $this->listsForFields['positions'] = [
            ['id' => 'drive', 'name' => 'Drive'],
            ['id' => 'reverse', 'name' => 'Reverse'],
            ['id' => 'both', 'name' => 'Both']
        ];
    }
}
