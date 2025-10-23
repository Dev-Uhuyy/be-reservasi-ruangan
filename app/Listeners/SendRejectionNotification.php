<?php

namespace App\Listeners;

use App\Events\ReservationRejected;
use App\Mail\ReservationRejectedMail; // <-- IMPORT MAIL INI
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendRejectionNotification implements ShouldQueue // Tambahkan ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(ReservationRejected $event): void
    {
        try {
            Mail::to($event->reservation->student->email)
                // ->send(new ReservationRejectedMail($event->reservation->rejection_reason)); // <-- INI SALAH
                ->send(new ReservationRejectedMail($event->reservation)); // <-- INI YANG BENAR

            // Alasan "Kenapa?"
            // Mailable Anda (ReservationRejectedMail.php) 
            // di constructor-nya membutuhkan: __construct(Reservation $reservation)
            // Bukan: __construct(string $reason)
            // Mailable Anda sudah pintar mengambil reason dari $reservation->rejection_reason
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email penolakan untuk reservasi ID: ' . $event->reservation->id . ' - Error: ' . $e->getMessage());
        }
    }
}
