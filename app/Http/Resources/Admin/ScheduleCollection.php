<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use App\Http\Resources\Admin\ScheduleResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ScheduleCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => ScheduleResource::collection($this->collection),
            'meta' => [
                "success" => true,
                "message" => "Data jadwal berhasil diambil!",
                'pagination' => [
                    "total" => $this->total(),
                    "count" => $this->count(),
                    "per_page" => (int)$this->perPage(),
                    "current_page" => $this->currentPage(),
                    "total_pages" => $this->lastPage(),
                ]
            ]
        ];
    }
}
