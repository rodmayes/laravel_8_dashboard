<?php

namespace App\Http\Livewire\Playtomic\Club;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\Club;
use App\Models\Resource;
use App\Models\User;
use App\Services\PlaytomicHttpService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
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
    public $selected_club;

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
        $this->selected_club = new Club();
        $this->sortBy            = 'name';
        $this->sortDirection     = 'asc';
        $this->perPage           = 100;
        $this->paginationOptions = collect(config('project.pagination.options'))->pluck('id','name');
        $this->orderable         = (new User())->orderable;
    }

    public function render()
    {
        $query = Club::advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $clubs = $query->paginate($this->perPage);

        return view('livewire.playtomic.club.index', compact('query', 'clubs'));
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        Club::whereIn('id', $this->selected)->delete();
        $this->resetSelected();
    }

    public function confirmDelete(Club $club)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // use a full syntax
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Delete the information?',
            'icon'        => 'error',
            'accept'      => [
                'label'  => 'Yes, delete it',
                'method' => 'delete',
                'params' => $club,
            ],
            'reject' => [
                'label'  => 'No, cancel',
                'method' => 'render'
            ],
            'style' => 'center'
        ]);
    }

    public function delete(Club $club)
    {
        $club->delete();
        return redirect()->route('playtomic.clubs.index');
    }

    public function showItem(Club $club){
        $this->selected_club = $club;
    }

    public function syncResources(Club $club){
        try {
            $service = (new PlaytomicHttpService(Auth::user()));
            $service->login();
            $information_club = $service->getInformationClub($club);

            if (isset($information_club['resources']))
                foreach ($information_club['resources'] as $resource) {
                    Resource::updateOrCreate(
                        [
                            'playtomic_id' => $resource['resource_id'],
                            'club_id' => $club->id
                        ],
                        [
                            'name' => $resource['name'],
                            'playtomic_id' => $resource['resource_id'],
                            'club_id' => $club->id
                        ]);
                }
            $this->notification()->success(
                $title = 'Notice',
                $description = 'Data resources synced!'
            );
        }catch (\Exception $e){
            $this->notification()->error(
                $title = 'Error !!!',
                $description = $e->getMessage()
            );
        }
    }
}
