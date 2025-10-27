<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SlotBooking;
use App\Models\Turf;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SlotBookingController extends Controller
{
    /**
     * Helper method to format date
     */
    private function formatDate($date)
    {
        if (is_string($date)) {
            return $date;
        }
        return Carbon::parse($date)->format('Y-m-d');
    }

    /**
     * Helper method to convert string status to numeric
     */
    private function getStatusValue($statusString)
    {
        switch($statusString) {
            case 'pending':
                return 0;
            case 'confirmed':
                return 1;
            case 'cancelled':
                return 2;
            case 'completed':
                return 3;
            default:
                return 0;
        }
    }

    /**
     * Store a new slot booking
     * POST /api/slot-bookings
     */
    public function store(Request $request)
    {
        // Get authenticated user
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User must be authenticated'
            ], 401);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'turfId' => 'required|exists:turfs,id',
            'sportId' => 'required|exists:sports,id',
            'date' => 'required|date|after_or_equal:today',
            'startTime' => 'required|date_format:H:i',
            'endTime' => 'required|date_format:H:i|after:startTime',
            'duration' => 'required|numeric|min:0.5',
            'start_slot_value' => 'required|integer|min:0',
            'end_slot_value' => 'required|integer|gt:start_slot_value',
            'totalPrice' => 'required|numeric|min:0',
            'status' => 'nullable|in:0,1,2,3',
            'specialRequests' => 'nullable|string|max:1000',
            'sportType' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if turf exists
        $turf = Turf::find($request->turfId);
        if (!$turf) {
            return response()->json([
                'success' => false,
                'message' => 'Turf not found'
            ], 404);
        }

        // Check if sport exists
        $sport = Sport::find($request->sportId);
        if (!$sport) {
            return response()->json([
                'success' => false,
                'message' => 'Sport not found'
            ], 404);
        }

        // Check for overlapping bookings
        $isSlotAvailable = $this->checkSlotAvailability(
            $request->turfId,
            $request->date,
            $request->start_slot_value,
            $request->end_slot_value
        );

        if (!$isSlotAvailable) {
            return response()->json([
                'success' => false,
                'message' => 'Some slots are already booked. Please select different time slots.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Create the booking
            $booking = SlotBooking::create([
                'user_id' => $user->id,
                'turf_id' => $request->turfId,
                'sport_id' => $request->sportId,
                'date' => $request->date,
                'start_time' => $request->startTime,
                'end_time' => $request->endTime,
                'duration' => $request->duration,
                'start_slot_value' => $request->start_slot_value,
                'end_slot_value' => $request->end_slot_value,
                'total_price' => $request->totalPrice,
                'status' => $this->getStatusValue($request->status ?? 'pending'),
                'sport_type' => $request->sportType ?? $sport->name,
                'special_requests' => $request->specialRequests ?? ''
            ]);

            DB::commit();

            // Load relationships
            $booking->load(['turf', 'sport', 'user']);

            return response()->json([
                'success' => true,
                'message' => 'Slot booking created successfully',
                'data' => [
                    'booking' => [
                        'id' => $booking->id,
                        'user' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email
                        ],
                        'turf' => [
                            'id' => $turf->id,
                            'name' => $turf->name,
                            'location' => $turf->location,
                            'address' => $turf->address
                        ],
                        'sport' => [
                            'id' => $sport->id,
                            'name' => $sport->name,
                            'type' => $booking->sport_type
                        ],
                        'date' => $this->formatDate($booking->date),
                        'start_time' => $booking->start_time,
                        'end_time' => $booking->end_time,
                        'duration' => $booking->duration,
                        'start_slot_value' => $booking->start_slot_value,
                        'end_slot_value' => $booking->end_slot_value,
                        'total_price' => $booking->total_price,
                        'status' => $booking->status,
                        'special_requests' => $booking->special_requests,
                        'created_at' => $booking->created_at
                    ]
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Booking failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if slots are available for booking
     */
    private function checkSlotAvailability($turfId, $date, $startSlot, $endSlot)
    {
        // Get all confirmed bookings for the same turf and date
        $existingBookings = SlotBooking::where('turf_id', $turfId)
            ->where('date', $date)
            ->where('status', 1) // 1 = confirmed
            ->get();

        // Check for any overlap
        foreach ($existingBookings as $booking) {
            // Check if there's any overlap between the requested slots and existing bookings
            if (!(
                $endSlot <= $booking->start_slot_value || 
                $startSlot >= $booking->end_slot_value
            )) {
                return false; // Slots overlap
            }
        }

        return true; // No overlap, slots are available
    }

    /**
     * Get user's slot bookings
     * GET /api/slot-bookings/my
     */
    public function getMyBookings(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User must be authenticated'
            ], 401);
        }

        $bookings = SlotBooking::where('user_id', $user->id)
            ->with(['turf', 'sport'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bookings->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'turf' => [
                        'id' => $booking->turf->id,
                        'name' => $booking->turf->name,
                        'location' => $booking->turf->location,
                        'address' => $booking->turf->address
                    ],
                    'sport' => [
                        'id' => $booking->sport->id ?? null,
                        'name' => $booking->sport->name ?? null,
                        'type' => $booking->sport_type
                    ],
                    'date' => $this->formatDate($booking->date),
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'duration' => $booking->duration,
                    'start_slot_value' => $booking->start_slot_value,
                    'end_slot_value' => $booking->end_slot_value,
                    'total_price' => $booking->total_price,
                    'status' => $booking->status,
                    'status_text' => $booking->status_text,
                    'special_requests' => $booking->special_requests,
                    'created_at' => $booking->created_at
                ];
            })
        ]);
    }

    /**
     * Get a specific booking
     * GET /api/slot-bookings/{id}
     */
    public function show($id)
    {
        $booking = SlotBooking::with(['turf', 'sport', 'user'])->find($id);
        
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $booking->id,
                'user' => [
                    'id' => $booking->user->id,
                    'name' => $booking->user->name,
                    'email' => $booking->user->email
                ],
                'turf' => [
                    'id' => $booking->turf->id,
                    'name' => $booking->turf->name,
                    'location' => $booking->turf->location,
                    'address' => $booking->turf->address
                ],
                'sport' => [
                    'id' => $booking->sport->id ?? null,
                    'name' => $booking->sport->name ?? null,
                    'type' => $booking->sport_type
                ],
                'date' => $this->formatDate($booking->date),
                'start_time' => $booking->start_time,
                'end_time' => $booking->end_time,
                'duration' => $booking->duration,
                'start_slot_value' => $booking->start_slot_value,
                'end_slot_value' => $booking->end_slot_value,
                'total_price' => $booking->total_price,
                'status' => $booking->status,
                'status_text' => $booking->status_text,
                'special_requests' => $booking->special_requests,
                'created_at' => $booking->created_at,
                'updated_at' => $booking->updated_at
            ]
        ]);
    }

    /**
     * Update booking status (Owner/Admin)
     * PUT /api/slot-bookings/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {

        
        $booking = SlotBooking::find($id);
        
        if($booking && $booking->user_id != auth()->user()->id){
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this booking'
            ], 403);
        }

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        $booking->status = 2;
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated successfully',
            'data' => [
                'id' => $booking->id,
                'status' => $booking->status,
                'updated_at' => $booking->updated_at
            ]
        ]);
    }

    /**
     * Get turf bookings (Owner view)
     * GET /api/slot-bookings/turf/{turfId}
     */
    public function getTurfBookings(Request $request, $turf_id = null)
    {

        $sportId = $request->sport_id;
        $date = $request->get('date', date('Y-m-d'));
        $bookings = SlotBooking::where('turf_id', $turf_id);

        if($date){
            $bookings = $bookings->where('date', $date);
        }

        if($sportId){
            $bookings = $bookings->where('sport_id', $sportId);
        }
        // 
        $bookings = $bookings->with(['user', 'sport'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bookings->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'user' => [
                        'id' => $booking->user->id,
                        'name' => $booking->user->name,
                        'email' => $booking->user->email
                    ],
                    'sport' => [
                        'id' => $booking->sport->id ?? null,
                        'name' => $booking->sport->name ?? null,
                        'type' => $booking->sport_type
                    ],
                    'date' => $this->formatDate($booking->date),
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'duration' => $booking->duration,
                    'start_slot_value' => $booking->start_slot_value,
                    'end_slot_value' => $booking->end_slot_value,
                    'total_price' => $booking->total_price,
                    'status' => $booking->status,
                    'status_text' => $booking->status_text,
                    'special_requests' => $booking->special_requests,
                    'created_at' => $booking->created_at
                ];
            })
        ]);
    }

    /**
     * Get user's total bookings count
     * GET /api/slot-bookings/my/stats
     */
    public function getMyBookingStats(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User must be authenticated'
            ], 401);
        }

        $stats = [
            'total_bookings' => SlotBooking::where('user_id', $user->id)->count(),
            'confirmed_bookings' => SlotBooking::where('user_id', $user->id)->where('status', 1)->count(),
            'pending_bookings' => SlotBooking::where('user_id', $user->id)->where('status', 0)->count(),
            'completed_bookings' => SlotBooking::where('user_id', $user->id)->where('status', 3)->count(),
            'cancelled_bookings' => SlotBooking::where('user_id', $user->id)->where('status', 2)->count(),
            'total_spent' => SlotBooking::where('user_id', $user->id)->sum('total_price')
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
