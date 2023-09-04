<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Availibility extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'playtomic_availibility';

    public $orderable = [
        'id',
        'start_date',
        'playtomic_resource_id'
    ];

    public $filterable = [
        'id',
        'start_date',
        'playtomic_resource_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'start_date'
    ];

    protected $fillable = [
        'name',
        'start_date',
        'playtomic_resource_id'
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class, 'playtomic_id', 'playtomic_resource_id');
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function scopeByResource($query, $value){
        return $query->where('playtomic_resource_id', $value);
    }

    public function scopeByStartDate($query, $value){
        return $query->where('start_date', $value);
    }

    public function scopeOrderByStartDate($query, $order = 'ASC'){
        return $query->orderBy('start_date', $order);
    }

    public function scopeOrderByResource($query, $order = 'ASC'){
        return $query->orderBy('playtomic_resource_id', $order);
    }
}
