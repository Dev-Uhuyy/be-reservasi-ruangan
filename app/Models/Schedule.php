<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';

     protected $fillable = [
        'room_id',
        'date',
        'start_time',
        'end_time',
        'status',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function reservationDetails()
    {
        return $this->hasMany(ReservationDetails::class, 'schedule_id');
    }

}
