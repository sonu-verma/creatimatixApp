<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id_user',
        'turf_id',
        'selected_date',
        'selected_slots',
        'total_price',
        'status',
        'payment_status'
    ];

    protected $casts = [
        'selected_slots' => 'array',
        'total_price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function turf()
    {
        return $this->belongsTo(Turf::class);
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }
}
