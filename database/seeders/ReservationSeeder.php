<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada user (student dan staff)
        $student = User::whereHas('roles', fn($q) => $q->where('name', 'student'))->first();
        $admin = User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->first();

        if (!$student || !$admin) {
            $this->command->info('Tidak dapat membuat data dummy. Harap jalankan UserSeeder terlebih dahulu.');
            return;
        }

        // Contoh 1: Reservasi yang masih menunggu persetujuan
         // 1. Reservasi yang masih pending
         Reservation::create([
            'student_id' => $student->id,
            'purpose' => 'Kegiatan Internal Himpunan',
            'request_date' => now()->subDays(5),
            'status' => 'pending',
            'approved_by' => $admin->id,
        ]);

        // 2. Reservasi yang sudah disetujui oleh Admin
        Reservation::create([
            'student_id' => $student->id,
            'purpose' => 'Rapat Panitia Wisuda',
            'request_date' => now()->subDays(3),
            'status' => 'approved',
            'approved_by' => $admin->id,
        ]);

        // 3. Reservasi yang ditolak oleh Admin 
        Reservation::create([
            'student_id' => $student->id,
            'purpose' => 'Latihan Band',
            'request_date' => now()->subDays(2),
            'status' => 'rejected',
            'rejection_reason' => 'Ruangan tidak tersedia pada jadwal tersebut.',
            'approved_by' => $admin->id,
        ]);


    }
}
