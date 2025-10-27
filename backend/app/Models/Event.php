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

    protected $appends = ['banner_url', 'event_status'];


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

    /**
     * Get the event status based on current time and event dates
     */
    public function getEventStatusAttribute()
    {
        $now = Carbon::now();
        $eventStart = Carbon::parse($this->event_start_date);
        $eventEnd = Carbon::parse($this->event_end_date);

        // If event is not active, it's cancelled
        if (!$this->is_active) {
            return 'cancelled';
        }

        // If current time is before event start
        if ($now->lt($eventStart)) {
            return 'upcoming';
        }

        // If current time is between event start and end
        if ($now->between($eventStart, $eventEnd)) {
            return 'ongoing';
        }

        // If current time is after event end
        if ($now->gt($eventEnd)) {
            return 'completed';
        }

        return 'upcoming'; // Default fallback
    }

    /**
     * Check if event is upcoming
     */
    public function isUpcoming()
    {
        return $this->event_status === 'upcoming';
    }

    /**
     * Check if event is ongoing
     */
    public function isOngoing()
    {
        return $this->event_status === 'ongoing';
    }

    /**
     * Check if event is completed
     */
    public function isCompleted()
    {
        return $this->event_status === 'completed';
    }

    /**
     * Check if event is cancelled
     */
    public function isCancelled()
    {
        return $this->event_status === 'cancelled';
    }

    /**
     * Get time remaining until event starts (for upcoming events)
     */
    public function getTimeUntilStart()
    {
        if ($this->isUpcoming()) {
            $now = Carbon::now();
            $eventStart = Carbon::parse($this->event_start_date);
            return $now->diffForHumans($eventStart, true);
        }
        return null;
    }

    /**
     * Get time remaining until event ends (for ongoing events)
     */
    public function getTimeUntilEnd()
    {
        if ($this->isOngoing()) {
            $now = Carbon::now();
            $eventEnd = Carbon::parse($this->event_end_date);
            return $now->diffForHumans($eventEnd, true);
        }
        return null;
    }

    /**
     * Get time since event ended (for completed events)
     */
    public function getTimeSinceEnd()
    {
        if ($this->isCompleted()) {
            $now = Carbon::now();
            $eventEnd = Carbon::parse($this->event_end_date);
            return $eventEnd->diffForHumans($now, true);
        }
        return null;
    }
}


