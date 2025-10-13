<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest as AuthRegisterRequest;
use App\Http\Requests\Auth\LoginRequest as AuthLoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Handle user registration request.
     *
     * @param AuthRegisterRequest $request
     * @return JsonResponse
     */
    public function register(AuthRegisterRequest $request): JsonResponse
    {
        // Memulai blok try-catch untuk menangani kemungkinan error
        try {
            // Ini memastikan semua query berhasil atau semuanya digagalkan (rollback)
            $user = DB::transaction(function () use ($request) {
                // Buat user baru
                $newUser = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'nim' => $request->nim,
                    'nip' => $request->nip,
                    'floor' => $request->floor,
                    'profile_picture' => $request->profile_picture,
                    'program' => $request->program,
                ]);

                // Berikan role 'student' kepada user baru
                $newUser->assignRole('student');

                return $newUser;
            });

            // Jika transaksi berhasil, kirim response sukses
            return response()->json([
                'data' => $user,
                'meta' => [
                    'status_code' => 201,
                    'success' => true,
                    'message' => 'Registrasi berhasil!'
                ]
            ], 201);

        } catch (Exception $e) {
            
            // Catat error ke dalam log untuk debugging
            Log::error('Registrasi gagal: ' . $e->getMessage());

            // Kirim response error ke client
            return response()->json([
                'data' => [],
                'meta' => [
                    'status_code' => 500,
                    'success' => false,
                    'message' => 'Registrasi gagal, terjadi kesalahan pada server.' // Pesan error yang lebih umum untuk client
                ]
            ], 500);
        }
    }

     public function login(AuthLoginRequest $request)
     {
         try {
             // BLOK 'TRY': Kode yang berpotensi menimbulkan error diletakkan di sini.
             $token = $request->authenticate();
             $user = auth()->user();

             // Jika semua baris di atas berhasil, kembalikan response sukses.
             return response()->json([
                 'data' => [
                     'user' => [
                         'id'              => $user->id,
                         'name'            => $user->name,
                         'email'           => $user->email,
                         'floor'           => $user->floor,
                         'nim'             => $user->nim,
                         'nip'             => $user->nip,
                         'program'         => $user->program,
                         'profile_picture' => $user->profile_picture,
                         'created_at'      => $user->created_at,
                         'updated_at'      => $user->updated_at,
                         'roles'           => $user->roles->pluck('name'),
                         'permissions'     => $user->getAllPermissions()->pluck('name')
                     ],
                     'token'      => $token,
                     'token_type' => 'bearer',
                     'expires_in' => config('jwt.ttl') * 60
                 ],
                 'meta' => [
                     'status_code' => 200,
                     'success'     => true,
                     'message'     => 'Login berhasil'
                 ]
             ], 200);

         } catch (ValidationException $e) {
             // BLOK 'CATCH' 1: Khusus menangkap error validasi.
             // Error ini terjadi jika email/password salah atau rate limit terlampaui.
             // Kita lempar kembali agar Laravel menanganinya secara default (menjadi JSON 422).
             throw $e;

         } catch (\Exception $e) {
             // BLOK 'CATCH' 2: Menangkap semua error tak terduga lainnya.
             // Contoh: Masalah koneksi database, service lain mati, dll.

             // Catat error ke log agar developer bisa menyelidikinya.
             Log::error('Terjadi kesalahan saat login: ' . $e->getMessage());

             // Beri response error 500 yang ramah kepada pengguna.
             return response()->json([
                 'data' => [],
                 'meta' => [
                     'status_code' => 500,
                     'success'     => false,
                     'message'     => 'Terjadi kesalahan pada server, silakan coba lagi nanti.',
                     'error'       => 'Internal Server Error'
                 ]
             ], 500);
         }
     }

     public function profile()
     {
          try {
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
          } catch (Exception $e) {
               return response()->json([
                    'data' => [],
                    'meta' => [
                         'status_code' => 500,
                         'success' => false,
                         'message' => 'Terjadi kesalahan saat mengambil data profil',
                         'error' => $e->getMessage()
                    ]
               ], 500);
          }
     }

     public function updateProfile(Request $request)
     {
          try {
               $user = auth()->user();

               $request->validate([
                    'name' => 'sometimes|required|string|max:255',
                    'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
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
          } catch (Exception $e) {
               return response()->json([
                    'data' => [],
                    'meta' => [
                         'status_code' => 500,
                         'success' => false,
                         'message' => 'Terjadi kesalahan saat memperbarui profil',
                         'error' => $e->getMessage()
                    ]
               ], 500);
          }
     }

     public function updateAvatar(Request $request)
     {
          try {
               $user = auth()->user();

               $request->validate([
                    'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
               ]);

               if (!$request->hasFile('profile_picture')) {
                    return response()->json([
                         'data' => [],
                         'meta' => [
                              'status_code' => 400,
                              'success' => false,
                              'message' => 'Tidak ada file avatar yang diunggah'
                         ]
                    ], 400);
               }

               $file = $request->file('profile_picture');
               $filename = time() . '_' . $file->getClientOriginalName();

               $path = Storage::disk('public')->putFileAs('avatars', $file, $filename);

               if ($user->profile_picture) {
                    // jika disimpan sebagai Storage::url ("/storage/avatars/..."), ambil relative path
                    $old = str_replace('/storage/', '', $user->profile_picture);
                    if (Storage::disk('public')->exists($old)) {
                         Storage::disk('public')->delete($old);
                    }
               }

               $user->profile_picture = Storage::url($path);
               $user->save();

               return response()->json([
                    'data' => $user,
                    'meta' => [
                         'status_code' => 200,
                         'success' => true,
                         'message' => 'Avatar berhasil diperbarui'
                    ]
               ], 200);
          } catch (Exception $e) {
               return response()->json([
                    'data' => [],
                    'meta' => [
                         'status_code' => 400,
                         'success' => false,
                         'message' => 'Tidak ada file avatar yang diunggah',
                         'error' => $e->getMessage()
                    ]
               ], 400);
          }
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
          try {
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
          } catch (Exception $e) {
               return response()->json([
                    'data' => [],
                    'message' => 'Terjadi kesalahan saat mengubah password',
                    'error' => $e->getMessage()
               ]);
          }
     }

     public function logout()
     {
          try {
               auth()->logout();

               return response()->json([
                    'data' => [],
                    'meta' => [
                         'status_code' => 200,
                         'success' => true,
                         'message' => 'Logout berhasil'
                    ]
               ], 200);
          } catch (Exception $e) {
               return response()->json([
                    'data' => [],
                    'meta' => [
                         'status_code' => 500,
                         'success' => false,
                         'message' => 'Terjadi kesalahan saat logout',
                         'error' => $e->getMessage()
                    ]
               ], 500);
          }
     }
}
