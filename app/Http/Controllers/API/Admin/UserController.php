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
            return $this->successResponse(
                new UserResource($user), 
                'Pengguna berhasil dibuat', 
                201
            );
        } catch (\Throwable $e) {
            return $this->exceptionError($e, 'Gagal membuat pengguna');
        }
    }

    /**
     * Menampilkan detail satu pengguna.
     */
    public function show(User $user)
    {
        try {
            // Gunakan template dari Controller
            return $this->successResponse(
                new UserResource($user), 
                'Detail pengguna berhasil diambil'
            );
        } catch (\Throwable $e) {
            return $this->exceptionError($e, 'Gagal mengambil detail pengguna');
        }
    }

    /**
     * Memperbarui data pengguna.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $updatedUser = $this->userService->updateUser($user, $request->validated());
            // Gunakan template dari Controller
            return $this->successResponse(
                new UserResource($updatedUser), 
                'Pengguna berhasil diperbarui'
            );
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
            // Gunakan template dari Controller
            return $this->successResponse(
                null, 
                'Pengguna berhasil dihapus'
            );
        } catch (\Throwable $e) {
            return $this->exceptionError($e, 'Gagal menghapus pengguna');
        }
    }

    /**
     * Menampilkan daftar semua staff.
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
            return $this->successResponse(
                UserResource::collection($staff),
                'Daftar staff berhasil diambil'
            );
        } catch (Exception $e) {
            return $this->exceptionError($e, 'Gagal mengambil data staff', 500);
        }
    }

    /**
     * Menampilkan daftar semua student.
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
            return $this->successResponse(
                UserResource::collection($students),
                'Daftar student berhasil diambil'
            );
        } catch (Exception $e) {
            return $this->exceptionError($e, 'Gagal mengambil data student', 500);
        }
    }
}