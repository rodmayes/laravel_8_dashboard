<?php

namespace App\Models\LineUps;

use App\Support\HasAdvancedFilter;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Year extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'lineups_years';

    public $orderable = [
        'id',
        'name'
    ];

    public $filterable = [
        'id',
        'name'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name'
    ];

    public function competitions()
    {
        return $this->hasMany(Competition::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function scopeByCompetition($query, $value){
        return $query->whereHas('competitions', function ($query) use ($value){
            $query->where('competition_id', $value);
        });
    }

    public function scopeByYear($query, $value){
        return $query->where('id', $value);
    }
}
