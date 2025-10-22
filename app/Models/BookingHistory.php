<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingHistory extends Model
{
    protected $table = 'booking_histories';

     protected $fillable = [
        'reservation_id',
        'room_id',
        'student_id',
        'start_time',
        'end_time',
        'booking_date',
        'usage_status',
        'verified_by',
        'verified_at',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    // public function user()
    // {
    //     return $this->hasMany(User::class);
    // }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function getCombinedStatusAttribute(): ?string // Return type diubah ke nullable string
    {
        // Pastikan relasi reservation ada
        if ($this->reservation) {
            // Jika reservasi ditolak, langsung kembalikan 'rejected'
            if ($this->reservation->status === 'rejected') {
                return 'rejected';
            }

            // Jika reservasi disetujui, cek status penggunaan
            if ($this->reservation->status === 'approved') {
                if ($this->usage_status === 'used') {
                    return 'approved/used';
                }
                if ($this->usage_status === 'unused') {
                    return 'approved/unused';
                }
                // Jika masih need_verification, anggap belum masuk history final
                // Kembalikan null agar difilter oleh service jika perlu
                if ($this->usage_status === 'need_verification') {
                   return null; // Atau 'approved' jika ingin tetap ditampilkan
                }
            }
        }

        // Jika status pending atau data tidak konsisten, kembalikan null
        return null;
    }

}
