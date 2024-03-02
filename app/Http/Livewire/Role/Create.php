<?php

namespace App\Http\Livewire\Role;

use App\Models\Permission;
use App\Models\Role;
use Livewire\Component;

class Create extends Component
{
    public $role;

    public $permissions = [];
    public $listsForFields = [];

    public function mount(Role $role)
    {
        $this->role = $role;
        $this->permissions = $this->role->permissions()->pluck('id')->toArray();
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.role.create');
    }

    public function submit()
    {
        $this->validate();

        $this->role->save();
        $this->role->permissions()->sync($this->permissions);

        return redirect()->route('user_management.roles.index');
    }

    protected function rules(): array
    {
        return [
            'role.title' => [
                'string',
                'required',
            ],
            'permissions' => [
                'required',
                'array',
            ],
            'permissions.*.id' => [
                'integer',
                'exists:permissions,id',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $permissions = Permission::orderBy('title')->get()->mapToGroups(function ($item, $key) {
            $label = explode(".", $item->title);
            return [$item->section => ['label' => $label[1], 'id' => $item->id, 'title' => $item->title]];
        });

        $this->listsForFields['permissions'] = $permissions;
    }
}
