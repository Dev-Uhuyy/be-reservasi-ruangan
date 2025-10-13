<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Definisikan struktur JSON yang Anda inginkan untuk Staff
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'email'     => $this->email,
            'nip'       => $this->nim_nip, // Sesuaikan dengan nama kolom di database
            'floor'     => $this->floor,   // Sesuaikan dengan nama kolom di database
            'program'   => $this->program,
            'role'      => $this->getRoleNames()->first(), // Ambil role pertama
        ];
    }
}
