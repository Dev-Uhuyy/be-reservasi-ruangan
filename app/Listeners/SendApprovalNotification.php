<?php

namespace App\Listeners;

use App\Events\ReservationApproved;
use App\Mail\ReservationApprovedMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SendApprovalNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ReservationApproved $event): void
    {
        $reservation = $event->reservation;

        Log::info('Listener dipanggil untuk reservasi ID: ' . $reservation->id);

        try {
            // Jika belum ada surat persetujuan, generate otomatis
            if (!$reservation->approval_letter || !Storage::disk('public')->exists($reservation->approval_letter)) {
                // Generate PDF
                $pdf = Pdf::loadView('pdfs.approval-letter', ['reservation' => $reservation]);

                // Simpan ke storage
                $fileName = 'approval_letters/approval_letter_' . $reservation->id . '.pdf';
                Storage::disk('public')->put($fileName, $pdf->output());

                // Update field approval_letter
                $reservation->approval_letter = $fileName;
                $reservation->save();

                Log::info('Surat persetujuan disimpan: ' . $fileName);
            } else {
                Log::info('Surat persetujuan sudah ada untuk reservasi ID: ' . $reservation->id);
            }

            Mail::to($reservation->student->email)
                ->send(new ReservationApprovedMail($reservation));

            Log::info('Email berhasil dikirim untuk reservasi ID: ' . $reservation->id);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email untuk reservasi ID: ' . $reservation->id . ' - Error: ' . $e->getMessage());
        }
    }
}
