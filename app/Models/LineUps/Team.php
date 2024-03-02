<?php

namespace App\Models\LineUps;

use App\Support\HasAdvancedFilter;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'lineups_teams';

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

    public function players()
    {
        return $this->hasManyThrough(Player::class, TeamPlayer::class);
    }

    public function competitions()
    {
        return $this->belongsToMany(Competition::class, CompetitionTeam::class);
    }

    public function years()
    {
        return $this->belongsToMany(Competition::class, CompetitionTeam::class);
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

    public function scopeByPlayer($query, $value){
        return $query->whereHas('players', function ($query) use ($value){
            $query->where('player_id', $value);
        });
    }

    public function scopeByYear($query, $value){
        return $query->whereHas('years', function ($query) use ($value){
            $query->where('year_id', $value);
        });
    }
}
