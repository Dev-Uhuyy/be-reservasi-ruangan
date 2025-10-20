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
            view: 'emails.reservation-approved',
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
        // generate pdf
        $pdf = Pdf::loadView('pdfs.approval-letter', ['reservation' => $this->reservation]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Surat-Persetujuan-Reservasi.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
