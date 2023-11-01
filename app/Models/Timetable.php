<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timetable extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'playtomic_timetable';

    public $orderable = [
        'id',
        'name',
        'playtomic_id',
        'playtomic_id_summer'
    ];

    public $filterable = [
        'id',
        'name',
        'playtomic_id',
        'playtomic_id_summer'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'playtomic_id',
        'playtomic_id_summer'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function scopeBefore($query, $timetable){
        return $query->where('id', ((int)$timetable->id)-1);
    }

    public function scopeAfter($query, $timetable){
        return $query->where('id', ((int)$timetable->id)+1);
    }
}
