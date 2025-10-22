<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookingHistory;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookingHistorySeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        BookingHistory::truncate();
        Schema::enableForeignKeyConstraints();

        try {
            // Gunakan firstOrFail() untuk memastikan data ditemukan
            $approvedReservation = Reservation::where('status', 'approved')->firstOrFail();
            $rejectedReservation = Reservation::where('status', 'rejected')->firstOrFail();
            $pendingReservation = Reservation::where('status', 'pending')->firstOrFail();

            $staff = User::whereHas('roles', fn ($q) => $q->where('name', 'staff'))->firstOrFail();

            // Ambil dua ruangan secara acak yang dijamin ada, jangan gunakan find(1) atau find(2)
            $rooms = Room::inRandomOrder()->take(2)->get();
            if ($rooms->count() < 2) {
                throw new \Exception("Tidak cukup data ruangan untuk membuat seeder.");
            }
            $room1 = $rooms[0];
            $room2 = $rooms[1];

            // 1. Riwayat yang sudah selesai dan diverifikasi 'used'
            BookingHistory::create([
                'reservation_id' => $approvedReservation->id,
                'room_id' => $room1->id,
                'student_id' => $approvedReservation->student_id,
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'booking_date' => now()->subDays(3)->toDateString(),
                'usage_status' => 'used',
                'verified_by' => $staff->id,
                'verified_at' => now()->subDays(2),
            ]);

            BookingHistory::create([
                'reservation_id' => $approvedReservation->id,
                'room_id' => $room1->id,
                'student_id' => $approvedReservation->student_id,
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'booking_date' => now()->subDays(3)->toDateString(),
                'usage_status' => 'unused',
                'verified_by' => $staff->id,
                'verified_at' => now()->subDays(2),
            ]);

            BookingHistory::create([
                'reservation_id' => $approvedReservation->id,
                'room_id' => $room1->id,
                'student_id' => $approvedReservation->student_id,
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
                'booking_date' => now()->subDays(3)->toDateString(),
                'usage_status' => 'need_verification',
                'verified_by' => $staff->id,
                'verified_at' => now()->subDays(2),
            ]);



            // 2. Riwayat yang masih perlu diverifikasi oleh staff
            BookingHistory::create([
                'reservation_id' => $pendingReservation->id,
                'room_id' => $room2->id,
                'student_id' => $pendingReservation->student_id,
                'start_time' => '13:00:00',
                'end_time' => '15:00:00',
                'booking_date' => now()->subDay()->toDateString(),
                'usage_status' => 'need_verification',
            ]);

            // 3. Riwayat untuk reservasi yang ditolak
            BookingHistory::create([
                'reservation_id' => $rejectedReservation->id,
                'room_id' => $room1->id,
                'student_id' => $rejectedReservation->student_id,
                'start_time' => '08:00:00',
                'end_time' => '10:00:00',
                'booking_date' => $rejectedReservation->created_at->toDateString(),
            ]);

        } catch (ModelNotFoundException | \Exception $e) {
            $this->command->error('Gagal membuat data BookingHistory: ' . $e->getMessage());
        }
    }
}
