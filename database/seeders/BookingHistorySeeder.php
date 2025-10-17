<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookingHistory;
use App\Models\Reservation;
use App\Models\Room;

class BookingHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data yang dibutuhkan dari seeder lain
        $approvedReservation = Reservation::where('status', 'approved')->first();
        $room1 = Room::find(1);
        $room2 = Room::find(2);

        if (!$approvedReservation || !$room1 || !$room2) {
            $this->command->info('Tidak dapat membuat data dummy. Pastikan Reservation dan Room seeder sudah dijalankan dan ada reservasi yang disetujui.');
            return;
        }

        // Contoh 1: Riwayat pemesanan yang perlu diverifikasi
        BookingHistory::create([
            'reservation_id' => $approvedReservation->id,
            'room_id' => $room1->id,
            'student_id' => $approvedReservation->student_id,
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'booking_date' => now()->subDay()->toDateString(), // Jadwal untuk kemarin
            'usage_status' => 'unused', // Status default
            'verified_by' => null,      // Belum diverifikasi
            'verified_at' => null,
        ]);

        // Contoh 2: Riwayat pemesanan lain yang perlu diverifikasi
        BookingHistory::create([
            'reservation_id' => $approvedReservation->id,
            'room_id' => $room2->id,
            'student_id' => $approvedReservation->student_id,
            'start_time' => '14:00:00',
            'end_time' => '16:00:00',
            'booking_date' => now()->subDay()->toDateString(), // Jadwal untuk kemarin
            'usage_status' => 'unused', // Status default
            'verified_by' => null,      // Belum diverifikasi
            'verified_at' => null,
        ]);
    }
}

