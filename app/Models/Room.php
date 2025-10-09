<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';

    protected $fillable = [
        'room_name',
        'floor',
        'facilities',
        'status',
        'capacity',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'room_id');
    }

    public function reservations()
    {
        return $this->hasMany(ReservationDetails::class, 'room_id');
    }


}
