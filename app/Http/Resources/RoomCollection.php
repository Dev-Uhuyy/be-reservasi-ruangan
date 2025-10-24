<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RoomCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($room) {
                return [
                    'id' => $room->id,
                    'name' => $room->room_name,
                    'floor' => $room->floor,
                    'capacity' => $room->capacity,
                    'facilities' => $room->facilities,
                    'status' => $room->status,
                ];
            }),
            'meta' => [
                'status_code' => 200, 
                'success' => true,
                'message' => 'Data ruangan berhasil diambil!',
                'pagination' => [
                    'total' => $this->total(),
                    'per_page' => $this->perPage(),
                    'current_page' => $this->currentPage(),
                    'last_page' => $this->lastPage(),
                    'from' => $this->firstItem(),
                    'to' => $this->lastItem(),
                ]
            ]
        ];
    }
}