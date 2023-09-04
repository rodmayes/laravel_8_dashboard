<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'club_id' => $this->club_id,
            'started_at' => $this->started_at,
            'timetable_is' => $this->timetable_id,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'public' => $this->public,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
