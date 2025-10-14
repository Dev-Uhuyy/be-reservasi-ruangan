<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RoomCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => RoomResource::collection($this->collection),
            'meta' => [
                "success" => true,
                "message" => "Rooms fetched successfully!",
                'pagination' => $this->paginationData()
            ]
        ];
    }

    /**
     * Membuat data paginasi kustom.
     *
     * @return array
     */
    private function paginationData(): array
    {
        return [
            "total" => $this->total(),
            "count" => $this->count(),
            "per_page" => (int)$this->perPage(),
            "current_page" => $this->currentPage(),
            "total_pages" => $this->lastPage(),
            "links" => [
                'previous' => $this->previousPageUrl(),
                "next" => $this->nextPageUrl(),
            ],
        ];
    }
}
