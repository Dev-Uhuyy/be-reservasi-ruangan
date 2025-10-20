<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'purpose' => $this->purpose,
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),

            // Tampilkan jadwal yang berhasil dipesan (jika di-load)
            'schedules' => ScheduleResource::collection($this->whenLoaded('reservationDetails', function () {
                // Kita ambil data schedule dari dalam reservationDetails
                return $this->reservationDetails->pluck('schedule');
            })),
        ];
    }
}
