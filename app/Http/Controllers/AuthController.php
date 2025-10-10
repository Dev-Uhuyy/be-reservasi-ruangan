<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest; // <-- Import RegisterRequest
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    /**
     * Handle user registration.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // Validasi sudah otomatis dijalankan oleh RegisterRequest

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password dengan bcrypt
            'nim' => $request->nim, // Akan null jika tidak ada input
            'nip' => $request->nip, // Akan null jika tidak ada input
            'floor' => $request->floor, // Akan null jika tidak ada input
            'profile_picture'=> $request->profile_picture,
            'program' => $request->program, // Akan null jika tidak ada input
        ]);

        // Assign role ke user menggunakan Spatie
        $user->assignRole($request->role);

        // Buat token (opsional, jika Anda butuh login otomatis setelah registrasi)
        // $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil!',
            'data' => $user,
            // 'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
}
