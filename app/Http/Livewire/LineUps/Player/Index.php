<?php

namespace App\Http\Livewire\LineUps\Player;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\LineUps\Competition;
use App\Models\LineUps\Configuration;
use App\Models\LineUps\Player;
use App\Models\LineUps\Team;
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
    public $listsForFields;

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
        $this->selected_item     = new Player(); $this->selected_item->id = 0;
        $this->sortBy            = 'name';
        $this->sortDirection     = 'asc';
        $this->perPage           = 100;
        $this->paginationOptions = collect(config('project.pagination.options'))->pluck('id','name');
        $this->orderable         = (new Player())->orderable;
        $this->active_year       = Configuration::byActiveYear()->first();
        $this->initListsForFields();
    }

    public function render()
    {
        $query = Player::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

       if(!isset($this->selected_item->id)){ $this->selected_item = new Player(); $this->selected_item->id = 0; }
        $players = $query->paginate($this->perPage);

        return view('livewire.line-ups.player.index', compact('query', 'players'));
    }

    public function confirmDelete(Player $player)
    {
        abort_if(Gate::denies('line-ups.player_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // use a full syntax
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Delete the information?',
            'icon'        => 'error',
            'accept'      => [
                'label'  => 'Yes, delete it',
                'method' => 'delete',
                'params' => $player,
            ],
            'reject' => [
                'label'  => 'No, cancel',
                'method' => 'render'
            ],
            'style' => 'center'
        ]);
    }

    public function delete(Player $player)
    {
        $player->delete();
        return redirect()->route('line-ups.player.administration.administration.index');
    }

    public function confirmMassive()
    {
        abort_if(Gate::denies('line-ups.player_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
            Player::whereIn('id', $this->selected)->delete();
            $this->resetSelected();
            $this->notification(['timeout' => 1500, 'title' => 'Action', 'description' => 'Players deleted!', 'icon' => 'success']);
        }catch(\Exception $e){
            $this->notification()->error('Error',$e->getMessage());
        }
    }

    public function showItem(Player $player){
        $this->selected_item = $player;
        //$this->teams = $this->selected_item->teams->pluck('id')->unique();
        //$this->competitions = $this->selected_item->competitions->pluck('id');
    }

    protected function initListsForFields(): void{
        $this->listsForFields['competitions'] = Competition::byYear($this->active_year)->orderBy('name')->get();
        $this->listsForFields['teams'] = Team::byYear($this->active_year)->orderBy('name')->get();
    }
}
