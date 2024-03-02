<?php

namespace App\Models\LineUps;

use App\Support\HasAdvancedFilter;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompetitionPlayer extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'lineups_competitions_players';

    public $orderable = [
        'id',
        'competition_id',
        'player_id',
        'score'
    ];

    public $filterable = [
        'id',
        'competition_id',
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

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function competitions()
    {
        return $this->hasMany(Competition::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function scopeByCompetition($query, $value){
        return $query->where('competition_id', $value);
    }

    public function scopeByPlayer($query, $value){
        return $query->where('player_id', $value);
    }
}
