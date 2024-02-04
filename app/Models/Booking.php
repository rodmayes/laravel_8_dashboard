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
        'resources',
        'timetables',
        'status',
        'started_at',
        'timetable_id',
        'created_by',
        'public',
        'booked_at',
        'player'
    ];

    public $filterable = [
        'id',
        'name',
        'playtomic_id',
        'club_id',
        'resources',
        'status',
        'started_at',
        'timetables',
        'created_by',
        'public',
        'booking_preference',
        'booked_at',
        'player'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'started_at',
        'booked_at'
    ];

    protected $fillable = [
        'id',
        'name',
        'playtomic_id',
        'club_id',
        'resources',
        'timetables',
        'status',
        'timetable_id',
        'started_at',
        'created_by',
        'public',
        'booking_preference',
        'booked_at',
        'player_email'
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function creator()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function player()
    {
        return $this->hasOne(User::class, 'email', 'player_email');
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

    public function getIsBookedAttribute(){
        return !is_null($this->booked_at);
    }

    public function getPlayerNameAttribute(){
        return $this->player->name;
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
        return $query->where('status', '<>', 'closed');
    }

    public function scopeBooked($query){
        return $query->whereNotNull('booked');
    }

    public function scopeNoBooked($query){
        return $query->whereNull('booked');
    }

    public function scopeByPlayer($query, $value){
        return $query->where('player_email', mb_strtolower(trim($value)));
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

    public function setBooked(){
        $this->booked_at = now();
        return $this->save();
    }

    public function toggleBooked(){
        $this->booked_at = is_null($this->booked_at) ? now() : null;
        return $this->save();
    }
}
