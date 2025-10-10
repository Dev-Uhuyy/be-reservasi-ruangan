<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Aturan dasar yang berlaku untuk semua role
        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => ['required', Password::min(8)],
            'role' => 'required|string|in:Admin,Staff,Student',
        ];

        $role = $this->input('role');

        // Jika rolenya adalah "Student", tambahkan aturan ini
        if ($role === 'Student') {
            $rules['nim'] = 'required|string|max:20|unique:users,nim';
            $rules['program'] = 'required|string';
        }

        // Jika rolenya adalah "Staff", tambahkan aturan ini
        if ($role === 'Staff') {
            $rules['nip'] = 'required|string|max:20|unique:users,nip';
            $rules['floor'] = 'required|string|max:50';
        }

        // Admin tidak punya field tambahan yang wajib

        return $rules;
    }
}
