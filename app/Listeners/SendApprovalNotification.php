<?php

namespace App\Listeners;

use App\Events\ReservationApproved;
use App\Mail\ReservationApprovedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendApprovalNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ReservationApproved $event): void
    {
        try {
            Mail::to($event->reservation->student->email)
                ->send(new ReservationApprovedMail($event->reservation));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email persetujuan untuk reservasi ID: ' . $event->reservation->id . ' - Error: ' . $e->getMessage());
            // Anda bisa menambahkan mekanisme retry di sini jika perlu
        }
    }
}