<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\MahasiswaResource;
use App\Http\Resources\StaffResource;
use App\Models\User;
use Illuminate\Http\Request;

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

    // public function index()
    // {
    //     $users = User::all();
    //     return response()->json([
    //         'data' => $users->map(function ($user) {
    //             if ($user->hasRole('staff')) {
    //                 return (new StaffResource($user))->resolve();
    //             } else  {
    //                 return (new MahasiswaResource($user))->resolve();
    //             } 
    //         })
    //     ]);
    // }

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
}
