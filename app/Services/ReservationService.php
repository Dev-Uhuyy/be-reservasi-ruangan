<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class ReservationService
{
    public function createStudentReservation(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {

            $scheduleIds = $data['schedules'];

            $schedules = Schedule::whereIn('id', $scheduleIds)
                ->lockForUpdate()
                ->get();

            foreach ($schedules as $schedule) {
                if ($schedule->status !== 'available') {
                    throw new Exception("Jadwal sudah tidak tersedia.");
                }
            }

            $reservation = Reservation::create([
                'student_id'   => $user->id,
                'purpose'      => $data['purpose'],
                'status'       => 'pending',
                'request_date' => now(), // <-- DITAMBAHKAN (karena wajib diisi)
            ]);

            // Panggilan ini sekarang akan BERHASIL karena kita sudah
            // mengubah nama relasi di Model Reservation
            foreach ($schedules as $schedule) {
                $reservation->reservationDetails()->create([
                    'schedule_id' => $schedule->id,
                    'room_id'     => $schedule->room_id, // <-- TAMBAHKAN BARIS INI
                ]);

                $schedule->status = 'booked';
                $schedule->save();
            }

            return $reservation;
        });
    }
}
