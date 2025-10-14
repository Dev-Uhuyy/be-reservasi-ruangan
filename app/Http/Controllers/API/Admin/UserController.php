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

    public function showStaff(User $user)
    {
        if (!$user->hasRole('staff')) {
            return $this->exceptionError('User bukan staff', 400);
        }

        return $this->successResponse(new UserResource($user), 'Detail pengguna berhasil diambil');
    }

    public function showStudent(User $user){
        if($user->hasRole('student')){
            return $this->exceptionError('User bukan student', 400);
        }

        return $this->successResponse(new UserResource($user), 'Detail pengguna berhasil diambil');
    }

}