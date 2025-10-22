<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ReservationResource extends JsonResource
{
    /**
     * Transform resource menjadi array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // Gunakan resource lain untuk relasi (best practice)
            'student' => new StudentResource($this->whenLoaded('student')),
            'purpose' => $this->purpose,
            'request_date' => $this->request_date,
            'status' => $this->status,
            'rejection_reason' => $this->rejection_reason,
            // Gunakan Storage facade untuk URL
            'approval_letter' => $this->approval_letter ? Storage::disk('public')->url($this->approval_letter) : null,
            // Pisahkan resource untuk approver
            'approved_by' => new ApproverResource($this->whenLoaded('approver')),
            'created_at' => $this->created_at,
        ];
    }
}
