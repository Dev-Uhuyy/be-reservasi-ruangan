<?php

namespace App\Http\Resources\Staff;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Staff\VerificationResource;

class VerificationCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => VerificationResource::collection($this->collection),
            'meta' => [
                "success" => true,
                "message" => "Daftar verifikasi berhasil diambil!",
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
