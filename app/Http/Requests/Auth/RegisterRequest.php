<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authorize(): bool
    {
        $this->ensureIsNotRateLimited();

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:100',
            'email'    => 'required|string|email|max:100|unique:users,email',
            'password' => ['required', Password::min(8)],
            'nim'      => 'required|string|max:20|unique:users,nim',
            'program'  => 'required|string',
        ];
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        // Cek apakah sudah terlalu banyak percobaan
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            // Jika belum, hitung percobaan ini
            RateLimiter::hit($this->throttleKey());
            return;
        }

        // Jika sudah, lempar exception dengan sisa waktu tunggu
        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey(): string
    {
        // Kunci unik berdasarkan IP address request
        return 'register|' . $this->ip();
    }

    /**
     * Clear the rate limiter attempts for this request.
     */
    public function clearRateLimiter(): void
    {
        RateLimiter::clear($this->throttleKey());
    }
}
