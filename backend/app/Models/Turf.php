<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turf extends Model
{
    use HasFactory;
    protected $fillable = ['name','slug','location','address','timing','description','features','benefits','latitude','longitude','status','rules','pricing'];
    // protected $casts = ['rules' => 'array'];

    protected $appends = ['min_price'];

    public function sports()
    {
        return $this->hasMany(Sport::class, 'id_turf','id');
    }

    public function images()
    {
        return $this->hasMany(TurfImage::class, 'id_turf','id');
    }

    public function getMinPriceAttribute(){
        return $this->sports()->min('rate_per_hour');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'turf_id', 'id');
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class, 'turf_id', 'id')->where('status', true);
    }

    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->approvedReviews()->count();
    }

    public function slots()
    {
        return $this->hasMany(Slot::class, 'turf_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'turf_id', 'id');
    }

    public function slotBookings()
    {
        return $this->hasMany(SlotBooking::class, 'turf_id', 'id');
    }
}
