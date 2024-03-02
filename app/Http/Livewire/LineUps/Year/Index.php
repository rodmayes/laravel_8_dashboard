<?php

namespace App\Http\Livewire\LineUps\Year;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
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
    public $year;
    public $selected_year;

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
        $this->selected_year = new Year(); $this->selected_year->id = 0;
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 100;
        $this->paginationOptions = collect(config('project.pagination.options'))->pluck('id','name');
        $this->orderable         = (new Year())->orderable;
    }

    public function render()
    {
        $query = Year::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

       if(!isset($this->selected_year->id)){ $this->selected_year = new Year(); $this->selected_year->id = 0; }
       $years = $query->paginate($this->perPage);

        return view('livewire.line-ups.year.index', compact('query', 'years'));
    }

    public function confirmDelete(Year $year)
    {
        abort_if(Gate::denies('line-ups.year_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Delete the information?',
            'icon'        => 'error',
            'accept'      => [
                'label'  => 'Yes, delete it',
                'method' => 'delete',
                'params' => $year,
            ],
            'reject' => [
                'label'  => 'No, cancel',
                'method' => 'render'
            ],
            'style' => 'center'
        ]);
    }

    public function delete(Year $year)
    {
        try{
            $year->delete();
            $this->notification(['timeout' => 1500, 'title' => 'Action', 'description' => 'Year deleted!', 'icon' => 'success']);
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
            Year::whereIn('id', $this->selected)->delete();
            $this->resetSelected();
            $this->notification(['timeout' => 1500, 'title' => 'Action', 'description' => 'Years deleted!', 'icon' => 'success']);
        }catch(\Exception $e){
            $this->notification()->error('Error',$e->getMessage());
        }
    }

    public function showItem(Year $year){
        $this->selected_year = $year;
    }
}
