<?php

namespace App\Mail;

use App\Models\Reservation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class ReservationApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Reservation $reservation;
    public string $downloadUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
        $this->downloadUrl = Storage::disk('public')->url($reservation->approval_letter);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reservasi anda telah Disetujui',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reservations.approved',
            with: [
                'reservation' => $this->reservation
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // LOGIKA BARU:
        // Cek apakah path file ada di database
        if (empty($this->reservation->approval_letter)) {
            return [];
        }

        // Ambil path lengkap file dari disk 'public'
        $fullPath = Storage::disk('public')->path($this->reservation->approval_letter);

        // Pastikan file-nya benar-benar ada sebelum di-attach
        if (!Storage::disk('public')->exists($this->reservation->approval_letter)) {
            // Log error jika file tidak ditemukan
            Log::error('File attachment tidak ditemukan untuk reservasi ID: ' . $this->reservation->id);
            return [];
        }

        // Attach file yang sudah dibuat oleh ApprovalService
        return [
            Attachment::fromPath($fullPath)
                ->as('Surat-Persetujuan-Reservasi.pdf') // Beri nama yang ramah
                ->withMime('application/pdf'),
        ];
    }
}
