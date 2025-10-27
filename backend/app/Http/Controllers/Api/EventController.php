<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Models\Event;
use App\Services\EventStatusService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventController extends Controller
{
    protected $eventStatusService;

    public function __construct(EventStatusService $eventStatusService)
    {
        $this->eventStatusService = $eventStatusService;
    }

    public function index(Request $request)
    {
        $status = $request->get('status');
        $perPage = $request->get('per_page', 10);

        if ($status) {
            $events = $this->eventStatusService->getEventsByStatus($status, $perPage);
        } else {
            $events = $this->eventStatusService->getEventsWithStatus($perPage);
        }

        return response()->json([
            'statusCode' => Response::HTTP_OK,
            'events' => $events,
        ], Response::HTTP_OK);
    }

    public function store(EventStoreRequest $request)
    {

        $validated = $request->validated();

        if ($request->hasFile('bannerFile')) {
            $file = $request->file('bannerFile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('event_banners', $filename, 'public');
            $validated['banner'] = $filename;
        }

        $event = Event::create([
            'title' => $validated['title'],
            'user_name' => $validated['userName'] ?? null,
            'registration_start_date' => $validated['registrationStartDate'],
            'registration_end_date' => $validated['registrationEndDate'],
            'event_start_date' => $validated['eventStartDate'],
            'event_end_date' => $validated['eventEndDate'],
            'registration_amount' => $validated['registrationAmount'],
            'team_limit' => $validated['teamLimit'] ?? null,
            'sports_type' => $validated['sportsType'],
            'event_type' => $validated['eventType'],
            'location_lat' => $validated['locationLat'],
            'location_lon' => $validated['locationLon'],
            'banner' => $validated['banner'] ?? null,
            'description' => $validated['description'],
            'rules' => $validated['rules'] ?? null,
            'is_active' => $validated['isActive'] ?? true,
            'address' => $validated['address'],
        ]);

        return ResponseHelper::success(
            message: 'Event created successfully!',
            data: $event,
            statusCode: Response::HTTP_CREATED
        );
    }

    public function show(string $id)
    {
        $event = Event::find($id);
        if (!$event) {
            return ResponseHelper::error(message: 'Event not found', statusCode: Response::HTTP_NOT_FOUND);
        }
        return ResponseHelper::success(message: 'Event data', data: $event, statusCode: Response::HTTP_OK);
    }

    public function update(EventUpdateRequest $request, string $id)
    {
        $event = Event::find($id);
        if (!$event) {
            return ResponseHelper::error(message: 'Event not found', statusCode: Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validated();

        if ($request->hasFile('bannerFile')) {
            $file = $request->file('bannerFile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('event_banners', $filename, 'public');
            $validated['banner'] = $filename;
        }

        $event->update([
            'title' => $validated['title'] ?? $event->title,
            'sponsored_by' => $validated['sponsoredBy'] ?? $event->sponsored_by,
            'user_name' => $validated['userName'] ?? $event->user_name,
            'registration_start_date' => $validated['registrationStartDate'] ?? $event->registration_start_date,
            'registration_end_date' => $validated['registrationEndDate'] ?? $event->registration_end_date,
            'event_start_date' => $validated['eventStartDate'] ?? $event->event_start_date,
            'event_end_date' => $validated['eventEndDate'] ?? $event->event_end_date,
            'registration_amount' => $validated['registrationAmount'] ?? $event->registration_amount,
            'team_limit' => $validated['teamLimit'] ?? $event->team_limit,
            'sports_type' => $validated['sportsType'] ?? $event->sports_type,
            'event_type' => $validated['eventType'] ?? $event->event_type,
            'location_lat' => $validated['locationLat'] ?? $event->location_lat,
            'location_lon' => $validated['locationLon'] ?? $event->location_lon,
            'banner' => $validated['banner'] ?? $event->banner,
            'description' => $validated['description'] ?? $event->description,
            'rules' => $validated['rules'] ?? $event->rules,
            'is_active' => $validated['isActive'] ?? $event->is_active,
            'address' => $validated['address'] ?? $event->address,
        ]);

        return ResponseHelper::success(message: 'Event updated successfully!', data: $event->fresh(), statusCode: Response::HTTP_OK);
    }

    public function destroy(string $id)
    {
        $event = Event::find($id);
        if (!$event) {
            return ResponseHelper::error(message: 'Event not found', statusCode: Response::HTTP_NOT_FOUND);
        }
        $event->delete();
        return ResponseHelper::success(message: 'Event deleted successfully!', data: null, statusCode: Response::HTTP_OK);
    }

    /**
     * Get events by status
     */
    public function getEventsByStatus(Request $request, $status)
    {
        $perPage = $request->get('per_page', 10);
        $events = $this->eventStatusService->getEventsByStatus($status, $perPage);
        
        return ResponseHelper::success(
            message: "Events with status '{$status}' retrieved successfully",
            data: $events,
            statusCode: Response::HTTP_OK
        );
    }

    /**
     * Get upcoming events
     */
    public function getUpcomingEvents(Request $request)
    {
        try {
            $days = $request->get('days', 7);
            $perPage = $request->get('per_page', 10);
            
            $events = $this->eventStatusService->getUpcomingEvents($days, $perPage);
            
            return ResponseHelper::success(
                message: "Upcoming events for next {$days} days retrieved successfully",
                data: $events,
                statusCode: Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                message: "Error retrieving upcoming events: " . $e->getMessage(),
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Get today's events
     */
    public function getTodayEvents(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $events = $this->eventStatusService->getTodayEvents($perPage);
        
        return ResponseHelper::success(
            message: "Today's events retrieved successfully",
            data: $events,
            statusCode: Response::HTTP_OK
        );
    }

    /**
     * Get events by date range
     */
    public function getEventsByDateRange(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $perPage = $request->get('per_page', 10);
        
        $events = $this->eventStatusService->getEventsByDateRange($startDate, $endDate, $perPage);
        
        return ResponseHelper::success(
            message: "Events for date range retrieved successfully",
            data: $events,
            statusCode: Response::HTTP_OK
        );
    }

    /**
     * Get event statistics
     */
    public function getEventStatistics()
    {
        $stats = $this->eventStatusService->getEventStatistics();
        
        return ResponseHelper::success(
            message: "Event statistics retrieved successfully",
            data: $stats,
            statusCode: Response::HTTP_OK
        );
    }

    /**
     * Get events by sports type and status
     */
    public function getEventsBySportsType(Request $request, $sportsType)
    {
        $status = $request->get('status');
        $perPage = $request->get('per_page', 10);
        
        $events = $this->eventStatusService->getEventsBySportsTypeAndStatus($sportsType, $status, $perPage);
        
        return ResponseHelper::success(
            message: "Events for sports type '{$sportsType}' retrieved successfully",
            data: $events,
            statusCode: Response::HTTP_OK
        );
    }
}


