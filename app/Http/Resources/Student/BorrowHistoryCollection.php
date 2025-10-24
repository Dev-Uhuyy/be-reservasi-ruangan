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
                'status_code' => 200,
                'success' => true,
                'message' => 'Riwayat peminjaman berhasil diambil!',
                'pagination' => [
                    'total' => $this->total(),
                    'count' => $this->count(),
                    'per_page' => (int)$this->perPage(),
                    'current_page' => $this->currentPage(),
                    'total_pages' => $this->lastPage(),
                    'from' => $this->firstItem(),
                    'to' => $this->lastItem(),
                ]
            ]
        ];
    }
}