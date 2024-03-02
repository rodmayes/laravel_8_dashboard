<?php

namespace App\Models\LineUps;

use App\Support\HasAdvancedFilter;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competition extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'lineups_competitions';

    public $orderable = [
        'id',
        'name',
        'year_id',
        'couples_number'
    ];

    public $filterable = [
        'id',
        'name',
        'year_id',
        'couples_number'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'couples_number',
        'year_id'
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class, CompetitionTeam::class);
    }

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getYearNameAttribute(){
        return $this->year->name;
    }

    public function getFullnameAttribute(){
        return $this->name.' ['.$this->year->name.']';
    }

    public function scopeByTeam($query, $value){
        return $query->whereHas('teams', function ($query) use ($value){
            $query->where('team_id', $value);
        });
    }

    public function scopeByYear($query, $value){
        return $query->where('year_id', $value);
    }
}
