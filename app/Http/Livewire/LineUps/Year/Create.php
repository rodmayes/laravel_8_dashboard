<?php

namespace App\Http\Livewire\LineUps\Year;

use App\Models\LineUps\Year;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class Create extends ModalComponent
{
    use Actions;

    public $year;

    public function mount()
    {
        $this->year = new Year();
    }

    public function render()
    {
        return view('livewire.line-ups.year.modal.create');
    }

    public function submit(){
        try {
            $this->validate();
            $this->year->save();
            $this->emit('item-updated');
            $this->notification(['timeout' => 1500, 'title' => 'Action', 'description' => 'Year created!', 'icon' => 'success']);
            $this->emit('closeModal');
        }catch(\Exception $e){
            $this->notification()->error('Error',$e->getMessage());
        }
    }

    protected function rules(): array
    {
        return [
            'year.id' => [
                'integer',
                'required',
            ],
            'year.name' => [
                'string',
                'required',
            ]
        ];
    }
}
