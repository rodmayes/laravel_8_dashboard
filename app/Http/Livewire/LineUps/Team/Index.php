<?php

namespace App\Http\Livewire\LineUps\Team;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\LineUps\Competition;
use App\Models\LineUps\Configuration;
use App\Models\LineUps\Team;
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

    public $active_year;
    public $perPage;
    public $orderable;
    public $search = '';
    public $selected = [];
    public $paginationOptions;
    public $selected_item;
    public $listsForFields = [];
    public $year;
    public $competition;
    public $years = [];
    public $competitions = [];

    protected $listeners = ['item-updated' => '$refresh'];

    protected $queryString = [
        'search' => [
            'except' => '',
        ],
        'sortBy' => [
            'except' => 'name',
        ],
        'sortDirection' => [
            'except' => 'asc',
        ],
    ];

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
        $this->selected_item = new Team(); $this->selected_item->id = 0;
        $this->sortBy            = 'name';
        $this->sortDirection     = 'asc';
        $this->perPage           = 100;
        $this->paginationOptions = collect(config('project.pagination.options'))->pluck('id','name');
        $this->orderable         = (new Team())->orderable;

        $this->active_year = Configuration::byActiveYear()->first();
        $this->year = Year::find($this->active_year->value);
        $this->initListsForFields();
    }

    public function render()
    {
        $query = Team::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

       if(!isset($this->selected_item->id)){ $this->selected_item = new Team(); $this->selected_item->id = 0; }
        $teams = $query->paginate($this->perPage);

        return view('livewire.line-ups.team.index', compact('query', 'teams'));
    }

    public function confirmDelete(Team $team)
    {
        abort_if(Gate::denies('line-ups.team_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Delete the information?',
            'icon'        => 'error',
            'accept'      => [
                'label'  => 'Yes, delete it',
                'method' => 'delete',
                'params' => $team,
            ],
            'reject' => [
                'label'  => 'No, cancel',
                'method' => 'render'
            ],
            'style' => 'center'
        ]);
    }

    public function delete(Team $team)
    {
        try{
            $team->delete();
            $this->notification(['timeout' => 1500, 'title' => 'Action', 'description' => 'Team deleted!', 'icon' => 'success']);
        }catch(\Exception $e){
            $this->notification()->error('Error',$e->getMessage());
        }
    }

    public function confirmMassive()
    {
        abort_if(Gate::denies('line-ups.team_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
            Team::whereIn('id', $this->selected)->delete();
            $this->resetSelected();
            $this->notification(['timeout' => 1500, 'title' => 'Action', 'description' => 'Teams deleted!', 'icon' => 'success']);
        }catch(\Exception $e){
            $this->notification()->error('Error',$e->getMessage());
        }
    }

    public function showItem(Team $team){
        $this->selected_item = $team;
        $this->years = $this->selected_item->years->pluck('id')->unique();
        $this->competitions = $this->selected_item->competitions->pluck('id');
    }

    protected function initListsForFields(): void{
        $this->listsForFields['competitions'] = Competition::orderBy('name')->get();
    }
}
