<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sponsored_by',
        'user_name',
        'registration_start_date',
        'registration_end_date',
        'event_start_date',
        'event_end_date',
        'registration_amount',
        'team_limit',
        'sports_type',
        'event_type',
        'location_lat',
        'location_lon',
        'banner',
        'description',
        'rules',
        'is_active',
        'address',
    ];

    protected $casts = [
        'registration_start_date' => 'date',
        'registration_end_date' => 'date',
        'event_start_date' => 'datetime',
        'event_end_date' => 'datetime',
        'registration_amount' => 'decimal:2',
        'team_limit' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $appends = ['banner_url'];


    public function getBannerUrlAttribute()
    {
        return asset('storage/event_banners').'/'.$this->banner;
    }

    // 👇 Example: Format eventStartDate
    public function getEventStartDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    // 👇 Example: Format eventEndDate
    public function getEventEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }
}


