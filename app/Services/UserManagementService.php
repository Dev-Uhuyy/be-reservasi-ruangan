<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Berisi semua logika bisnis untuk manajemen pengguna oleh Admin.
 */
class UserManagementService
{
    /**
     * Mengambil daftar pengguna dengan filter dan paginasi.
     *
     * @param array $filters Filter dari request (misal: 'role').
     * @return LengthAwarePaginator
     */
    public function getUsers(array $filters): LengthAwarePaginator
    {
        $query = User::query()->with('roles'); // Eager load relasi roles untuk efisiensi

        // Menerapkan filter berdasarkan peran (role) jika ada
        if (!empty($filters['role'])) {
            $query->whereHas('roles', function ($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        // Mengembalikan hasil dengan paginasi
        return $query->paginate(5);
    }

    /**
     * Membuat pengguna baru (Staff atau Mahasiswa) oleh Admin.
     *
     * @param array $data Data pengguna yang sudah divalidasi.
     * @return User
     */
    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                // Mengisi NIP atau NIM berdasarkan peran
                'nip' => $data['role'] === 'staff' ? $data['nip'] : null,
                'nim' => $data['role'] === 'student' ? $data['nim'] : null,
                // Mengisi field kondisional
                'floor' => $data['role'] === 'staff' ? $data['floor'] : null,
                'program' => $data['role'] === 'student' ? $data['program'] : null,
            ]);

            $user->assignRole($data['role']);

            return $user;
        });
    }

    /**
     * Memperbarui data pengguna oleh Admin.
     *
     * @param User $user Model pengguna yang akan diupdate.
     * @param array $data Data baru yang sudah divalidasi.
     * @return User
     */
    public function updateUser(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh(); // Mengembalikan model yang sudah di-refresh
    }

    /**
     * Menghapus pengguna.
     *
     * @param User $user Model pengguna yang akan dihapus.
     * @return void
     */
    public function deleteUser(User $user): void
    {
        $user->delete();
    }

    public function getAllStaff(): LengthAwarePaginator
    {
        // Menggunakan scope 'role' dari Spatie/laravel-permission
        // untuk memfilter user berdasarkan peran dan melakukan paginasi.
        return User::role('staff')->paginate(3);
    }

    public function getAllStudents(): LengthAwarePaginator
    {
        return User::role('student')->paginate(2);
    }

    // public function showStaff(int $userId)
    // {
    //     // Temukan user atau gagal (akan melempar ModelNotFoundException)
    //     $user = User::findOrFail($userId);

    //     // Logika bisnis inti: periksa peran
    //     if (!$user->hasRole('staff')) {
    //         // Lemparkan exception jika kondisi tidak terpenuhi
    //         throw new Exception('User yang dipilih bukan staff.', 403); // 403 Forbidden lebih cocok
    //     }

    //     // Kembalikan data jika valid
    //     return $user;
    // }

    // public function showStudent(int $userId)
    // {
    //     $user = User::findOrFail($userId);

    //     // Logika bisnis inti: periksa peran
    //     if (!$user->hasRole('student')) {
    //         // Lemparkan exception jika kondisi tidak terpenuhi
    //         throw new Exception('User yang dipilih bukan student.', 403);
    //     }

    //     return $user;
    // }
}
