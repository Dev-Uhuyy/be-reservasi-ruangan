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



}
