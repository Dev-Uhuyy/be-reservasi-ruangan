<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'data' => null,
            'meta' => [
                'status_code' => 400,
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ]
        ], 422));
    }
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['required', Rule::in(['staff', 'student'])],
        ];

        // Aturan kondisional berdasarkan peran
        if ($this->input('role') === 'staff') {
            $rules['nip'] = 'required|string|max:20|unique:users,nip';
            $rules['floor'] = 'required|string|max:50';
        }

        if ($this->input('role') === 'student') {
            $rules['nim'] = 'required|string|max:20|unique:users,nim';
            $rules['program'] = 'required|string|max:100';
        }

        return $rules;
    }
}
