<?php

namespace App\Http\Resources\Staff\History;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'history_id' => $this->id,
            'room_name' => $this->room->room_name,
            'student_name' => $this->student->name,
            'booking_date' => $this->booking_date,
            'usage_status' => $this->usage_status,
            'verified_at' => $this->verified_at,
        ];
    }
}
