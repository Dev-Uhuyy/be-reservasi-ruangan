<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;


class CreateStudentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nim' => 'required|string|unique:users,nim', // NIM wajib untuk mahasiswa
        ];
    }
}
