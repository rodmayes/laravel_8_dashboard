<?php

namespace App\Livewire\Playtomic\Timetable;

use App\Livewire\WithConfirmation;
use App\Livewire\WithSorting;
use App\Models\Club;
use App\Models\Timetable;
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
    public $selected_timetable;

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
        $this->selected_timetable = new Timetable(); $this->selected_timetable->id = 0;
        $this->sortBy            = 'name';
        $this->sortDirection     = 'asc';
        $this->perPage           = 100;
        $this->paginationOptions = collect(config('project.pagination.options'))->pluck('id','name');
        $this->orderable         = (new Club())->orderable;
    }

    public function render()
    {
        $query = Timetable::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

       if(!isset($this->selected_timetable->id)){ $this->selected_timetable = new Timetable(); $this->selected_timetable->id = 0; }
        $timetables = $query->paginate($this->perPage);

        return view('livewire.playtomic.timetable.index', compact('query', 'timetables'));
    }

    public function deleteSelected()
    {
        //abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        Timetable::whereIn('id', $this->selected)->delete();
        $this->resetSelected();
    }

    public function confirmDelete(Timetable $timetable)
    {
        //abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // use a full syntax
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Delete the information?',
            'icon'        => 'error',
            'accept'      => [
                'label'  => 'Yes, delete it',
                'method' => 'delete',
                'params' => $timetable,
            ],
            'reject' => [
                'label'  => 'No, cancel',
            'method' => 'render'
            ],
            'style' => 'center'
        ]);
    }

    public function delete(Timetable $timetable)
    {
        $timetable->delete();
        return redirect()->route('playtomic.timetables.index');
    }

    public function showItem(Timetable $timetable){
        $this->selected_timetable = $timetable;
    }
}
