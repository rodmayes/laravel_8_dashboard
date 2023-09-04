<?php

namespace App\Models;

use \DateTimeInterface;
use App\Support\HasAdvancedFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Booking extends Model
{
    use HasFactory;
    use HasAdvancedFilter;
    use SoftDeletes;

    public $table = 'playtomic_booking';

    public $orderable = [
        'id',
        'name',
        'playtomic_id',
        'club_id',
        'resource_id',
        'status',
        'started_at',
        'timetable_id',
        'created_by',
        'public'
    ];

    public $filterable = [
        'id',
        'name',
        'playtomic_id',
        'club_id',
        'resource_id',
        'status',
        'started_at',
        'timetable_id',
        'created_by',
        'public'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'started_at'
    ];

    protected $fillable = [
        'id',
        'name',
        'playtomic_id',
        'club_id',
        'resource_id',
        'status',
        'timetable_id',
        'started_at',
        'created_by',
        'public'
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function creator()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getIsOnTimeAttribute(){
        return $this->status === 'on-time';
    }

    public function scopeByClub($query, $value){
        return $query->where('club_id', $value)
            ->orWhere('public', 1);
    }

    public function scopeByUser($query, $value){
        return $query->where('created_by', $value);
    }

    public function scopeOnTime($query){
        return $query->where('status', 'on-time');
    }

    public function scopeNotClosed($query){
        return $query->where('status', '!=', 'closed');
    }

    public function setStatusTimeOut(){
        $this->status = 'time-out';
        return $this->save();
    }

    public function setStatusClosed(){
        $this->status = 'closed';
        return $this->save();
    }

    public function setStatusOnTime(){
        $this->status = 'on-time';
        return $this->save();
    }
}
