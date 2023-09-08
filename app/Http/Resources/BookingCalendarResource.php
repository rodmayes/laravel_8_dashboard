<?php

namespace App\Http\Resources;

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
        $dt = Carbon::createFromTimeString($this->started_at->format('Y-m-d').' '.$this->timetable->name);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'club_id' => $this->club_id,
            'start' => $dt,
            'started_at' => $this->staryed_at,
            'timetable_is' => $this->timetable_id,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'public' => $this->public,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
