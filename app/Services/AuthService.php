<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\ValidationException;

/**
 * AuthService berisi semua logika bisnis terkait autentikasi dan profil pengguna.
 */
class AuthService
{
    /**
     * Menangani logika registrasi pengguna baru (khusus mahasiswa).
     *
     * @param array $data Data valid dari request.
     * @return User
     */
    public function registerStudent(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'nim' => $data['nim'], // Menggunakan field 'nim' yang terpisah
                'program' => $data['program'],
                'password' => Hash::make($data['password']),
            ]);

            $user->assignRole('student');

            return $user;
        });
    }

    /**
     * Menangani logika login dan pembuatan token JWT menggunakan email dan password.
     *
     * @param array $credentials Kredensial (email & password) dari request.
     * @return string|null Token JWT atau null jika gagal.
     */
    public function login(array $credentials): ?string
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            return null;
        }
        
        return $token;
    }

    /**
     * Menangani logika logout dengan mendaftarkan token ke blacklist.
     *
     * @return void
     */
    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    /**
     * Menangani logika pembaruan data profil teks.
     *
     * @param User $user Model pengguna yang akan diupdate.
     * @param array $data Data baru yang sudah divalidasi.
     * @return User
     */
    public function updateProfile(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh(); 
    }

    /**
     * Menangani logika pembaruan foto profil (avatar).
     *
     * @param User $user Model pengguna yang akan diupdate.
     * @param UploadedFile $file File gambar yang diunggah.
     * @return User
     */
    public function updateAvatar(User $user, UploadedFile $file): User
    {
        // Hapus avatar lama jika ada untuk menghemat ruang
        if ($user->profile_picture) {
            // Mengambil path relatif dari URL
            $oldPath = str_replace(Storage::url(''), '', $user->profile_picture);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        // Simpan avatar baru
        $path = $file->store('avatars', 'public');

        // Update path di database
        $user->profile_picture = $path;
        $user->save();

        return $user;
    }

    /**
     * Menangani logika perubahan password.
     *
     * @param User $user Model pengguna yang akan diupdate.
     * @param array $data Data password lama dan baru.
     * @return bool
     * @throws ValidationException
     */
    public function changePassword(User $user, array $data): bool
    {
        // Validasi password saat ini
        if (!Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Password saat ini salah.'],
            ]);
        }

        // Update password baru
        $user->password = Hash::make($data['new_password']);
        $user->save();

        return true;
    }
}