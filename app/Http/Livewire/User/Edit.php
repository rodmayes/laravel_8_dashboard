<?php

namespace App\Http\Livewire\User;

use App\Models\Role;
use App\Models\User;
use App\Services\PlaytomicHttpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;
use WireUi\Traits\Actions;

class Edit extends Component
{
    use Actions;

    public $user;
    public $roles = [];
    public $avatar;
    public $password = '';
    public $playtomic_password;

    public $listsForFields = [];

    public function mount(User $user)
    {
        $this->user  = $user;
        $this->roles = $this->user->roles()->pluck('id')->toArray();
        $this->initListsForFields();
    }

    public function updatedAvatar(){

    }

    public function render()
    {
        return view('livewire.user.edit');
    }

    public function submit()
    {
        $this->validate();
        $this->user->save();
        $this->user->roles()->sync($this->roles);

        return redirect()->route('user_management.users.index');
    }

    public function storePassword(){
        $this->user->password = $this->password;
        $this->user->save();

        return redirect()->route('user_management.users.edit', $this->user->id);
    }

    public function storePlaytomicPassword(){
        $this->user->playtomic_password = Crypt::encrypt($this->playtomic_password);
        $this->user->save();

        return redirect()->route('user_management.users.edit', $this->user->id);
    }

    public function refreshToken(){
        $response = (new PlaytomicHttpService($this->user))->login();
        if($response) {
            $this->user->playtomic_id = $response['user_id'];
            $this->user->playtomic_token = $response['access_token'];
            $this->user->playtomic_refresh_token = $response['refresh_token'];
            $this->user->save();
            return redirect()->route('user_management.users.edit', $this->user->id);
        }
        return $this->addError('user.playtomic_token', 'Error to refresh');
    }

    public function refreshData(){
        $this->user = User::find($this->user->id);
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
                'unique:users,email,' . $this->user->id,
            ],
            'password' => [
                'string',
            ],
            'user.playtomic_id' => [
                'nullable',
                'string'
            ],
            'user.playtomic_token' => [
                'nullable',
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
        $this->listsForFields['roles'] = Role::orderBy('title')->get();
    }

    public function uploadAvatar(Request $request, User $user){
        $request->validate([
            'avatar' => 'required|mimes:png,jpg,jpeg,webp|max:2048'
        ]);

        try {
            if ($request->hasFile('avatar')) $user->saveAvatar($request->avatar);
            $this->notification()->success(
                $title = 'Avatar saved',
                $description = 'Your avatar was successfull saved'
            );
            $this->render();
        }catch(\Exception $e){
            $this->notification()->error(
                $title = 'Error !!!',
                $description = $e->getMessage()
            );
            throw new \Exception($e->getMessage());
        }

    }
}
