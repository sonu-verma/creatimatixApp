<?php

/**
 * Test Status Conversion
 * 
 * This file demonstrates how the numeric status values are converted to string status names
 */

require __DIR__ . '/vendor/autoload.php';

// Test the getBookingStatus function
echo "=== Testing Status Conversion ===\n\n";

echo "Testing getBookingStatus function:\n";
echo "Status 0: " . getBookingStatus(0) . "\n";
echo "Status 1: " . getBookingStatus(1) . "\n";
echo "Status 2: " . getBookingStatus(2) . "\n";
echo "Status 3: " . getBookingStatus(3) . "\n";
echo "Status 99: " . getBookingStatus(99) . " (default)\n\n";

// Test with SlotBooking model
echo "Testing SlotBooking model accessor:\n";

// Create a mock SlotBooking instance
$booking = new \App\Models\SlotBooking();
$booking->setRawAttributes([
    'id' => 1,
    'user_id' => 1,
    'turf_id' => 1,
    'sport_id' => 84,
    'date' => '2025-10-27',
    'start_time' => '03:30',
    'end_time' => '05:00',
    'duration' => 1.5,
    'start_slot_value' => 7,
    'end_slot_value' => 9,
    'total_price' => 459,
    'sport_type' => 'Badminton',
    'special_requests' => 'Test',
    'status' => 1, // Numeric status
    'created_at' => now(),
    'updated_at' => now()
]);

echo "Raw status value: " . $booking->raw_status . "\n";
echo "String status: " . $booking->status . "\n";
echo "Is confirmed: " . ($booking->isConfirmed() ? 'Yes' : 'No') . "\n";
echo "Is cancelled: " . ($booking->isCancelled() ? 'Yes' : 'No') . "\n";
echo "Is completed: " . ($booking->isCompleted() ? 'Yes' : 'No') . "\n\n";

// Test different status values
$statuses = [0, 1, 2, 3];
foreach ($statuses as $statusValue) {
    $booking->setRawAttributes(['status' => $statusValue]);
    echo "Status $statusValue: " . $booking->status . "\n";
}

echo "\n=== Testing Complete ===\n";

