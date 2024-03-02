<?php

namespace App\Models\LineUps;

use App\Support\HasAdvancedFilter;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Round extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'lineups_rounds';

    public $orderable = [
        'id',
        'competition_id',
        'match_day',
        'round_number'
    ];

    public $filterable = [
        'id',
        'competition_id',
        'match_day',
        'round_number'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'match_day'
    ];

    protected $fillable = [
        'competition_id',
        'match_day',
        'round_number'
    ];

    public function competition(){
        return $this->belongsTo(Competition::class);
    }

    public function year(){
        return $this->hasOneThrough(Year::class, Competition::class, 'year_id', 'competition_id');
    }

    public function team(){
        return $this->hasOneThrough(Team::class, CompetitionTeam::class, 'year_id', 'team_id');
    }

    protected function serializeDate(DateTimeInterface $date){
        return $date->format('Y-m-d');
    }

    public function scopeByCompetition($query, $value){
        return $query->where('competition_id', $value);
    }

    public function scopeByYear($query, $value){
        return $query->whereHas('year', function ($query) use ($value){
            $query->where('id', $value);
        });
    }

    public function scopeByTeam($query, $value){
        return $query->whereHas('team', function ($query) use ($value){
            $query->where('id', $value);
        });
    }
}
