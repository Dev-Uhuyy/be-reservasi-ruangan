<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

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

    /**
     * Relasi ke mahasiswa yang membuat reservasi.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }


    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * RELASI BARU: Menghubungkan ke detail-detailnya (ruangan & jadwal yang dipesan).
     * Ini adalah inti dari sistem multi-reservasi kita.
     */
    public function details()
    {
        return $this->hasMany(ReservationDetail::class);
    }

    /**
     * DIPERBAIKI: Relasi yang benar adalah hasOne.
     * Satu reservasi hanya akan menghasilkan satu catatan histori.
     */
    public function bookingHistory()
    {
        return $this->hasOne(BookingHistory::class);
    }

    public function reservationDetails()
    {
        // Saya asumsikan ini relasi yang benar
        return $this->hasMany(ReservationDetail::class, 'reservation_id');
    }
}
