<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlotBooking extends Model
{
    use HasFactory;

    protected $table = 'slot_bookings';

    protected $fillable = [
        'user_id',
        'turf_id',
        'sport_id',
        'date',
        'start_time',
        'end_time',
        'duration',
        'start_slot_value',
        'end_slot_value',
        'total_price',
        'sport_type',
        'special_requests',
        'status'
    ];

    protected $appends = ['status_text'];
    protected $casts = [
        'date' => 'date',
        'duration' => 'decimal:1',
        'total_price' => 'decimal:2'
    ];


    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function turf()
    {
        return $this->belongsTo(Turf::class);
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    // Helper methods
    public function isConfirmed()
    {
        return $this->status === 1;
    }

    public function isCancelled()
    {
        return $this->status === 2;
    }

    public function isCompleted()
    {
        return $this->status === 3;
    }

    /**
     * Get the status as a string
     */
    // Accessor to append status_text when model is converted to array/json

    public function getStatusTextAttribute()
    {
        return getBookingStatus((int)($this->attributes['status'] ?? 0));
    }
}
