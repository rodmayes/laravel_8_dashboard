<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'images';

    public $orderable = [
        'id',
        'name',
        'filename',
        'imageable_type',
        'imageable_id',
        'image'
    ];

    public $filterable = [
        'id',
        'name',
        'imageable_type',
        'imageable_id',
        'image'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'id',
        'name',
        'filename',
        'imageable_type',
        'imageable_id',
        'image'
    ];

    public function users()
    {
        return $this->morphTo(User::class, 'imageable');
    }
}
