<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';

    protected $fillable = [
        'student_id',
        'purpose', 
        'request_date',
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

    /**
     * Relasi ke admin yang menyetujui.
     */
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
}
