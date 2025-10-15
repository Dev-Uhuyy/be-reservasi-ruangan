<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RoomRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mendapatkan aturan validasi yang berlaku untuk permintaan.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Mendapatkan ID ruangan dari parameter route, jika ada (untuk kasus update).
        // `this->route('room')` akan mengambil model Room yang di-bind di route.
        $roomId = $this->route('room') ? $this->route('room')->id : null;

        return [
            // Aturan 'unique' akan mengabaikan ID ruangan saat ini jika sedang dalam mode update,
            // sehingga Anda bisa menyimpan tanpa error "room_name has already been taken".
            'room_name' => 'required|string|max:100|unique:rooms,room_name,' . $roomId,
            'floor' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
            'facilities' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ];
    }

    /**
     * Menangani respons validasi yang gagal.
     * Ini akan mengubah format error default Laravel menjadi format JSON kustom Anda.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'data' => ['errors' => $validator->errors()],
            'meta' => [
                'status_code' => 422,
                'success' => false,
                'message' => 'Validasi gagal! Data yang diberikan tidak valid.'
            ]
        ], 422));
    }
}

