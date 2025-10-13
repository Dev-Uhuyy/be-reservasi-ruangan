<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class MahasiswaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // return response()->json([
        //      'data' => [
        //         'name' => $this->name,
        //         'email' => $this->email,
        //         'nim' => $this->nim,
        //         'program' => $this->program,
        //         'role' => $this->getRoleNames(),
        //      ],
        //      'meta' => [
        //          'status_code' => 500,
        //          'success'     => false,
        //          'message'     => 'Terjadi kesalahan pada server, silakan coba lagi nanti.',
        //          'error'       => 'Internal Server Error'
        //      ]
        // ], 500);

        // ✅ Kembalikan sebuah array sederhana. Laravel akan mengurus sisanya.
        return [
            'id'      => $this->id,
            'name'    => $this->name,
            'email'   => $this->email,
            'nim'     => $this->nim, // Pastikan properti ini ada di model User atau ditangani dengan aman
            'program' => $this->program, // Sama seperti nim
            'role' => $this->getRoleNames()->first(),
        ];
    }
}
