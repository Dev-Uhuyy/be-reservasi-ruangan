<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\MahasiswaResource;
use App\Http\Resources\StaffResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\CreateStudentRequest as AuthCreateStudentRequest;
use App\Http\Requests\Auth\CreateStaffRequest as AuthCreateStaffRequest;
use App\Http\Requests\Auth\UpdateStudentRequest as AuthUpdateStudentRequest;
use App\Http\Requests\Auth\UpdateStaffRequest as AuthUpdateStaffRequest;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function student()
    {
        // $students = User::role('student')->get();
        // return MahasiswaResource::collection($students);
        try {
            $students = User::role('student')->paginate(10);

            // ✅ Ini akan menghasilkan JSON dengan status 200 OK
            //    dan data akan otomatis dibungkus dalam key 'data'.
            return MahasiswaResource::collection($students);
        } catch (\Exception $e) {
            // Jika terjadi error sesungguhnya (misal, koneksi database gagal)
            // baru kita kembalikan respons error 500 di sini.
            return response()->json([
                'meta' => [
                    'status_code' => 500,
                    'success'     => false,
                    'message'     => 'Terjadi kesalahan pada server, silakan coba lagi nanti.',
                    'error'       => $e->getMessage() // Lebih baik untuk development
                ]
            ], 500);
        }
    }

    public function staff()
    {
        // $students = User::role('student')->get();
        // return MahasiswaResource::collection($students);
        try {
            $staff = User::role('staff')->paginate(10);

            // ✅ Ini akan menghasilkan JSON dengan status 200 OK
            //    dan data akan otomatis dibungkus dalam key 'data'.
            return StaffResource::collection($staff);
        } catch (\Exception $e) {
            // Jika terjadi error sesungguhnya (misal, koneksi database gagal)
            // baru kita kembalikan respons error 500 di sini.
            return response()->json([
                'meta' => [
                    'status_code' => 500,
                    'success'     => false,
                    'message'     => 'Terjadi kesalahan pada server, silakan coba lagi nanti.',
                    'error'       => $e->getMessage() // Lebih baik untuk development
                ]
            ], 500);
        }
    }

    public function index()
    {
        $users = User::with('roles')->get();

        $formattedUsers = $users
            // 1. Buang user yang bukan staff atau student
            ->filter(function ($user) {
                return $user->hasRole('staff') || $user->hasRole('student');
            })
            // 2. Map user yang tersisa ke Resource yang benar
            ->map(function ($user) {
                if ($user->hasRole('staff')) {
                    // Gunakan 'new StaffResource($user)' saja, tanpa ->resolve()
                    return new StaffResource($user);
                }
                // Karena sudah difilter, sisanya pasti student
                return new MahasiswaResource($user);
            })
            // 3. (Opsional tapi direkomendasikan) Reset keys agar menjadi array JSON [ ]
            ->values();

        return response()->json([
            'data' => $formattedUsers
        ]);
    }

    public function storeStudent(AuthCreateStudentRequest $request)
    {

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'nim' => $request->nim,
            ]);

            $user->assignRole('student');

            return response()->json([
                'data' => new MahasiswaResource($user),
                'meta' => [
                    'status_code' => 201,
                    'success'     => true,
                    'message'     => 'Mahasiswa berhasil dibuat.',
                ]
            ], 201);
        } catch (\Exception $e) {
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

    public function storeStaff(AuthCreateStaffRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'floor' => $request->floor,
                'nip' => $request->nip,
            ]);

            $user->assignRole('staff');

            return response()->json([
                'data' => new StaffResource($user),
                'meta' => [
                    'status_code' => 201,
                    'success'     => true,
                    'message'     => 'Staff berhasil dibuat.',
                ]
            ], 201);
        } catch (\Exception $e) {
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

    public function destroy($id)
    {
      
        try {
            // Hapus data user dari database
            $user = User::findOrFail($id);
            $user->forceDelete();

            // Kirim respons sukses tanpa data (status 204 No Content)
            // atau dengan pesan JSON
            return response()->json([
                'meta' => [
                    'status_code' => 200,
                    'success'     => true,
                    'message'     => 'User berhasil dihapus.',
                ]
            ], 200);
        } catch (\Exception $e) {
            // Jika terjadi error saat menghapus
            return response()->json([
                'meta' => [
                    'status_code' => 500,
                    'success'     => false,
                    'message'     => 'Gagal menghapus user, terjadi kesalahan server.',
                ]
            ], 500);
        }
    }

    public function updateStudent(AuthUpdateStudentRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Data mahasiswa berhasil diperbarui',
            'data' => $user
        ]);
    }

    public function updateStaff(AuthUpdateStaffRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Data staff berhasil diperbarui',
            'data' => $user
        ]);
    }
}
