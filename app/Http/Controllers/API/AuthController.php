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
     * Endpoint untuk registrasi mahasiswaz.
     */

    /**
     * @OA\Post(
     *    path="/register",
     *    summary="Registrasi mahasiswa baru",
     *    description="Endpoint untuk registrasi mahasiswa baru ke dalam sistem.",
     *    tags={"Auth"},
     *    @OA\RequestBody(
     *      required=true,
     *      description="Data registrasi mahasiswa",
     *      @OA\JsonContent(
     *          required={"name", "email", "password"},
     *          @OA\Property(property="name", type="string", example="Sindu"),
     *          @OA\Property(property="email", type="string", format="email", example="sindu@student.dinus.ac.id"),
     *          @OA\Property(property="password", type="string", format="password", example="password123"),
     *          @OA\Property(property="nim", type="string", example="1234567890"),
     *          @OA\Property(property="program", type="string", example="Teknik Informatika")
     *        )
     *    ),
     *  @OA\Response(
     *      response=201,
     *      description="Registrasi berhasil.",
     *      @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *  ),
     *  @OA\Response(
     *     response=422,
     *    description="Validasi gagal.",
     *   @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *  ),
     *  @OA\Response(
     *      response=400,
     *      description="Permintaan tidak valid.",
     *      @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *  ),
     *  @OA\Response(
     *      response=500,
     *      description="Kesalahan server.",
     *      @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *  )
     * )
     */

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->registerStudent($request->validated());
            return $this->successResponse(new UserResource($user), 'Registrasi berhasil. Silakan login.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'data' => null,
                'meta' => [
                    'status_code' => 422,
                    'success' => false,
                    'message' => 'Gagal Validasi.',
                    'errors' => $e->errors()
                ]
            ], 422);
        } catch (\Throwable $e) {
            return $this->exceptionError($e, 'Gagal melakukan registrasi', 500);
        }
    }

    /**
     * Endpoint untuk login pengguna.
     */
    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Login pengguna (mahasiswa, staff, atau admin)",
     *     description="Endpoint untuk autentikasi pengguna dan mendapatkan token JWT.",
     *     tags={"Auth"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Kredensial login pengguna",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="sindu@student.dinus.ac.id"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Login berhasil.",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Email atau password salah.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
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
    /**
     * @OA\Get(
     *     path="/auth/me",
     *     summary="Get profil pengguna saat ini",
     *     description="Endpoint untuk mendapatkan data profil pengguna yang sedang login.",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Data profil berhasil diambil.",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function profile(): JsonResponse
    {
        return $this->successResponse(new UserResource(auth()->user()), 'Data profil berhasil diambil.');
    }

    /**
     * Endpoint untuk memperbarui data profil teks.
     */
    /**
     * @OA\Put(
     *     path="/auth/me",
     *     summary="Perbarui profil pengguna saat ini",
     *     description="Endpoint untuk memperbarui data profil pengguna yang sedang login.",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data profil yang akan diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Sindu Updated"),
     *             @OA\Property(property="email", type="string", format="email", example="sindu.updated@example.com"),
     *             @OA\Property(property="program", type="string", example="Teknik Informatika")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profil berhasil diperbarui.",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Permintaan tidak valid.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
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
    /**
     * @OA\Post(
     *     path="/auth/me/avatar",
     *     summary="Perbarui avatar pengguna saat ini",
     *     description="Endpoint untuk memperbarui foto profil (avatar) pengguna yang sedang login.",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="File gambar avatar baru",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"profile_picture"},
     *                 @OA\Property(
     *                     property="profile_picture",
     *                     type="string",
     *                     format="binary",
     *                     description="File gambar avatar"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Avatar berhasil diperbarui.",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Permintaan tidak valid.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
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
    /**
     * @OA\Put(
     *     path="/auth/reset-password",
     *     summary="Ubah password pengguna saat ini",
     *     description="Endpoint untuk mengubah password pengguna yang sedang login.",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data untuk mengubah password",
     *         @OA\JsonContent(
     *             required={"password", "new_password", "new_password_confirmation"},
     *             @OA\Property(property="password", type="string", format="password", example="oldpassword123"),
     *             @OA\Property(property="new_password", type="string", format="password", example="newpassword123"),
     *             @OA\Property(property="new_password_confirmation", type="string", format="password", example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password berhasil diubah.",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Permintaan tidak valid.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
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
    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     summary="Logout pengguna saat ini",
     *     description="Endpoint untuk logout pengguna yang sedang login.",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout berhasil.",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();
        return $this->successResponse(null, 'Logout berhasil.');
    }

    /**
     * Endpoint untuk refresh token.
     */

    /**
     * @OA\Post(
     *     path="/auth/refresh-token",
     *     summary="Refresh token JWT",
     *     description="Endpoint untuk memperbarui token JWT yang sudah kedaluwarsa.",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="Token berhasil diperbarui.",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token tidak valid atau kedaluwarsa.",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     * )
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
