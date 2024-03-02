<?php

namespace App\Http\Livewire\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class Create extends Component
{
    public $user;

    public $roles = [];
    public $password = '';
    public $playtomic_password = '';

    public $listsForFields = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.user.create');
    }

    public function submit()
    {
        $this->validate();
        $this->user->password = $this->password;
        $this->user->playtomic_password = Crypt::encrypt($this->playtomic_password);
        $this->user->save();
        $this->user->roles()->sync($this->roles);

        return redirect()->route('user_management.users.index');
    }

    protected function rules(): array
    {
        return [
            'user.name' => [
                'string',
                'required',
            ],
            'user.email' => [
                'email:rfc',
                'required',
                'unique:users,email',
            ],
            'password' => [
                'string',
                'required',
            ],
            'user.playtomic_id' => [
                'string'
            ],
            'user.playtomic_token' => [
                'string'
            ],
            'roles' => [
                'required',
                'array',
            ],
            'roles.*.id' => [
                'integer',
                'exists:roles,id',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['roles'] = Role::pluck('title', 'id');
    }
}
