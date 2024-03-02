<?php

namespace App\Http\Livewire\LineUps\Competition;

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

    public $perPage;
    public $perTeam = null;
    public $perYear = null;
    public $orderable;
    public $search = '';
    public $selected = [];
    public $paginationOptions;
    public $selected_item;
    public $active_year;
    public $listsForFields = [];

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
        'perTeam', 'perYear', 'perPage'
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
        $this->selected_item = new Competition(); $this->selected_item->id = 0;
        $this->sortBy            = 'name';
        $this->sortDirection     = 'asc';
        $this->perPage           = 100;
        $this->paginationOptions = collect(config('project.pagination.options'))->pluck('id','name');
        $this->orderable         = (new Competition())->orderable;

        $this->active_year = Configuration::byActiveYear()->first();
        $this->initListsForFields();
    }

    public function render()
    {
        $query = Competition::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

       if(!isset($this->selected_item->id)){ $this->selected_item = new Competition(); $this->selected_item->id = 0; }
        $competitions = $query->with('year')->with('teams')
            ->when($this->perYear, function($query) {
                return $query->byYear($this->perYear);
            })
            ->when($this->perTeam, function($query) {
              return $query->byTeam($this->perTeam);
            })
            ->paginate($this->perPage);

        return view('livewire.line-ups.competition.index', compact('query', 'competitions'));
    }

    public function confirmDelete(Competition $competition)
    {
        abort_if(Gate::denies('line-ups.competition_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // use a full syntax
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Delete the information?',
            'icon'        => 'error',
            'accept'      => [
                'label'  => 'Yes, delete it',
                'method' => 'delete',
                'params' => $competition,
            ],
            'reject' => [
                'label'  => 'No, cancel',
                'method' => 'render'
            ],
            'style' => 'center'
        ]);
    }

    public function delete(Competition $competition)
    {
        try{
            $competition->delete();
            $this->notification(['timeout' => 1500, 'title' => 'Action', 'description' => 'Competition deleted!', 'icon' => 'success']);
        }catch(\Exception $e){
            $this->notification()->error('Error',$e->getMessage());
        }
    }

    public function confirmMassive()
    {
        abort_if(Gate::denies('line-ups.competition_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
            Competition::whereIn('id', $this->selected)->delete();
            $this->resetSelected();
            $this->notification(['timeout' => 1500, 'title' => 'Action', 'description' => 'Competitions deleted!', 'icon' => 'success']);
        }catch(\Exception $e){
            $this->notification()->error('Error',$e->getMessage());
        }
    }

    public function showItem(Competition $competition){
        $this->selected_item = $competition;
    }

    public function showAddCompetitionYear(Competition $competition){
        $this->selected_item = $competition;
    }

    protected function initListsForFields(): void{
        $this->listsForFields['years'] = Year::orderBy('id')->get();
        $this->listsForFields['teams'] = Team::orderBy('name')->get();
    }
}
