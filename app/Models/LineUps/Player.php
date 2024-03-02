<?php

namespace App\Models\LineUps;

use App\Support\HasAdvancedFilter;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'lineups_players';

    public $orderable = [
        'id',
        'name',
        'email',
        'position'
    ];

    public $filterable = [
        'id',
        'name',
        'email',
        'position'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'position'
    ];

    public function teams()
    {
        return $this->hasManyThrough(Team::class, TeamPlayer::class);
    }

    public function competitions()
    {
        return $this->belongsToMany(CompetitionTeam::class, CompetitionPlayer::class, 'player_id', 'competition_team_id')->withPivot('ranking')->with(['competition', 'team']);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getIsDriveAttribute(){
        return $this->position === 'drive';
    }

    public function getIsReverseAttribute(){
        return $this->position === 'reverse';
    }

    public function getIsBothAttribute(){
        return $this->position === 'both';
    }

    public function scopeByCompetition($query, $value){
        return $query->whereHas('competitions', function ($query) use ($value){
            $query->where('competition_id', $value);
        });
    }

    public function scopeByTeam($query, $value){
        return $query->whereHas('teams', function ($query) use ($value){
            $query->where('team_id', $value);
        });
    }
}
