<?php

namespace App\Models\LineUps;

use App\Support\HasAdvancedFilter;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompetitionTeam extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'lineups_competitions_teams';

    public $orderable = [
        'id',
        'competition_id',
        'team_id',
        'active'
    ];

    public $filterable = [
        'id',
        'competition_id',
        'team_id',
        'active'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'competition_id',
        'team_id'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function years()
    {
        return $this->hasManyThrough(Year::class, CompetitionTeam::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getIsActiveAttribute(){
        return $this->active === 1;
    }

    public function getCompetitionNameAttribute(){
        return $this->competition->name;
    }

    public function getTeamNameAttribute(){
        return $this->team->name;
    }

    public function getFullnameAttribute(){
        return $this->team->name.', '.$this->competition->fullname;
    }

    public function scopeByTeam($query, $value){
        return $query->where('team_id', $value);
    }

    public function scopeByCompetition($query, $value){
        return $query->where('competition_id', $value);
    }

    public function scopeByYear($query, $value){
        return $query->whereHas('years', function ($query) use ($value){
            $query->where('year_id', $value);
        });
    }
}
