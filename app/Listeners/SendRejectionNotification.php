<?php

namespace App\Listeners;

use App\Events\ReservationRejected;
use App\Mail\ReservationRejectedMail; 
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendRejectionNotification implements ShouldQueue 
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(ReservationRejected $event): void
    {
        try {
            Mail::to($event->reservation->student->email)
                ->send(new ReservationRejectedMail($event->reservation)); 

        } catch (\Exception $e) {
            Log::error('Gagal mengirim email penolakan untuk reservasi ID: ' . $event->reservation->id . ' - Error: ' . $e->getMessage());
        }
    }
}
