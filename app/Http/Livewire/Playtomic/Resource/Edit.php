<?php

namespace App\Http\Livewire\Playtomic\Resource;

use App\Models\Club;
use App\Models\Resource;
use Livewire\Component;

class Edit extends Component
{
    public $listsForFields = [];
    public $resource;

    public function mount(Resource $resource)
    {
        $this->resource = $resource;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.playtomic.resource.edit');
    }

    public function submit()
    {
        $this->validate();
        $this->resource->save();

        return redirect()->route('playtomic.resources.index');
    }

    protected function rules(): array
    {
        return [
            'resource.name' => [
                'string',
                'nullable',
            ],
            'resource.playtomic_id' => [
                'string',
                'nullable',
            ],
            'resource.club_id' => [
                'integer',
                'required'
            ],
            'resource.priority' => [
                'integer'
            ]
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['club'] = Club::pluck('name', 'id');
    }
}
