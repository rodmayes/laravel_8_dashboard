<?php

namespace App\Models\LineUps;

use App\Support\HasAdvancedFilter;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamPlayer extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'lineups_teams_players';

    public $orderable = [
        'id',
        'team_id',
        'player_id'
    ];

    public $filterable = [
        'id',
        'team_id',
        'player_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name'
    ];

    public function player()
    {
        return $this->hasOne(Player::class);
    }

    public function team()
    {
        return $this->hasOne(Team::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
