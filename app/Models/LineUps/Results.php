<?php

namespace App\Models\LineUps;

use App\Support\HasAdvancedFilter;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Results extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'lineups_results';

    public $orderable = [
        'id',
        'round_id',
        'player1_id',
        'player2_id',
        'competition_id',
        'score_local_couple',
        'score_visitor_couple',
        'winner'
    ];

    public $filterable = [
        'id',
        'round_id',
        'player1_id',
        'player2_id',
        'competition_id',
        'score_local_couple',
        'score_visitor_couple',
        'winner'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'round_id',
        'player1_id',
        'player2_id',
        'competition_id',
        'score_local_couple',
        'score_visitor_couple',
        'winner'
    ];

    public function player1()
    {
        return $this->belongsTo(Player::class, 'id', 'player1_id');
    }

    public function player2()
    {
        return $this->belongsTo(Player::class, 'id', 'player2_id');
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getLocalWinAttribute(){
        return $this->winner === 'local';
    }

    public function getVisitorWinAttribute(){
        return $this->winner === 'visitor';
    }
}
