<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // ubah sesuai kebutuhan (misal hanya admin yang bisa)
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id'); // ambil ID user dari route

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => 'nullable|string|min:8|confirmed',

            // khusus mahasiswa
            'program' => 'required|string|max:255',
            // opsional
            'profile_picture' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.min' => 'Password minimal 8 karakter.',
            'program.required' => 'Program studi wajib diisi.',
        ];
    }
}
