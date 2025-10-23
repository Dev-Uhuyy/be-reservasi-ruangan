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
                'total' => $this->total(), // Changed from $this->collection->total()
                'per_page' => $this->perPage(), // Changed from $this->collection->perPage()
                'current_page' => $this->currentPage(), // Changed from $this->collection->currentPage()
                'last_page' => $this->lastPage(), // Changed from $this->collection->lastPage()
                'from' => $this->firstItem(), // Changed from $this->collection->firstItem()
                'to' => $this->lastItem(), // Changed from $this->collection->lastItem()
                'status_code' => 201,
                'success' => true,
                'message' => 'Data Room berhasil!'
            ]
        ];
    }
}
