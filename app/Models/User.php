<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lab404\Impersonate\Models\Impersonate;

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

    public function getAvatar(){
        return $this->avatar ? $this->avatar->image : asset('/images/avatar-default.jpeg');
    }

    public function saveAvatar($image){
        //$image_path = file_get_contents($image);//$image->store('image', 'public');
        $img_name = 'img_'.time().'.'.$image->getClientOriginalExtension();
        $image->move(public_path('img/'), $img_name);
        $imagePath = 'img/'.$img_name;

        $image = Image::updateOrCreate(
            [
            'imageable_type' => 'App\\Models\\User',
            'imageable_id' => $this->id
            ],
            [
                'imageable_type' => 'App\\Models\\User',
                'imageable_id' => $this->id,
                'name' => 'Avatar '.$this->id,
                'image' => $imagePath
            ]);
    }
}
