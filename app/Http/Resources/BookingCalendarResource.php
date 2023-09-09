<?php

namespace App\Http\Resources;

use App\Models\Timetable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingCalendarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $data = [];
        $timetables = Timetable::whereIn('id', explode(',',$this->timetables))->get();
        foreach($timetables  as $timetable){
            $dt = Carbon::createFromTimeString($this->started_at->format('Y-m-d').' '.$timetable->name);
            $data = [
                'id' => $this->id,
                'name' => $this->name,
                'club_id' => $this->club_id,
                'start' => $dt,
                'started_at' => $this->started_at,
                'timetable_is' => $this->timetable_id,
                'status' => $this->status,
                'created_by' => $this->created_by,
                'public' => $this->public,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ];
        }
        return $data;
    }
}
