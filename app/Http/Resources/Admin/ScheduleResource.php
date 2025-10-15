<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\RoomResource;

class ScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // Hapus 'purpose' karena tidak ada di tabel
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status,
            // Hapus 'booked_by' karena tidak ada relasi user
            'room' => new RoomResource($this->whenLoaded('room')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'), // Tambahkan updated_at untuk konsistensi
        ];
    }
}
