<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => UserResource::collection($this->collection),
            'meta' => [
                'status_code' => 200, 
                'success' => true,
                'message' => 'Daftar pengguna berhasil diambil!',
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