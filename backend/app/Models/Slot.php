<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slot extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'turf_id',
        'slot_date',
        'start_time',
        'end_time',
        'status',
        'price',
        'booking_id'
    ];

    protected $casts = [
        'slot_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'price' => 'decimal:2'
    ];

    public function turf()
    {
        return $this->belongsTo(Turf::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function isBooked()
    {
        return $this->status === 'booked';
    }

    public function isBlocked()
    {
        return $this->status === 'blocked';
    }
}
