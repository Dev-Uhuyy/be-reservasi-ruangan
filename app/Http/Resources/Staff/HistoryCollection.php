<?php

namespace App\Http\Resources\Staff\History;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HistoryCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => HistoryResource::collection($this->collection),
            'meta' => [
                "success" => true,
                "message" => "Riwayat verifikasi berhasil diambil!",
                'pagination' => [
                    "total" => $this->total(),
                    "per_page" => (int)$this->perPage(),
                    "current_page" => $this->currentPage(),
                    "last_page" => $this->lastPage(),
                ]
            ]
        ];
    }
}
