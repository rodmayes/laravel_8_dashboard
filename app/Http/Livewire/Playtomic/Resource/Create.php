<?php

namespace App\Http\Livewire\Playtomic\Resource;

use App\Models\Club;
use App\Models\Resource;
use Livewire\Component;

class Create extends Component
{
    public $resource;

    public $club;

    public $listsForFields = [];

    public function mount(Resource $resource)
    {
        $this->resource = $resource;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.playtomic.resource.create');
    }

    public function submit()
    {
        try{
            $this->validate();
            $this->resource->save();
            //$this->club->resources()->sync($this->resource);

            return redirect()->route('playtomic.resources.index');
        } catch (\Exception $e) {
            $this->addError('error', 'Dramatic error you will die.');
        }
    }

    protected function rules(): array
    {
        return [
            'resource.name' => [
                'string',
                'required',
            ],
            'resource.playtomic_id' => [
                'string',
                'required'
            ],
            'resource.club_id' => [
                'integer',
                'exists:playtomic_club,id',
                'required',
            ],
            'resource.priority' => [
                'integer'
            ]
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['club'] = Club::all();
    }
}
