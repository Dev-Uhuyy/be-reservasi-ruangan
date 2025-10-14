<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;


class CreateStaffRequest extends FormRequest
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
            'floor' => 'required|string|max:255',
            'nip' => 'required|string|unique:users,nip', // NIM wajib untuk mahasiswa
        ];
    }
}
