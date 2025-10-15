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
    public function index(Request $request)
    {
        try {
            $users = $this->userService->getUsers($request->all());
            // Gunakan Collection untuk respons daftar
            return new UserCollection($users);
        } catch (\Throwable $e) {
            return $this->exceptionError($e, 'Gagal mengambil data pengguna');
        }
    }

    /**
     * Menyimpan pengguna baru.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $user = $this->userService->createUser($request->validated());
            // Gunakan Resource untuk respons tunggal
            return $this->successResponse(new UserResource($user), 'Pengguna berhasil dibuat', 201);
        } catch (\Throwable $e) {
            return $this->exceptionError($e, 'Gagal membuat pengguna');
        }
    }

    /**
     * Menampilkan detail satu pengguna.
     */
    public function show(User $user)
    {
        return $this->successResponse(new UserResource($user), 'Detail pengguna berhasil diambil');
    }

    /**
     * Memperbarui data pengguna.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $updatedUser = $this->userService->updateUser($user, $request->validated());
            return $this->successResponse(new UserResource($updatedUser), 'Pengguna berhasil diperbarui');
        } catch (\Throwable $e) {
            return $this->exceptionError($e, 'Gagal memperbarui pengguna');
        }
    }

    /**
     * Menghapus pengguna.
     */
    public function destroy(User $user)
    {
        try {
            $this->userService->deleteUser($user);
            return $this->successResponse(null, 'Pengguna berhasil dihapus');
        } catch (\Throwable $e) {
            return $this->exceptionError($e, 'Gagal menghapus pengguna');
        }
    }
    

    public function indexStaff()
    {
        try {
            // Panggil service untuk mendapatkan daftar staff yang sudah dipaginasi
            $staff = $this->userService->getAllStaff();

            // Gunakan UserResource::collection untuk mengubah koleksi data
            return $this->successResponse(
                UserResource::collection($staff),
                'Daftar staff berhasil diambil'
            );
        } catch (Exception $e) {
            return $this->exceptionError('Gagal mengambil data staff: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Menampilkan daftar semua student.
     */
    public function indexStudent()
    {
        try {
            $students = $this->userService->getAllStudents();

            return $this->successResponse(
                UserResource::collection($students),
                'Daftar student berhasil diambil'
            );
        } catch (Exception $e) {
            return $this->exceptionError('Gagal mengambil data student: ' . $e->getMessage(), 500);
        }
    }
}
