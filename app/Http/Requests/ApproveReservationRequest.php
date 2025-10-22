<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ApproveReservationRequest extends FormRequest
{
    /**
     * Tentukan apakah user berhak membuat request ini.
     * Ini menggantikan cek permission di controller.
     */
    public function authorize(): bool
    {
        return Auth::user()->hasPermissionTo('approve reservations');
    }

    /**
     * Dapatkan aturan validasi.
     * Ini menggantikan $request->validate() di controller.
     */
    public function rules(): array
    {
        return [
            // 'approval_letter' => 'nullable|file|mimes:pdf|max:2048', // <-- HAPUS BARIS INI
        ];
    }
}
