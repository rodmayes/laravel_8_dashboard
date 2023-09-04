<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slot extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'playtomic_slots';

    public $orderable = [
        'id',
        'start_time',
        'duration',
        'price',
        'availibility_id'
    ];

    public $filterable = [
        'id',
        'start_time',
        'duration',
        'price',
        'availibility_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'start_time'  => 'datetime:H:i'
    ];

    protected $fillable = [
        'name',
        'start_time',
        'duration',
        'price',
        'availibility_id'
    ];

    public function availibility()
    {
        return $this->belongsTo(Resource::class, 'id', 'availibility_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('H:i');
    }

    public function scopeByAvailibility($query, $value){
        return $query->where('availibility_id', $value);
    }

    public function scopeOrderByStartTime($query, $order = 'ASC'){
        return $query->orderBy('start_time', $order);
    }
}
