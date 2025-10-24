<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'booking_id' => $this->id,
            'status' => $this->combined_status,
            'student_name' => $this->student?->name,
            'room_name' => $this->room?->room_name,
            'booking_date' => date('H:i', strtotime($this->start_time)) . ' - ' . date('H:i', strtotime($this->end_time)),
            'purpose' => $this->reservation?->purpose,
            'rejection_reason' => $this->when(
                $this->combined_status === 'rejected',
                fn() => $this->reservation?->rejection_reason
            ),
        ];
    }
}
