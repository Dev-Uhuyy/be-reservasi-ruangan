<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->room_name,
            'floor' => $this->floor,
            'capacity' => $this->capacity,
            'facilities' => $this->facilities,
            'status' => $this->status,
            'schedules' => $this->when(isset($this->schedules), function() {
                return $this->schedules->map(function($schedule) {
                    return [
                        'id' => $schedule->id,
                        'date' => $schedule->date,
                        'start_time' => $schedule->start_time,
                        'end_time' => $schedule->end_time,
                        'status' => $schedule->status,
                    ];
                });
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}