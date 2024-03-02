<?php

namespace App\Models\LineUps;

use App\Support\HasAdvancedFilter;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restrictions extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'lineups_restrictions';

    public $orderable = [
        'id',
        'player_id',
        'round_id',
        'condition',
        'value'
    ];

    public $filterable = [
        'id',
        'player_id',
        'round_id',
        'condition',
        'value'
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
        return $this->belongsTo(Player::class, 'id', 'player_id');
    }

    public function round()
    {
        return $this->hasMany(Round::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
