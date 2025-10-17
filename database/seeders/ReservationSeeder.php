<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada user (student dan staff)
        $student = User::whereHas('roles', fn ($q) => $q->where('name', 'student'))->first();
        $staff = User::whereHas('roles', fn ($q) => $q->where('name', 'staff'))->first();

        if (!$student || !$staff) {
            $this->command->info('Tidak dapat membuat data dummy. Harap jalankan UserSeeder terlebih dahulu.');
            return;
        }

        // Contoh 1: Reservasi yang masih menunggu persetujuan
        Reservation::create([
            'student_id' => $student->id,
            'purpose' => 'Kegiatan UKM Fotografi',
            'request_date' => now()->subDays(5),
            'status' => 'pending',
        ]);

        // Contoh 2: Reservasi yang sudah disetujui
        Reservation::create([
            'student_id' => $student->id,
            'purpose' => 'Rapat Internal BEM',
            'request_date' => now()->subDays(2),
            'status' => 'approved',
            'approved_by' => $staff->id,
        ]);

        // Contoh 3: Reservasi lain yang masih pending
        Reservation::create([
            'student_id' => $student->id,
            'purpose' => 'Diskusi Proyek Kelompok',
            'request_date' => now()->subDay(),
            'status' => 'pending',
        ]);
    }
}
