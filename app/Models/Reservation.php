<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';

    /**
     * DIUBAH: Disesuaikan dengan Service dan Migrasi
     * 1. 'purpose' ditambahkan (dari Migrasi & Service)
     * 2. 'request_date' ditambahkan (dari Migrasi)
     * 3. 'room_id' dan 'schedule_id' DIHAPUS (karena tidak ada di Migrasi)
     */
    protected $fillable = [
        'student_id',
        'purpose',          // <-- DITAMBAHKAN
        'request_date',     // <-- DITAMBAHKAN
        'rejection_reason',
        'approval_letter',
        'approved_by',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // ... (relasi room() dan schedule() Anda sebenarnya tidak terpakai di sini) ...

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * DIUBAH: Nama relasi diubah dari bookingHistories() menjadi reservationDetails()
     * agar sesuai dengan panggilan di ReservationService.
     *
     * Pastikan Anda punya model ReservationDetails.
     */
    public function reservationDetails()
    {
        // Saya asumsikan ini relasi yang benar
        return $this->hasMany(ReservationDetails::class, 'reservation_id');
    }
}
