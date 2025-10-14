<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Otorisasi ditangani oleh middleware di level route.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // Mengambil user yang sedang di-update dari route model binding
        $user = $this->route('user');

        $rules = [
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                // Aturan 'unique' harus mengabaikan ID user yang sedang diedit.
                Rule::unique('users')->ignore($user->id),
            ],
            // Password bersifat opsional saat update. Hanya validasi jika diisi.
            'password' => ['sometimes', 'nullable', 'confirmed', Password::min(8)],
        ];

        // Aturan kondisional berdasarkan peran dari user yang sedang diedit.
        // Peran tidak bisa diubah saat update untuk menjaga integritas data.
        if ($user->hasRole('staff')) {
            $rules['nip'] = [
                'sometimes',
                'required',
                'string',
                'max:20',
                Rule::unique('users')->ignore($user->id),
            ];
            $rules['floor'] = 'sometimes|required|string|max:50';
        }

        if ($user->hasRole('student')) {
            $rules['nim'] = [
                'sometimes',
                'required',
                'string',
                'max:20',
                Rule::unique('users')->ignore($user->id),
            ];
            $rules['program'] = 'sometimes|required|string|max:100';
        }

        return $rules;
    }
}
