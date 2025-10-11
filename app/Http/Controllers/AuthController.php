<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest; 
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
     public function register(RegisterRequest $request): JsonResponse
     {
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

          $user->assignRole('student'); 

          if (!$user) {
                return response()->json([
                     'data' => [],
                     'meta' => [
                          'status_code' => 500,
                          'success' => false,
                          'message' => 'Registrasi gagal!'
                     ]
                ], 500);
          }

          return response()->json([
                'data' => $user,
                'meta' => [
                     'status_code' => 201,
                     'success' => true,
                     'message' => 'Registrasi berhasil!'
                ]
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
                     'data' => [],
                     'meta' => [
                          'status_code' => 401,
                          'success' => false,
                          'message' => 'Email atau password salah',
                          'error' => 'Unauthorized'
                     ]
                ], 401);
          }

          $user = auth()->user();

          return response()->json([
                'data' => [
                     'user' => [
                          'id' => $user->id,
                          'name' => $user->name,
                          'email' => $user->email,
                          'floor' => $user->floor,
                          'nim' => $user->nim,
                          'nip' => $user->nip,
                          'program' => $user->program,
                          'profile_picture' => $user->profile_picture,
                          'created_at' => $user->created_at,
                          'updated_at' => $user->updated_at,
                          'roles' => $user->roles->pluck('name'),
                          'permissions' => $user->getAllPermissions()->pluck('name')
                     ],
                     'token' => $token,
                     'token_type' => 'bearer',
                     'expires_in' => config('jwt.ttl') * 60 
                ],
                'meta' => [
                     'status_code' => 200,
                     'success' => true,
                     'message' => 'Login berhasil'
                ]
          ], 200);
     }

     public function profile()
     {
          $user = auth()->user();

          return response()->json([
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
                ],
                'meta' => [
                     'status_code' => 200,
                     'success' => true,
                     'message' => 'Data profil berhasil diambil'
                ]
          ], 200);
     }

     public function updateProfile(Request $request)
     {
          $user = auth()->user();

          $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:users,email,'. $user->id,
                'program' => 'sometimes|nullable|string|max:255',
          ]);

          $user->update($request->only(['name', 'email', 'program']));

          return response()->json([
                'data' => $user,
                'meta' => [
                     'status_code' => 200,
                     'success' => true,
                     'message' => 'Profil berhasil diperbarui'
                ]
          ], 200);
     }
     
     public function updateAvatar(Request $request)
     {
          $user = auth()->user();

          $request->validate([
                'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
          ]);

          if ($request->hasFile('profile_picture')) {
                $file = $request->file('profile_picture');
                $filename = time().'_'.$file->getClientOriginalName();
                $filePath = $file->storeAs('avatars', $filename, 'public');

                $user->profile_picture = '/storage/avatars/' . $filePath;
                $user->save();

                return response()->json([
                     'data' => $user,
                     'meta' => [
                          'status_code' => 200,
                          'success' => true,
                          'message' => 'Avatar berhasil diperbarui'
                     ]
                ], 200);
          }

          return response()->json([
                'data' => [],
                'meta' => [
                     'status_code' => 400,
                     'success' => false,
                     'message' => 'Tidak ada file avatar yang diunggah'
                ]
          ], 400);
     }

     public function refreshToken()
     {
          $newToken = JWTAuth::refresh(JWTAuth::getToken());

          return response()->json([
                'data' => [
                     'token' => $newToken,
                     'token_type' => 'bearer',
                     'expires_in' => config('jwt.ttl') * 60 
                ],
                'meta' => [
                     'status_code' => 200,
                     'success' => true,
                     'message' => 'Token berhasil diperbarui'
                ]
          ], 200);
     }

     public function changePassword(Request $request)
     {
          $user = auth()->user();

          $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6|confirmed'
          ]);

          if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                     'data' => [],
                     'meta' => [
                          'status_code' => 400,
                          'success' => false,
                          'message' => 'Password saat ini salah'
                     ]
                ], 400);
          }

          $user->password = Hash::make($request->new_password);
          $user->save();

          return response()->json([
                'data' => [],
                'meta' => [
                     'status_code' => 200,
                     'success' => true,
                     'message' => 'Password berhasil diubah'
                ]
          ], 200);
     }

     public function logout()
     {
          auth()->logout();
          
          return response()->json([
                'data' => [],
                'meta' => [
                     'status_code' => 200,
                     'success' => true,
                     'message' => 'Logout berhasil'
                ]
          ], 200);
     }
}
