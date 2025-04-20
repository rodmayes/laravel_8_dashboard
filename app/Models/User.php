<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Lab404\Impersonate\Models\Impersonate;
use Laravolt\Avatar\Facade;

class User extends Authenticatable
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;
    use Notifiable;
    use Impersonate;

    public $table = 'users';

    public $orderable = [
        'id',
        'name',
        'email',
        'email_verified_at',
    ];

    public $filterable = [
        'id',
        'name',
        'email',
        'email_verified_at',
        'roles.title',
    ];

    protected $hidden = [
        'remember_token',
        'password',
        'password_playtomic'
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'playtomic_id',
        'playtomic_token',
        'playtomic_refresh_token',
        'password_playtomic'
    ];

    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('title', 'Admin')->exists();
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('project.datetime_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('project.datetime_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = Hash::needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasRole($role)
    {
        if(is_array($role)) {
            return $this->roles()
                ->whereIn('title', $role)
                ->exists();
        }

        return $this->roles()->whereRaw("LOWER(title) = '".strtolower($role)."'")->exists();
    }

    public function hasPermission($permission)
    {
        if(is_array($permission)) return $this->permissions()->whereIn('title', $permission)->exists();
        return $this->permissions()->where('title', $permission)->exists();
    }

    public function avatar()
    {
        return $this->morphOne('App\Models\Image', 'imageable');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function canImpersonate()
    {
        // For example
        return $this->is_admin == 1;
    }

    public function scopeByEmail($query, $value){
        return $query->where('email', $value);
    }

    public function getAvatar()
    {
        if ($this->avatar && Storage::exists($this->avatar->image)) {
            return asset(Storage::url($this->avatar->image));
        }

        return Facade::create($this->name)->toBase64();
    }

    public function saveAvatar($image){
        try {
            //$image_path = file_get_contents($image);//$image->store('image', 'public');
            $imagePath = 'public/profiles/img/';
            $img_name = 'img_' . time() . '.' . $image->extension();
            Storage::disk('local')->put($imagePath.$img_name, file_get_contents($image));
            //$image->move(public_path('img/'), $img_name);

            $image = Image::updateOrCreate(
                [
                    'imageable_type' => 'App\\Models\\User',
                    'imageable_id' => $this->id
                ],
                [
                    'imageable_type' => 'App\\Models\\User',
                    'imageable_id' => $this->id,
                    'name' => 'Avatar ' . $this->id,
                    'image' => $imagePath.$img_name
                ]);
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function setNameAttribute($value){
        $this->attributes['name'] = ucwords(trim($value));
    }

    public function setEmailAttribute($value){
        $this->attributes['email'] = mb_strtolower(trim($value));
    }
}
