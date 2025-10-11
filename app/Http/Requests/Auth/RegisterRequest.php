<?php

namespace App\Http\Requests\Auth;

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
            // 'role' => 'required|string|in:admin,staff,student',
        ];

        $role = $this->input('role');

        

        // Jika rolenya adalah "student", tambahkan aturan ini
        if ($role === 'student') {
            $rules['nim'] = 'required|string|max:20|unique:users,nim';
            $rules['program'] = 'required|string';
        }
        return $rules;
    }
}
