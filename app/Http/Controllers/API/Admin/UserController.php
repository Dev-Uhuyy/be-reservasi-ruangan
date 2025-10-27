<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Resources\Admin\UserCollection;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use App\Services\UserManagementService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserManagementService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Menampilkan daftar pengguna dengan paginasi dan filter.
     */

    /**
     *  @OA\Get(
     *  path="/admin/users",
     *  summary="Melihat User yang ada",
     *  description="Endpoint untuk melihat data user",
     *  tags={"Admin - Manajemen User"},
     *  security={{"bearerAuth":{}}},
     *  @OA\Response(
     *  response=200,
     *      description="Data Berhasil di tampilkan.",
     *      @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     * ),
     * @OA\Response(
     *  response=500,
     *      description="Kesalahan Server.",
     *      @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     * ),
     *
     * )
     */
    public function index(Request $request)
    {
        try {
            $users = $this->userService->getUsers($request->all());
            return new UserCollection($users);
        } catch (\Throwable $e) {
            return $this->exceptionError($e, 'Gagal mengambil data pengguna');
        }
    }

    /**
     * Menyimpan pengguna baru.
     */
    /**
     * @OA\Post(
     *     path="/admin/users",
     *     summary="Menambahkan pengguna baru (staff atau student)",
     *     description="Endpoint untuk membuat pengguna baru oleh admin.",
     *     tags={"Admin - Manajemen User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "role"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@student.dinus.ac.id"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", example="password123"),
     *             @OA\Property(property="role", type="string", enum={"staff","student"}, example="staff"),
     *             @OA\Property(property="nip", type="string", example="STF12345", description="Wajib untuk staff"),
     *             @OA\Property(property="floor", type="string", example="Lantai 3", description="Wajib untuk staff"),
     *             @OA\Property(property="nim", type="string", example="A11202113501", description="Wajib untuk student"),
     *             @OA\Property(property="program", type="string", example="Informatika", description="Wajib untuk student")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pengguna berhasil dibuat",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validasi gagal",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server saat membuat pengguna",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $user = $this->userService->createUser($request->validated());
            return $this->successResponse(new UserResource($user), 'Pengguna berhasil dibuat', 201);
        } catch (\Throwable $e) {
            return $this->exceptionError($e, 'Gagal membuat pengguna');
        }
    }

    /**
     * Menampilkan detail satu pengguna.
     */
    /**
     * @OA\Get(
     *     path="/admin/users/{user}",
     *     summary="Melihat detail pengguna",
     *     description="Menampilkan detail satu pengguna berdasarkan ID.",
     *     tags={"Admin - Manajemen User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID pengguna",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail pengguna berhasil diambil",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pengguna tidak ditemukan",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function show(User $user)
    {
        try {
            // Gunakan template dari Controller
            return $this->successResponse(new UserResource($user), 'Detail pengguna berhasil diambil');
        } catch (\Throwable $e) {
            return $this->exceptionError($e, 'Gagal mengambil detail pengguna');
        }
    }

    /**
     * Memperbarui data pengguna.
     */
    /**
     * @OA\Put(
     *     path="/admin/users/{user}",
     *     summary="Memperbarui data pengguna",
     *     description="Endpoint untuk memperbarui data pengguna (staff atau student) oleh admin.",
     *     tags={"Admin - Manajemen User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID pengguna yang akan diperbarui",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="John Doe Updated"),
     *             @OA\Property(property="email", type="string", example="john.updated@student.dinus.ac.id"),
     *             @OA\Property(property="password", type="string", example="newpassword123"),
     *             @OA\Property(property="password_confirmation", type="string", example="newpassword123"),
     *             @OA\Property(property="nip", type="string", example="STF67890", description="Untuk staff"),
     *             @OA\Property(property="floor", type="string", example="Lantai 4"),
     *             @OA\Property(property="nim", type="string", example="A11202113502", description="Untuk student"),
     *             @OA\Property(property="program", type="string", example="Sistem Informasi")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pengguna berhasil diperbarui",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validasi gagal",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pengguna tidak ditemukan",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server saat memperbarui pengguna",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $updatedUser = $this->userService->updateUser($user, $request->validated());
            // Gunakan template dari Controller
            return $this->successResponse(new UserResource($updatedUser), 'Pengguna berhasil diperbarui');
        } catch (\Throwable $e) {
            return $this->exceptionError($e, 'Gagal memperbarui pengguna');
        }
    }

    /**
     * Menghapus pengguna.
     */
    /**
     * @OA\Delete(
     *     path="/admin/users/{user}",
     *     summary="Menghapus pengguna",
     *     description="Endpoint untuk menghapus pengguna oleh admin.",
     *     tags={"Admin - Manajemen User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID pengguna yang akan dihapus",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pengguna berhasil dihapus",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pengguna tidak ditemukan",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server saat menghapus pengguna",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function destroy(User $user)
    {
        try {
            $this->userService->deleteUser($user);
            // Gunakan template dari Controller
            return $this->successResponse(null, 'Pengguna berhasil dihapus');
        } catch (\Throwable $e) {
            return $this->exceptionError($e, 'Gagal menghapus pengguna');
        }
    }

    /**
     * Menampilkan daftar semua staff.
     */
    /**
     * @OA\Get(
     *     path="/admin/staff",
     *     summary="Melihat daftar semua staff",
     *     description="Endpoint untuk menampilkan semua pengguna dengan role staff.",
     *     tags={"Admin - Manajemen User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar staff berhasil diambil",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server saat mengambil data staff",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function indexStaff()
    {
        try {
            $staff = $this->userService->getAllStaff();

            // Jika data sudah dipaginasi, gunakan Collection
            if (method_exists($staff, 'items')) {
                return new UserCollection($staff);
            }

            // Jika data collection biasa, gunakan template dari Controller
            return $this->successResponse(UserResource::collection($staff), 'Daftar staff berhasil diambil');
        } catch (Exception $e) {
            return $this->exceptionError($e, 'Gagal mengambil data staff', 500);
        }
    }

    /**
     * Menampilkan daftar semua student.
     */
    /**
     * @OA\Get(
     *     path="/admin/students",
     *     summary="Melihat daftar semua mahasiswa",
     *     description="Endpoint untuk menampilkan semua pengguna dengan role student.",
     *     tags={"Admin - Manajemen User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar mahasiswa berhasil diambil",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Kesalahan server saat mengambil data mahasiswa",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */

    public function indexStudent()
    {
        try {
            $students = $this->userService->getAllStudents();

            // Jika data sudah dipaginasi, gunakan Collection
            if (method_exists($students, 'items')) {
                return new UserCollection($students);
            }

            // Jika data collection biasa, gunakan template dari Controller
            return $this->successResponse(UserResource::collection($students), 'Daftar student berhasil diambil');
        } catch (Exception $e) {
            return $this->exceptionError($e, 'Gagal mengambil data student', 500);
        }
    }
}
