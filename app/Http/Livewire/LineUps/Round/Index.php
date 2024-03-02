<?php

namespace App\Http\Livewire\LineUps\Round;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\LineUps\Competition;
use App\Models\LineUps\Round;
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
            'except' => 'match_day',
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
        $this->selected_item = new Round(); $this->selected_item->id = 0;
        $this->sortBy            = 'match_day';
        $this->sortDirection     = 'desc';
        $this->perPage           = 100;
        $this->paginationOptions = collect(config('project.pagination.options'))->pluck('id','name');
        $this->orderable         = (new Round())->orderable;
    }

    public function render()
    {
        $query = Round::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

       if(!isset($this->selected_item->id)){ $this->selected_item = new Round(); $this->selected_item->id = 0; }
        $rounds = $query->paginate($this->perPage);

        return view('livewire.line-ups.round.index', compact('query', 'rounds'));
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('line-ups.round_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        Round::whereIn('id', $this->selected)->delete();
        $this->resetSelected();
    }

    public function confirmDelete(Round $round)
    {
        abort_if(Gate::denies('line-ups.round_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // use a full syntax
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Delete the information?',
            'icon'        => 'error',
            'accept'      => [
                'label'  => 'Yes, delete it',
                'method' => 'delete',
                'params' => $round,
            ],
            'reject' => [
                'label'  => 'No, cancel',
                'method' => 'render'
            ],
            'style' => 'center'
        ]);
    }

    public function delete(Round $round)
    {
        $round->delete();
        return redirect()->route('line-ups.administration.round.index');
    }

    public function showItem(Round $round){
        $this->selected_item = $round;
    }
}
