<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class LotteryResults extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'lottery_results';

    public $orderable = [
        'id',
        'date_at',
        'numbers',
        'frequencies'
    ];

    public $filterable = [
        'id',
        'date_at',
        'numbers',
        'frequencies'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'date_at'
    ];

    protected $fillable = [
        'date_at',
        'numbers',
        'frequencies'
    ];

    /*
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    */

    public function numbers(): Attribute
    {
        return Attribute::make(
            get: fn (String $value) => json_decode($value),
            set: fn (string $value) => json_encode($value),
        );
    }

    public function date_atDate(): Attribute
    {
        return Attribute::make(
            get: fn (String $value) => \Carbon\Carbon::createFromFormat('Y-m-d', $value)
        );
    }
}
