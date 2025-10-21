<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Requests\Auth\UpdateAvatarRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Resources\Admin\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Endpoint untuk registrasi mahasiswa.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->registerStudent($request->validated());
            return $this->successResponse(new UserResource($user), 'Registrasi berhasil. Silakan login.');
        } catch (\Throwable $e) {
            return $this->exceptionError($e, 'Gagal melakukan registrasi', 500);
        }
    }

    /**
     * Endpoint untuk login pengguna.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->authService->login($request->validated());

        if (!$token) {
            return $this->exceptionError(new \Exception('Kredensial tidak valid'), 'Kredensial tidak valid', 401);
        }

        $user = auth()->user();

        $data = [
            'user'       => new UserResource($user),
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60
        ];

        return $this->successResponse($data, 'Login berhasil.');
    }

    /**
     * Endpoint untuk mendapatkan data profil pengguna yang sedang login.
     */
    public function profile(): JsonResponse
    {
        return $this->successResponse(new UserResource(auth()->user()), 'Data profil berhasil diambil.');
    }

    /**
     * Endpoint untuk memperbarui data profil teks.
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->updateProfile(auth()->user(), $request->validated());
            return $this->successResponse(new UserResource($user), 'Profil berhasil diperbarui.');
        } catch (\Throwable $e) {
            return $this->exceptionError($e, 'Gagal memperbarui profil', 500);
        }
    }

    /**
     * Endpoint untuk memperbarui foto profil (avatar).
     */
    public function updateAvatar(UpdateAvatarRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->updateAvatar(auth()->user(), $request->file('profile_picture'));
            return $this->successResponse(new UserResource($user), 'Avatar berhasil diperbarui.');
        } catch (\Throwable $e) {
            return $this->exceptionError($e, 'Gagal mengunggah avatar', 500);
        }
    }

    /**
     * Endpoint untuk mengubah password.
     */

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        try {
            // Panggil service untuk mengubah password
            $this->authService->changePassword(auth()->user(), $request->validated());

            // Jika berhasil, kembalikan respons sukses
            return $this->successResponse(null, 'Password berhasil diubah.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangani ValidationException secara eksplisit
            return response()->json([
                'data' => null,
                'meta' => [
                    'status_code' => 422,
                    'success' => false,
                    'message' => 'Password saat ini salah.',
                    'errors' => $e->errors()
                ]
            ], 422);
        } catch (\Throwable $e) {
            // Tangani error lain dengan exceptionError
            return $this->exceptionError($e, 'Gagal mengubah password', 500);
        }
    }

    /**
     * Endpoint untuk logout.
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();
        return $this->successResponse(null, 'Logout berhasil.');
    }

    /**
     * Endpoint untuk refresh token.
     */
    public function refreshToken(): JsonResponse
    {
        $data = [
            'token' => auth()->refresh(),
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60
        ];
        return $this->successResponse($data, 'Token berhasil diperbarui.');
    }
}
