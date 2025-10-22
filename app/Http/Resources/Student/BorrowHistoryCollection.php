<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BorrowHistoryCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => BorrowHistoryResource::collection($this->collection),
            'meta' => [
                "success" => true,
                "message" => "Riwayat peminjaman berhasil diambil!",
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

