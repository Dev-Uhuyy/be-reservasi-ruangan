<?php

namespace App\Services;

use App\Events\ReservationApproved;
use App\Models\Reservation;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ApprovalService
{


    public function approve(Reservation $reservation, User $admin, ?UploadedFile $uploadedLetter): Reservation
    {
        if ($reservation->status !== 'pending') {
            throw new \Exception('Hanya reservasi dengan status pending yang bisa diproses.', 409);
        }

        $approvedReservation = DB::transaction(function () use ($reservation, $admin, $uploadedLetter) {
            $letterPath = null;

            // Hanya handle file upload manual (jika ada)
            if ($uploadedLetter && $uploadedLetter->isValid()) {
                $letterPath = $uploadedLetter->store('letters', 'public');
                Log::info('Approval letter diunggah manual: ' . $letterPath);
            }

            // Update reservasi (tanpa generate PDF otomatis)
            $reservation->update([
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approval_letter' => $letterPath, // null jika tidak ada upload manual
            ]);

            // Update status jadwal terkait menjadi 'booked'
            foreach ($reservation->details as $detail) {
                if ($detail->schedule) {
                    $detail->schedule->update(['status' => 'booked']);
                }
            }

            // Buat histori booking
            $this->createBookingHistory($reservation);

            return $reservation;
        });

        // Dispatch event setelah transaksi berhasil
        ReservationApproved::dispatch($approvedReservation);
        Log::info('Event ReservationApproved di-dispatch untuk ID: ' . $approvedReservation->id);

        return $approvedReservation;
    }

    /**
     * Membuat entri di booking_histories untuk setiap detail reservasi.
     */
    protected function createBookingHistory(Reservation $reservation)
    {
        foreach ($reservation->details as $detail) {
            // Menggunakan relasi hasMany dari model Reservation
            $reservation->bookingHistories()->updateOrCreate(
                [
                    // Kunci unik untuk mencegah duplikasi
                    'reservation_id' => $reservation->id,
                    'schedule_id' => $detail->schedule_id,
                ],
                [
                    // Data yang akan dibuat atau diupdate
                    'room_id' => $detail->room_id,
                    'student_id' => $reservation->student_id,
                    'booking_date' => $detail->schedule->date,
                    'start_time' => $detail->schedule->start_time,
                    'end_time' => $detail->schedule->end_time,
                    'usage_status' => 'need_verification',
                ]
            );
        }
    }
}
