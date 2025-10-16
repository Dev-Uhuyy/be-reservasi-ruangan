<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key constraint sementara untuk truncate
        Schema::disableForeignKeyConstraints();
        DB::table('reservations')->truncate();
        Schema::enableForeignKeyConstraints();

        // Ambil ID user berdasarkan role dan email dari UserSeeder
        $admin = User::where('email', 'admin@reservasi.com')->first();
        $mahasiswa1 = User::where('email', 'rizki.muhammad@student.university.ac.id')->first();
        $mahasiswa2 = User::where('email', 'sari.dewi@student.university.ac.id')->first();
        $mahasiswa3 = User::where('email', 'ahmad.fauzi@student.university.ac.id')->first();
        $testUser = User::where('email', 'test@example.com')->first();

        if (!$admin || !$mahasiswa1 || !$mahasiswa2 || !$mahasiswa3 || !$testUser) {
            // Jika user belum ada, skip atau throw error (asumsi UserSeeder dijalankan dulu)
            return;
        }

        // Array students untuk kemudahan random selection
        $students = [$mahasiswa1->id, $mahasiswa2->id, $mahasiswa3->id, $testUser->id];
        $statuses = ['pending', 'approved', 'rejected'];
        $purposes = [
            'Rapat Kelompok Studi',
            'Seminar Internal',
            'Presentasi Proposal',
            'Workshop Pengembangan',
            'Diskusi Kelompok',
            'Ujian Tengah Semester',
            'Rapat Rutin Himpunan',
            'Pelatihan Software',
            'Review Proyek Akhir',
            'Brainstorming Ide',
            'Simulasi Praktikum',
            'Konsultasi Dosen',
            'Event Organisasi Mahasiswa',
            'Latihan Presentasi',
            'Analisis Data Kelompok'
        ];

        // Buat tepat 15 data dummy
        for ($i = 0; $i < 15; $i++) {
            $studentId = $students[array_rand($students)];
            $status = $statuses[array_rand($statuses)];
            $approvedBy = ($status === 'pending') ? null : $admin->id;
            $rejectionReason = ($status === 'rejected') ? 'Alasan penolakan dummy: jadwal bentrok atau dokumen tidak lengkap.' : null;
            $approvalLetter = ($status === 'approved') ? 'letters/dummy_approval_' . ($i + 1) . '.pdf' : null;

            Reservation::create([
                'student_id' => $studentId,
                'purpose' => $purposes[$i % count($purposes)] . ' #' . ($i + 1),
                'request_date' => now()->addDays(rand(-30, 30))->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                'status' => $status,
                'rejection_reason' => $rejectionReason,
                'approval_letter' => $approvalLetter,
                'approved_by' => $approvedBy,
            ]);
        }
    }
}
