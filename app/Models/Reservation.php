<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservations';

    protected $fillable = [
        'student_id',
        'room_id',
        'schedule_id',
        'request_date',
        'rejection_reason',
        'approval_letter',
        'approved_by',
        'status',

    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function bookingHistories()
    {
        return $this->belongsTo(BookingHistory::class, 'reservation_id');
    }
}
