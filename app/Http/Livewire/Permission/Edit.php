<?php

namespace App\Http\Livewire\Permission;

use App\Models\Permission;
use Livewire\Component;
use WireUi\Traits\Actions;

class Edit extends Component
{
    use Actions;

    public $permission;

    public function mount(Permission $permission)
    {
        $this->permission = $permission;
    }

    public function render()
    {
        return view('livewire.permission.edit');
    }

    public function submit()
    {
        try {
            $this->validate();
            $this->permission->save();
            //$this->emit('item-updated');
            //$this->notification(['timeout' => 1500, 'title' => 'Action', 'description' => 'Permission updated!', 'icon' => 'success']);
            return redirect()->route('user_management.permissions.index');
        }catch(\Exception $e){
            $this->emit('closeModal');
            $this->notification()->error('Error',$e->getMessage());
        }
    }

    protected function rules(): array
    {
        return [
            'permission.title' => [
                'string',
                'required',
            ],
            'permission.section' => [
                'string',
                'required',
            ]
        ];
    }
}
