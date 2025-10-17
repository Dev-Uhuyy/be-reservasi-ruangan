<?php

namespace App\Http\Resources\Staff;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VerificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'booking_id' => $this->id,
            'booking_date' => $this->booking_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'usage_status' => $this->usage_status,
            'room' => [
                'id' => $this->room->id,
                'name' => $this->room->room_name,
            ],
            'student' => [
                'id' => $this->student->id,
                'name' => $this->student->name,
            ],
            'verifier' => $this->whenLoaded('verifier', function () {
                return [
                    'id' => $this->verifier->id,
                    'name' => $this->verifier->name,
                ];
            }),
        ];
    }
}
