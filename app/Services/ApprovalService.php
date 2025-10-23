<?php

namespace App\Services;

use App\Events\ReservationApproved;
use App\Events\ReservationRejected;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use App\Exceptions\ReservationAlreadyProcessedException;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class ApprovalService
{
    /**
     * Logika untuk menyetujui reservasi.
     *
     * @param Reservation $reservation
     * @param User $approver
     * @param UploadedFile|null $letter
     * @return Reservation
     * @throws ReservationAlreadyProcessedException
     */

    public function approveReservation(Reservation $reservation, User $approver): Reservation
    {
        $this->checkIfPending($reservation);

        // 1. Muat relasi yang dibutuhkan oleh Blade PDF
        // (Blade Anda menggunakan student, details, room, dan schedule)
        $reservation->loadMissing(['student', 'details.room', 'details.schedule']);

        // 2. Generate PDF dari Blade view
        $pdf = Pdf::loadView('pdfs.approval-letter', ['reservation' => $reservation]);

        // 3. Buat nama file yang unik dan path
        $timestamp = Carbon::now()->format('Ymd-His');
        $filename = 'surat-persetujuan-' . $reservation->id . '-' . $timestamp . '.pdf';
        $letterPath = 'letters/' . $filename; // Path relatif untuk disimpan di DB

        // 4. Simpan PDF ke disk 'public'
        Storage::disk('public')->put($letterPath, $pdf->output());

        // 5. Update reservasi dengan path PDF yang baru
        $reservation->update([
            'status' => 'approved',
            'approved_by' => $approver->id,
            'approval_letter' => $letterPath, // Simpan path dari PDF yang di-generate
            'rejection_reason' => null,
        ]);

        // 6. Dispatch event
        ReservationApproved::dispatch($reservation);

        // 7. Kembalikan model yang sudah di-refresh
        return $reservation->fresh(['student', 'approver']);
    }

    /**
     * Logika untuk menolak reservasi.
     *
     * @param Reservation $reservation
     * @param User $approver
     * @param string $reason
     * @return Reservation
     * @throws ReservationAlreadyProcessedException
     */
    public function rejectReservation(Reservation $reservation, User $approver, string $reason): Reservation
    {
        $this->checkIfPending($reservation);

        $reservation->update([
            'status' => 'rejected',
            'approved_by' => $approver->id,
            'rejection_reason' => $reason,
        ]);

        // Opsional: Kirim event penolakan
        // ReservationRejected::dispatch($reservation);

        ReservationRejected::dispatch($reservation);

        return $reservation->fresh(['student', 'approver']);
    }

    /**
     * Cek apakah status reservasi masih 'pending'.
     *
     * @param Reservation $reservation
     * @throws ReservationAlreadyProcessedException
     */
    protected function checkIfPending(Reservation $reservation): void
    {
        if ($reservation->status !== 'pending') {
            // Lemparkan exception kustom
            throw new ReservationAlreadyProcessedException('Reservation already processed.');
        }
    }
}
