<?php

namespace App\Http\Livewire\LineUps\Competition;

use App\Models\LineUps\Competition;
use App\Models\LineUps\Year;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class Create extends ModalComponent
{
    use Actions;

    public $competition;
    public $listsForFields = [];

    public function mount()
    {
        $this->competition = new Competition;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.line-ups.competition.modal.create');
    }

    public function submit(){
        try {
            $this->validate();
            $this->competition->save();
            $this->emit('item-updated');
            $this->notification(['timeout' => 1500, 'title' => 'Action', 'description' => 'Competition created!', 'icon' => 'success']);
            $this->emit('closeModal');
        }catch(\Exception $e){
            $this->notification()->error('Error',$e->getMessage());
        }
    }

    protected function rules(): array
    {
        return [
            'competition.name' => [
                'string',
                'required',
            ],
            'competition.year_id' => [
                'integer',
                'required',
            ],
            'competition.couples_number' => [
                'integer',
                'required',
            ]
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['years'] = Year::orderBy('id', 'desc')->get();
    }
}
