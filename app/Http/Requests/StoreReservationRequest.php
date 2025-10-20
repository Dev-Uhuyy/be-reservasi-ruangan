<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Kita asumsikan user yang sudah login (via middleware auth) boleh membuat reservasi
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'purpose' => 'required|string|max:255',
            'schedules' => 'required|array|min:1',
            'schedules.*' => 'required|integer|exists:schedules,id',
        ];
    }
}
