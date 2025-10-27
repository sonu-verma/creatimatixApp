<?php

namespace App\Services;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class EventStatusService
{
    /**
     * Get events by status
     */
    public function getEventsByStatus($status, $perPage = 10)
    {
        $query = Event::query();

        switch ($status) {
            case 'upcoming':
                $query->where('is_active', true)
                      ->where('event_start_date', '>', Carbon::now());
                break;
            
            case 'ongoing':
                $query->where('is_active', true)
                      ->where('event_start_date', '<=', Carbon::now())
                      ->where('event_end_date', '>=', Carbon::now());
                break;
            
            case 'completed':
                $query->where('event_end_date', '<', Carbon::now());
                break;
            
            case 'cancelled':
                $query->where('is_active', false);
                break;
            
            default:
                // Return all events if status is not specified
                break;
        }

        return $query->orderBy('event_start_date', 'desc')->paginate($perPage);
    }

    /**
     * Get events by multiple statuses
     */
    public function getEventsByStatuses(array $statuses, $perPage = 10)
    {
        $query = Event::query();

        $conditions = [];

        foreach ($statuses as $status) {
            switch ($status) {
                case 'upcoming':
                    $conditions[] = function ($q) {
                        $q->where('is_active', true)
                          ->where('event_start_date', '>', Carbon::now());
                    };
                    break;
                
                case 'ongoing':
                    $conditions[] = function ($q) {
                        $q->where('is_active', true)
                          ->where('event_start_date', '<=', Carbon::now())
                          ->where('event_end_date', '>=', Carbon::now());
                    };
                    break;
                
                case 'completed':
                    $conditions[] = function ($q) {
                        $q->where('event_end_date', '<', Carbon::now());
                    };
                    break;
                
                case 'cancelled':
                    $conditions[] = function ($q) {
                        $q->where('is_active', false);
                    };
                    break;
            }
        }

        if (!empty($conditions)) {
            $query->where(function ($q) use ($conditions) {
                foreach ($conditions as $condition) {
                    $q->orWhere($condition);
                }
            });
        }

        return $query->orderBy('event_start_date', 'desc')->paginate($perPage);
    }

    /**
     * Get upcoming events (next 7 days)
     */
    public function getUpcomingEvents($days = 7, $perPage = 10)
    {
        return Event::where('is_active', true)
            ->where('event_start_date', '>', Carbon::now())
            ->where('event_start_date', '<=', Carbon::now()->addDays($days))
            ->orderBy('event_start_date', 'asc')
            ->paginate($perPage);
    }

    /**
     * Get events happening today
     */
    public function getTodayEvents($perPage = 10)
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        return Event::where('is_active', true)
            ->whereBetween('event_start_date', [$today, $tomorrow])
            ->orderBy('event_start_date', 'asc')
            ->paginate($perPage);
    }

    /**
     * Get events by date range
     */
    public function getEventsByDateRange($startDate, $endDate, $perPage = 10)
    {
        return Event::where('is_active', true)
            ->whereBetween('event_start_date', [$startDate, $endDate])
            ->orderBy('event_start_date', 'asc')
            ->paginate($perPage);
    }

    /**
     * Get event statistics by status
     */
    public function getEventStatistics()
    {
        $now = Carbon::now();

        return [
            'total_events' => Event::count(),
            'upcoming_events' => Event::where('is_active', true)
                ->where('event_start_date', '>', $now)
                ->count(),
            'ongoing_events' => Event::where('is_active', true)
                ->where('event_start_date', '<=', $now)
                ->where('event_end_date', '>=', $now)
                ->count(),
            'completed_events' => Event::where('event_end_date', '<', $now)
                ->count(),
            'cancelled_events' => Event::where('is_active', false)
                ->count(),
        ];
    }

    /**
     * Get events with status information
     */
    public function getEventsWithStatus($perPage = 10)
    {
        $events = Event::orderBy('event_start_date', 'desc')->paginate($perPage);
        
        // Add status information to each event
        $events->getCollection()->transform(function ($event) {
            $event->status_info = [
                'status' => $event->event_status,
                'time_until_start' => $event->getTimeUntilStart(),
                'time_until_end' => $event->getTimeUntilEnd(),
                'time_since_end' => $event->getTimeSinceEnd(),
            ];
            return $event;
        });

        return $events;
    }

    /**
     * Get events that need status updates (for batch processing)
     */
    public function getEventsNeedingStatusUpdate()
    {
        $now = Carbon::now();
        
        // Events that should be marked as ongoing
        $ongoingEvents = Event::where('is_active', true)
            ->where('event_start_date', '<=', $now)
            ->where('event_end_date', '>=', $now)
            ->get();

        // Events that should be marked as completed
        $completedEvents = Event::where('event_end_date', '<', $now)
            ->get();

        return [
            'ongoing' => $ongoingEvents,
            'completed' => $completedEvents,
        ];
    }

    /**
     * Update event statuses in batch
     */
    public function updateEventStatuses()
    {
        $eventsToUpdate = $this->getEventsNeedingStatusUpdate();
        $updated = 0;

        // This method can be used for batch status updates if needed
        // For now, status is calculated dynamically, but this could be used
        // to store status in database for better performance

        return $updated;
    }

    /**
     * Get events by sports type and status
     */
    public function getEventsBySportsTypeAndStatus($sportsType, $status = null, $perPage = 10)
    {
        $query = Event::where('sports_type', $sportsType);

        if ($status) {
            switch ($status) {
                case 'upcoming':
                    $query->where('is_active', true)
                          ->where('event_start_date', '>', Carbon::now());
                    break;
                
                case 'ongoing':
                    $query->where('is_active', true)
                          ->where('event_start_date', '<=', Carbon::now())
                          ->where('event_end_date', '>=', Carbon::now());
                    break;
                
                case 'completed':
                    $query->where('event_end_date', '<', Carbon::now());
                    break;
                
                case 'cancelled':
                    $query->where('is_active', false);
                    break;
            }
        }

        return $query->orderBy('event_start_date', 'desc')->paginate($perPage);
    }
}
