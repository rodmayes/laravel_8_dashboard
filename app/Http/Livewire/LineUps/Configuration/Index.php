<?php

namespace App\Http\Livewire\LineUps\Configuration;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\LineUps\Configuration;
use App\Models\LineUps\Year;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
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
    public $orderable;
    public $search = '';
    public $selected = [];
    public $paginationOptions;
    public $selected_item;

    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'name',
        ],
        'sortDirection' => [
            'except' => 'asc',
        ]
    ];

    protected $listeners = ['item-updated' => '$refresh'];

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
        $this->selected_item = new Configuration(); $this->selected_item->id = 0;
        $this->sortBy            = 'name';
        $this->sortDirection     = 'asc';
        $this->perPage           = 100;
        $this->paginationOptions = collect(config('project.pagination.options'))->pluck('id','name');
        $this->orderable         = (new Configuration())->orderable;
    }

    public function render()
    {
        $query = Configuration::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

       if(!isset($this->selected_item->id)){ $this->selected_item = new Configuration(); $this->selected_item->id = 0; }
        $configurations = $query->paginate($this->perPage);

        return view('livewire.line-ups.configuration.index', compact('query', 'configurations'));
    }

    public function confirmDelete(Configuration $configuration)
    {
        abort_if(Gate::denies('line-ups.configuration_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // use a full syntax
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Delete the information?',
            'icon'        => 'error',
            'accept'      => [
                'label'  => 'Yes, delete it',
                'method' => 'delete',
                'params' => $configuration,
            ],
            'reject' => [
                'label'  => 'No, cancel',
                'method' => 'render'
            ],
            'style' => 'center'
        ]);
    }

    public function delete(Configuration $configuration)
    {
        try{
            $configuration->delete();
            $this->notification(['timeout' => 1500, 'title' => 'Action', 'description' => 'Configuration item deleted!', 'icon' => 'success']);
        }catch(\Exception $e){
            $this->notification()->error('Error',$e->getMessage());
        }
    }

    public function confirmMassive()
    {
        abort_if(Gate::denies('line-ups.year_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Delete the information?',
            'icon'        => 'error',
            'accept'      => [
                'label'  => 'Yes, delete it',
                'method' => 'deleteSelected'
            ],
            'reject' => [
                'label'  => 'No, cancel',
                'method' => 'render'
            ],
            'style' => 'center'
        ]);
    }

    public function deleteSelected()
    {
        try{
            Configuration::whereIn('id', $this->selected)->delete();
            $this->resetSelected();
            $this->notification(['timeout' => 2000, 'title' => 'Action', 'description' => 'Configurations deleted!', 'icon' => 'success']);
        }catch(\Exception $e){
            $this->notification()->error('Error',$e->getMessage());
        }
    }

    public function showItem(Configuration $configuration){
        $this->selected_item = $configuration;
    }
}
