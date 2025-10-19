<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::orderBy('id', 'desc')->paginate(10);
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
}


