<?php

namespace App\Http\Livewire\LineUps\Administration;

use App\Models\LineUps\Configuration;
use App\Models\LineUps\Year;
use Livewire\Component;
use WireUi\Traits\Actions;

class Index extends Component
{
    use Actions;

    public $active_year;
    public $active_page = 'years';

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount(){
        $this->active_page = 'years';
        $this->active_year = Configuration::byActiveYear()->first();
    }

    public function render()
    {
        $data = [
            'year' => Configuration::byActiveYear()->first() ?: new Year,
        ];

        //dd($data);
        return view('livewire.line-ups.administration.index', compact('data'));
    }

    public function setActiveYear(){
        try{
            $year = Configuration::byActiveYear()->first();
            $year->value = $this->active_year->value;
            $year->save();
            $this->notification()->success('Action','Active year changed!');
        }catch (\Exception $e){
            $this->notification()->error('Error !!!', $e->getMessage());
        }
    }
}
