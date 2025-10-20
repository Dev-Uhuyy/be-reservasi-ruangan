<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReservationDetail extends Model
{
    use HasFactory;

    protected $table = 'reservation_details';

    protected $fillable = [
        'reservation_id',
        'room_id',
        'schedule_id',
    ];
    
    /**
     * Relasi ke 'induk' atau 'tiket utama' reservasi.
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Relasi ke ruangan yang dipesan.
     */
    public function room(){
        return $this->belongsTo(Room::class);
    }

    /**
     * Relasi ke jadwal yang dipesan.
     */
    public function schedule(){
        return $this->belongsTo(Schedule::class);
    }
}
