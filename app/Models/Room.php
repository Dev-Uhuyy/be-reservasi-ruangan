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
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'room_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'room_id');
    }


}
