<?php

namespace App\Http\Livewire\LineUps\Year;

use App\Models\LineUps\Year;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class Edit extends ModalComponent
{
    use Actions;

    public $year;

    public function mount(Year $year){
        $this->year = $year;
    }

    public function render(){
        return view('livewire.line-ups.year.modal.edit');
    }

    public function submit(){
        try {
            $this->validate();
            $this->year->save();
            $this->emit('item-updated');
            $this->notification(['timeout' => 1500, 'title' => 'Action', 'description' => 'Year updated!', 'icon' => 'success']);
            $this->emit('closeModal');
        }catch(\Exception $e){
            $this->notification()->error('Error',$e->getMessage());
        }
    }

    protected function rules(): array{
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
/*
    public static function modalMaxWidth(): string
    {
        // 'sm'
        // 'md'
        // 'lg'
        // 'xl'
        // '2xl'
        // '3xl'
        // '4xl'
        // '5xl'
        // '6xl'
        // '7xl'
        return 'md';
    }
*/
}
