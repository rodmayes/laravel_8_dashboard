<?php

namespace App\Http\Livewire\LineUps\Competition;

use App\Models\LineUps\Competition;
use App\Models\LineUps\Year;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;


class Edit extends ModalComponent
{
    use Actions;

    public $competition;
    public $listsForFields = [];

    public function mount(Competition $competition)
    {
        $this->competition = $competition;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.line-ups.competition.modal.edit');
    }

    public function submit(){
        try {
            $this->validate();
            $this->year->save();
            $this->emit('item-updated');
            $this->notification(['timeout' => 1500, 'title' => 'Action', 'description' => 'Competition updated!', 'icon' => 'success']);
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
