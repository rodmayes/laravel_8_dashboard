<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;

class Modal extends Component
{
    public $isOpen = false;
    public $slot; // Controla si el modal está abierto

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
        return view('livewire.components.modal');
    }
}
