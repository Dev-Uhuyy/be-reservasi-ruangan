<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ReservationDetails extends Model
{
    protected $table = 'reservation_details';

    protected $fillable = [
        'reservation_id',
        'room_id',
        'schedule_id',
    ];
    
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    public function room(){
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function schedule(){
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }
    
}
