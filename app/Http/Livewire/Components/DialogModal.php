<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;

class DialogModal extends Component
{
    public $isOpen = false; // Controla la visibilidad del modal

    public function open()
    {
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.components.dialog-modal');
    }
}
