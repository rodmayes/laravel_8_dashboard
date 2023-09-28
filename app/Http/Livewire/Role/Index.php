<?php

namespace App\Http\Livewire\Role;

use App\Http\Livewire\WithConfirmation;
use App\Http\Livewire\WithSorting;
use App\Models\Role;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use WithSorting;
    use WithConfirmation;

    public $perPage;

    public $orderable;

    public $search = '';

    public $selected = [];

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
        $this->sortBy            = 'id';
        $this->sortDirection     = 'desc';
        $this->perPage           = 100;
        $this->paginationOptions = collect(config('project.pagination.options'))->pluck('id','name');
        $this->orderable         = (new Role())->orderable;
    }

    public function render()
    {
        $query = Role::with(['permissions'])->advancedFilter([
            's'               => $this->search ?: null,
            'order_column'    => $this->sortBy,
            'order_direction' => $this->sortDirection,
        ]);

        $roles = $query->paginate($this->perPage);

        return view('livewire.role.index', compact('query', 'roles', 'roles'));
    }

    public function deleteSelected()
    {
        abort_if(Gate::denies('role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Role::whereIn('id', $this->selected)->delete();

        $this->resetSelected();
    }

    public function confirmDelete(Role $role)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // use a full syntax
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Delete the information?',
            'icon'        => 'question',
            'accept'      => [
                'label'  => 'Yes, delete it',
                'method' => 'delete',
                'params' => $role,
            ],
            'reject' => [
                'label'  => 'No, cancel',
                'method' => 'render',
            ],
        ]);
    }

    public function delete(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.roles.index');
    }

}
