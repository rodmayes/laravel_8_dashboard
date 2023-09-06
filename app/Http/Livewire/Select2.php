<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Select2 extends Component
{
    public $selected = '';

    public function render()
    {
        return view('livewire.select2')
            ->layout('layouts.select2');
    }
}
