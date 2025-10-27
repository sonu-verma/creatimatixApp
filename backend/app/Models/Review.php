<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'turf_id',
        'user_id',
        'rating',
        'comment',
        'status'
    ];

    protected $casts = [
        'rating' => 'integer',
        'status' => 'boolean'
    ];

    /**
     * Get the turf that owns the review.
     */
    public function turf()
    {
        return $this->belongsTo(Turf::class, 'turf_id');
    }

    /**
     * Get the user that owns the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
