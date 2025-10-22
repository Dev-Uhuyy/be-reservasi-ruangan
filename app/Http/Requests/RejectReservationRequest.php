<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RejectReservationRequest extends FormRequest
{
    /**
     * Tentukan apakah user berhak membuat request ini.
     */
    public function authorize(): bool
    {
        // Menggunakan permission yang sama dengan approve
        return Auth::user()->hasPermissionTo('approve reservations');
    }

    /**
     * Dapatkan aturan validasi.
     */
    public function rules(): array
    {
        return [
            'rejection_reason' => 'required|string|max:255',
        ];
    }
}
