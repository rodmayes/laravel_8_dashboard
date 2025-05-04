<?php

namespace App\Livewire\Playtomic\Resource;

use App\Livewire\WithConfirmation;
use App\Livewire\WithSorting;
use App\Models\Club;
use App\Models\Resource;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\Actions;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use WithConfirmation;
    use Actions;

    public $perPage;
    public $perClub;
    public $orderable;
    public $search = '';
    public $selected = [];
    public $selected_resource;

    public $paginationOptions;

    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'id',
        ],
        'sortDirection' => [
            'except' => 'desc',
        ],
    ];

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function getSelectedCountProperty()
    {
        return count($this->selected);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function resetSelected()
    {
        $this->selected = [];
    }

    public function mount()
    {
        $this->selected_resource = new Resource(); $this->selected_resource->id = 0;
        $this->sortBy            = 'name';
        $this->sortDirection     = 'asc';
        $this->perPage           = 10;
        $this->perClub           = -1;
        $this->paginationOptions = collect(config('project.pagination.options'))->pluck('id','name');
        $this->orderable         = (new Resource())->orderable;
    }

    public function render()
    {
        $query = Resource::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ])
        ->when((int)$this->perClub != -1, function($q){
            return $q->byClub($this->perClub);
        });
        if(!isset($this->selected_resource->id)){ $this->selected_resource = new Resource(); $this->selected_resource->id = 0; }
        $resources = $query->paginate($this->perPage);
        $clubs = Club::all();

        return view('livewire.playtomic.resource.index', compact('query', 'resources', 'clubs'));
    }

    public function deleteSelected()
    {
        //abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        Resource::whereIn('id', $this->selected)->delete();
        $this->resetSelected();
    }

    public function confirmDelete(Resource $resource)
    {
        //abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // use a full syntax
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Delete the information?',
            'icon'        => 'question',
            'accept'      => [
                'label'  => 'Yes, delete it',
                'method' => 'delete',
                'params' => $resource,
            ],
            'reject' => [
                'label'  => 'No, cancel',
                'method' => 'render',
            ],
        ]);
    }

    public function delete(Resource $resource)
    {
        $resource->delete();
        return redirect()->route('playtomic.resources.index');
    }

    public function showItem(Resource $resource){
        $this->selected_resource = $resource;
    }
}
