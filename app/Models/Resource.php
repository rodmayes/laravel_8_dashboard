<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'playtomic_resource';

    public $orderable = [
        'id',
        'name',
        'playtomic_id',
        'priority'
    ];

    public $filterable = [
        'id',
        'name',
        'playtomic_id',
        'club_id',
        'priority'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'playtomic_id',
        'priority'
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function availabilities()
    {
        return $this->hasMany(Availibility::class, 'playtomic_resource_id', 'playtomic_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function scopeByClub($query, $value){
        return $query->where('club_id', $value);
    }

    public function scopeByPlaytomicId($query, $value){
        return $query->where('playtomic_id', $value);
    }

    public function scopeFavourites($query){
        return $query;
    }

    public function scopeOrderByPriority($query, $order = 'ASC'){
        return $query->orderBy('priority', $order);
    }

    public function scopeOrderByName($query, $order = 'ASC'){
        return $query->orderBy('name', $order);
    }
}
