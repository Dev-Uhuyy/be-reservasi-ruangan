<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest; 
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
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

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        $credentials = $request->only('email', 'password');

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah',
                'error' => 'Unauthorized'
            ], 401);
        }

        $user = auth()->user();

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'floor' => $user->floor,
                    'nim_nip' => $user->nim_nip,
                    'program' => $user->program,
                    'profile_picture' => $user->profile_picture,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'roles' => $user->roles->pluck('name'),
                    'permissions' => $user->getAllPermissions()->pluck('name')
                ],
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl') * 60 // dalam detik
            ]
        ], 200);
    }

    public function profile()
    {
        $user = auth()->user();
        
        return response()->json([
            'success' => true,
            'message' => 'Data profil berhasil diambil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'floor' => $user->floor,
                    'nim_nip' => $user->nim_nip,
                    'program' => $user->program,
                    'profile_picture' => $user->profile_picture,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'roles' => $user->roles->pluck('name'),
                    'permissions' => $user->getAllPermissions()->pluck('name')
                ]
            ]
        ], 200);
    }

    public function logout()
    {
        auth()->logout();
        
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ], 200);
    }
}
