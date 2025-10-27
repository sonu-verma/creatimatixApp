<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slot;
use App\Models\Turf;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SlotController extends Controller
{
    /**
     * Get slots for a specific turf and date
     * GET /api/slots/turf/{turfId}?date=2025-10-25
     */
    public function getSlots(Request $request, $turfId)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today'
        ]);

        $date = $request->input('date');
        
        // Check if turf exists
        $turf = Turf::find($turfId);
        if (!$turf) {
            return response()->json([
                'success' => false,
                'message' => 'Turf not found'
            ], 404);
        }

        // Get existing slots for the date
        $existingSlots = Slot::where('turf_id', $turfId)
            ->where('slot_date', $date)
            ->orderBy('start_time')
            ->get();

        // If no slots exist for this date, generate them
        if ($existingSlots->isEmpty()) {
            $this->generateSlotsForDate($turfId, $date);
            $existingSlots = Slot::where('turf_id', $turfId)
                ->where('slot_date', $date)
                ->orderBy('start_time')
                ->get();
        }

        // Organize slots into a grid format (8 columns for better display)
        $slotsGrid = $this->organizeSlotsIntoGrid($existingSlots);

        return response()->json([
            'success' => true,
            'data' => [
                'turf' => [
                    'id' => $turf->id,
                    'name' => $turf->name,
                    'location' => $turf->location,
                    'address' => $turf->address
                ],
                'date' => $date,
                'slots_grid' => $slotsGrid,
                'total_slots' => $existingSlots->count(),
                'available_slots' => $existingSlots->where('status', 'available')->count(),
                'booked_slots' => $existingSlots->where('status', 'booked')->count(),
                'blocked_slots' => $existingSlots->where('status', 'blocked')->count()
            ]
        ]);
    }

    /**
     * Organize slots into a grid format for better display
     */
    private function organizeSlotsIntoGrid($slots)
    {
        $grid = [];
        $columns = 8; // 8 columns for better grid display
        $currentRow = 0;
        $currentColumn = 0;

        foreach ($slots as $slot) {
            // Handle different time formats safely
            $time = null;
            try {
                $time = Carbon::createFromFormat('H:i:s', $slot->start_time);
            } catch (\Exception $e) {
                try {
                    $time = Carbon::createFromFormat('H:i', $slot->start_time);
                } catch (\Exception $e2) {
                    $time = Carbon::parse($slot->start_time);
                }
            }
            $formattedTime = $time->format('g:i A'); // Format like "6:00 AM", "1:30 PM"
            
            $slotData = [
                'id' => $slot->id,
                'time' => $formattedTime,
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
                'status' => $slot->status,
                'price' => $slot->price,
                'is_available' => $slot->isAvailable(),
                'is_booked' => $slot->isBooked(),
                'is_blocked' => $slot->isBlocked(),
                'display_text' => $formattedTime,
                'css_class' => $this->getSlotCssClass($slot->status)
            ];

            // Initialize row if it doesn't exist
            if (!isset($grid[$currentRow])) {
                $grid[$currentRow] = [];
            }

            $grid[$currentRow][$currentColumn] = $slotData;

            // Move to next column
            $currentColumn++;
            
            // If we've filled all columns in this row, move to next row
            if ($currentColumn >= $columns) {
                $currentColumn = 0;
                $currentRow++;
            }
        }

        return $grid;
    }

    /**
     * Get CSS class for slot based on status
     */
    private function getSlotCssClass($status)
    {
        switch ($status) {
            case 'available':
                return 'slot-available';
            case 'booked':
                return 'slot-booked';
            case 'blocked':
                return 'slot-blocked';
            default:
                return 'slot-unknown';
        }
    }

    /**
     * Generate slots for a specific date
     */
    private function generateSlotsForDate($turfId, $date)
    {
        $startTime = '06:00'; // 6 AM
        $endTime = '22:00';   // 10 PM
        $slotDuration = 30;   // 30 minutes per slot

        $currentTime = Carbon::createFromFormat('H:i', $startTime);
        $endDateTime = Carbon::createFromFormat('H:i', $endTime);

        while ($currentTime->lt($endDateTime)) {
            $slotEndTime = $currentTime->copy()->addMinutes($slotDuration);
            
            Slot::create([
                'turf_id' => $turfId,
                'slot_date' => $date,
                'start_time' => $currentTime->format('H:i:s'),
                'end_time' => $slotEndTime->format('H:i:s'),
                'status' => 'available',
                'price' => 500.00 // Default price, can be made dynamic
            ]);

            $currentTime->addMinutes($slotDuration);
        }
    }

    /**
     * Book multiple slots
     * POST /api/slots/book
     */
    public function bookSlots(Request $request)
    {
        $request->validate([
            'turf_id' => 'required|exists:turfs,id',
            'date' => 'required|date|after_or_equal:today',
            'slot_ids' => 'required|array|min:1',
            'slot_ids.*' => 'exists:slots,id',
            'user_id' => 'required|exists:users,id'
        ]);

        $turfId = $request->input('turf_id');
        $date = $request->input('date');
        $slotIds = $request->input('slot_ids');
        $userId = $request->input('user_id');

        // Check if all slots are available
        $slots = Slot::whereIn('id', $slotIds)
            ->where('turf_id', $turfId)
            ->where('slot_date', $date)
            ->where('status', 'available')
            ->get();

        if ($slots->count() !== count($slotIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Some slots are not available'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Create booking
            $totalPrice = $slots->sum('price');
            $booking = Booking::create([
                'id_user' => $userId,
                'turf_id' => $turfId,
                'selected_date' => $date,
                'selected_slots' => $slotIds,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_status' => 'pending'
            ]);

            // Update slots status
            Slot::whereIn('id', $slotIds)->update([
                'status' => 'booked',
                'booking_id' => $booking->id
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Slots booked successfully',
                'data' => [
                    'booking_id' => $booking->id,
                    'total_price' => $totalPrice,
                    'booked_slots' => $slots->count()
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Booking failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel booking and free slots
     * POST /api/slots/cancel/{bookingId}
     */
    public function cancelBooking($bookingId)
    {
        $booking = Booking::find($bookingId);
        
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        DB::beginTransaction();
        try {
            // Update slots status back to available
            Slot::where('booking_id', $bookingId)->update([
                'status' => 'available',
                'booking_id' => null
            ]);

            // Update booking status
            $booking->update(['status' => 'cancelled']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Cancellation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's bookings
     * GET /api/slots/my-bookings/{userId}
     */
    public function getMyBookings($userId)
    {
        $bookings = Booking::where('id_user', $userId)
            ->with(['turf', 'slots'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bookings->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'turf_name' => $booking->turf->name,
                    'date' => $booking->selected_date,
                    'total_price' => $booking->total_price,
                    'status' => $booking->status,
                    'payment_status' => $booking->payment_status,
                    'slots' => $booking->slots->map(function ($slot) {
                        return [
                            'start_time' => $slot->start_time,
                            'end_time' => $slot->end_time,
                            'price' => $slot->price
                        ];
                    }),
                    'created_at' => $booking->created_at
                ];
            })
        ]);
    }

    /**
     * Block/Unblock slots (Admin function)
     * POST /api/slots/block
     */
    public function blockSlots(Request $request)
    {
        $request->validate([
            'slot_ids' => 'required|array|min:1',
            'slot_ids.*' => 'exists:slots,id',
            'action' => 'required|in:block,unblock'
        ]);

        $slotIds = $request->input('slot_ids');
        $action = $request->input('action');
        $status = $action === 'block' ? 'blocked' : 'available';

        Slot::whereIn('id', $slotIds)->update(['status' => $status]);

        return response()->json([
            'success' => true,
            'message' => 'Slots ' . $action . 'ed successfully'
        ]);
    }

    /**
     * Get slots in React-optimized format
     * GET /api/slots/turf/{turfId}/react?date=2025-10-25
     */
    public function getSlotsForReact(Request $request, $turfId)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today'
        ]);

        $date = $request->input('date');
        
        // Check if turf exists
        $turf = Turf::find($turfId);
        if (!$turf) {
            return response()->json([
                'success' => false,
                'message' => 'Turf not found'
            ], 404);
        }

        // Get existing slots for the date
        $existingSlots = Slot::where('turf_id', $turfId)
            ->where('slot_date', $date)
            ->orderBy('start_time')
            ->get();

        // If no slots exist for this date, generate them
        if ($existingSlots->isEmpty()) {
            $this->generateSlotsForDate($turfId, $date);
            $existingSlots = Slot::where('turf_id', $turfId)
                ->where('slot_date', $date)
                ->orderBy('start_time')
                ->get();
        }

        // Create a comprehensive slots array for React
        $slotsArray = $existingSlots->map(function ($slot) {
            // Handle different time formats safely
            $time = null;
            $endTime = null;
            
            try {
                $time = Carbon::createFromFormat('H:i:s', $slot->start_time);
            } catch (\Exception $e) {
                try {
                    $time = Carbon::createFromFormat('H:i', $slot->start_time);
                } catch (\Exception $e2) {
                    $time = Carbon::parse($slot->start_time);
                }
            }
            
            try {
                $endTime = Carbon::createFromFormat('H:i:s', $slot->end_time);
            } catch (\Exception $e) {
                try {
                    $endTime = Carbon::createFromFormat('H:i', $slot->end_time);
                } catch (\Exception $e2) {
                    $endTime = Carbon::parse($slot->end_time);
                }
            }
            
            return [
                'id' => $slot->id,
                'time' => $time->format('g:i A'),
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
                'start_timestamp' => $time->timestamp,
                'end_timestamp' => $endTime->timestamp,
                'status' => $slot->status,
                'price' => $slot->price,
                'is_available' => $slot->isAvailable(),
                'is_booked' => $slot->isBooked(),
                'is_blocked' => $slot->isBlocked(),
                'display_text' => $time->format('g:i A'),
                'css_class' => $this->getSlotCssClass($slot->status),
                'color' => $this->getSlotColor($slot->status),
                'can_select' => $slot->isAvailable()
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'turf' => [
                    'id' => $turf->id,
                    'name' => $turf->name,
                    'location' => $turf->location,
                    'address' => $turf->address
                ],
                'date' => $date,
                'slots' => $slotsArray,
                'grid_config' => [
                    'columns' => 8,
                    'slot_duration' => 30,
                    'start_time' => '06:00',
                    'end_time' => '22:00'
                ],
                'summary' => [
                    'total_slots' => $existingSlots->count(),
                    'available_slots' => $existingSlots->where('status', 'available')->count(),
                    'booked_slots' => $existingSlots->where('status', 'booked')->count(),
                    'blocked_slots' => $existingSlots->where('status', 'blocked')->count()
                ]
            ]
        ]);
    }

    /**
     * Get color for slot based on status
     */
    private function getSlotColor($status)
    {
        switch ($status) {
            case 'available':
                return '#10B981'; // Green
            case 'booked':
                return '#EF4444'; // Red
            case 'blocked':
                return '#6B7280'; // Gray
            default:
                return '#9CA3AF'; // Light gray
        }
    }
}
