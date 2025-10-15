<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'email'           => $this->email,
            'nim'             => $this->nim,
            'nip'             => $this->nip,
            'floor'           => $this->when($this->hasRole('staff'), $this->floor),
            'program'         => $this->when($this->hasRole('student'), $this->program),
            'profile_picture' => $this->profile_picture ? Storage::url($this->profile_picture) : null,
            'created_at'      => $this->created_at->toDateTimeString(),
            'updated_at'      => $this->updated_at->toDateTimeString(),
            
            // Menambahkan roles dan permissions
            'roles'           => $this->getRoleNames(),
            'permissions'     => $this->getAllPermissions()->pluck('name'),
        ];
    }
}