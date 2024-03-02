<?php

namespace App\Http\Livewire\LineUps\Configuration;

use App\Models\LineUps\Configuration;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class Create extends ModalComponent
{
    use Actions;

    public $configuration;

    public function mount()
    {
        $this->configuration = new Configuration;
    }

    public function render()
    {
        return view('livewire.line-ups.configuration.modal.create');
    }

    public function submit()
    {
        try{
            $this->validate();
            $this->configuration->save();
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
            'configuration.name' => [
                'string',
                'required',
            ],
            'configuration.value' => [
                'string',
                'required',
            ]
        ];
    }
}
