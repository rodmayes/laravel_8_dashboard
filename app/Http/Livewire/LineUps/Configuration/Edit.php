<?php

namespace App\Http\Livewire\LineUps\Configuration;

use App\Models\LineUps\Configuration;
use LivewireUI\Modal\ModalComponent;
use WireUi\Traits\Actions;

class Edit extends ModalComponent
{
    use Actions;

    public $configuration;
    public $listsForFields = [];

    public function mount(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function render()
    {
        return view('livewire.line-ups.configuration.modal.edit');
    }

    public function submit()
    {
        try {
            $this->validate();
            $this->configuration->save();
            $this->emit('item-updated');
            $this->notification(['timeout' => 1500, 'title' => 'Action', 'description' => 'Configuration updated!', 'icon' => 'success']);
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
