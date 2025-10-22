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
            // Menggunakan accessor dari model, yang sudah kita perbaiki agar aman
            'status' => $this->combined_status,
            // Gunakan Nullsafe Operator (?->) untuk mencegah error jika relasi null.
            'student_name' => $this->student?->name,
            'room_name' => $this->room?->room_name,
            'booking_date' => date('H:i', strtotime($this->start_time)) . ' - ' . date('H:i', strtotime($this->end_time)),
            // Detail tambahan yang mungkin berguna
            'purpose' => $this->reservation?->purpose,
            'rejection_reason' => $this->when(
                $this->combined_status === 'rejected',
                fn() => $this->reservation?->rejection_reason
            ),
        ];
    }
}
