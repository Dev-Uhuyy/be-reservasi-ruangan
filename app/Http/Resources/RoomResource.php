<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->room_name,
            'floor' => $this->floor,
            'capacity' => $this->capacity,
            'facilities' => $this->facilities,
            'status' => $this->status,
            'schedules' => ScheduleResource::collection($this->when(isset($this->schedules), $this->schedules)),
        ];
    }
}
