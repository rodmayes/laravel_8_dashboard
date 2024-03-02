<?php

namespace App\Http\Livewire\Components;

use Illuminate\View\Component;

class InputNumber extends Component
{
    public $number;
    public $label;
    public $required;

    public function mount($number = 0, $label = '', $required = true){
        $this->number = $number;
        $this->label = $label;
        $this->required = $required;
    }

    public function render()
    {
        return view('lineups.components.input-number');
    }
}
