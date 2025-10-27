<?php

/**
 * Test Slot Booking System API
 * 
 * This file tests the slot booking functionality including:
 * - Creating slot bookings
 * - Checking slot availability
 * - Fetching turf data with booked slots
 * - Updating booking status
 * - Getting user bookings and stats
 */

require __DIR__ . '/vendor/autoload.php';

$baseUrl = 'http://localhost/api';

// Get authentication token (replace with actual token from login)
$token = ''; // Get this by logging in first

function makeRequest($method, $url, $data = null, $token = null) {
    $ch = curl_init();
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } elseif ($method === 'PUT') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'data' => json_decode($response, true)
    ];
}

echo "=== Testing Slot Booking System ===\n\n";

// Test 1: Create a new slot booking
echo "Test 1: Creating a new slot booking\n";
echo "------------------------------------\n";

$bookingData = [
    'date' => '2025-10-27',
    'duration' => 1.5,
    'endTime' => '05:00',
    'specialRequests' => 'Test',
    'sportId' => 84,
    'sportType' => 'Badminton',
    'startTime' => '03:30',
    'end_slot_value' => 9,
    'start_slot_value' => 7,
    'status' => 'confirmed', // This will be converted to 1 in the database
    'totalPrice' => 459,
    'turfId' => 1
];

$response = makeRequest('POST', $baseUrl . '/slot-bookings', $bookingData, $token);
echo "Status Code: " . $response['code'] . "\n";
echo "Response: " . json_encode($response['data'], JSON_PRETTY_PRINT) . "\n\n";

if ($response['code'] === 201) {
    $bookingId = $response['data']['data']['booking']['id'];
    echo "✓ Slot booking created successfully with ID: $bookingId\n\n";
} else {
    echo "✗ Failed to create slot booking\n";
    echo "Make sure you have a valid auth token set in \$token variable\n\n";
    exit;
}

// Test 2: Get turf data with booked slots
echo "Test 2: Getting turf data with booked slots\n";
echo "--------------------------------------------\n";

// First, get turf slug (you may need to adjust this based on your actual turf)
$response = makeRequest('GET', $baseUrl . '/turf/cupiditate?date=2025-10-27');
echo "Status Code: " . $response['code'] . "\n";
echo "Response (excerpt): " . json_encode([
    'booked_slots' => $response['data']['data']['booked_slots'] ?? 'N/A',
    'booking_stats' => $response['data']['data']['booking_stats'] ?? 'N/A'
], JSON_PRETTY_PRINT) . "\n\n";

if (isset($response['data']['data']['booked_slots'])) {
    echo "✓ Turf data retrieved with booked slots\n\n";
} else {
    echo "✗ Failed to get turf data with booked slots\n\n";
}

// Test 3: Get user's bookings
echo "Test 3: Getting user's bookings\n";
echo "--------------------------------\n";

$response = makeRequest('GET', $baseUrl . '/slot-bookings/my', null, $token);
echo "Status Code: " . $response['code'] . "\n";
echo "Response (excerpt): " . json_encode([
    'count' => count($response['data']['data'] ?? []),
    'first_booking' => $response['data']['data'][0] ?? null
], JSON_PRETTY_PRINT) . "\n\n";

if ($response['code'] === 200) {
    echo "✓ User bookings retrieved successfully\n\n";
} else {
    echo "✗ Failed to get user bookings\n\n";
}

// Test 4: Get user's booking stats
echo "Test 4: Getting user's booking stats\n";
echo "-------------------------------------\n";

$response = makeRequest('GET', $baseUrl . '/slot-bookings/my/stats', null, $token);
echo "Status Code: " . $response['code'] . "\n";
echo "Response: " . json_encode($response['data'], JSON_PRETTY_PRINT) . "\n\n";

if ($response['code'] === 200) {
    echo "✓ User booking stats retrieved successfully\n\n";
} else {
    echo "✗ Failed to get user booking stats\n\n";
}

// Test 5: Get specific booking
echo "Test 5: Getting specific booking\n";
echo "---------------------------------\n";

$response = makeRequest('GET', $baseUrl . '/slot-bookings/' . $bookingId, null, $token);
echo "Status Code: " . $response['code'] . "\n";
echo "Response (excerpt): " . json_encode([
    'id' => $response['data']['data']['id'] ?? 'N/A',
    'status' => $response['data']['data']['status'] ?? 'N/A',
    'turf' => $response['data']['data']['turf']['name'] ?? 'N/A'
], JSON_PRETTY_PRINT) . "\n\n";

if ($response['code'] === 200) {
    echo "✓ Booking details retrieved successfully\n\n";
} else {
    echo "✗ Failed to get booking details\n\n";
}

// Test 6: Update booking status (Owner action)
echo "Test 6: Updating booking status\n";
echo "--------------------------------\n";

$updateData = [
    'status' => 'completed'
];

$response = makeRequest('PUT', $baseUrl . '/slot-bookings/' . $bookingId . '/status', $updateData, $token);
echo "Status Code: " . $response['code'] . "\n";
echo "Response: " . json_encode($response['data'], JSON_PRETTY_PRINT) . "\n\n";

if ($response['code'] === 200) {
    echo "✓ Booking status updated successfully\n\n";
} else {
    echo "✗ Failed to update booking status\n\n";
}

// Test 7: Get turf bookings (Owner view)
echo "Test 7: Getting turf bookings (Owner view)\n";
echo "------------------------------------------\n";

$response = makeRequest('GET', $baseUrl . '/slot-bookings/turf/1', null, $token);
echo "Status Code: " . $response['code'] . "\n";
echo "Response (excerpt): " . json_encode([
    'count' => count($response['data']['data'] ?? []),
    'first_booking' => $response['data']['data'][0] ?? null
], JSON_PRETTY_PRINT) . "\n\n";

if ($response['code'] === 200) {
    echo "✓ Turf bookings retrieved successfully\n\n";
} else {
    echo "✗ Failed to get turf bookings\n\n";
}

// Test 8: Try to create overlapping booking (should fail)
echo "Test 8: Attempting to create overlapping booking\n";
echo "---------------------------------------------------\n";

$overlappingBooking = [
    'date' => '2025-10-27',
    'duration' => 1.0,
    'endTime' => '04:30',
    'specialRequests' => '',
    'sportId' => 84,
    'sportType' => 'Badminton',
    'startTime' => '03:45',
    'end_slot_value' => 8,
    'start_slot_value' => 7,
    'status' => 'confirmed', // This will be converted to 1 in the database
    'totalPrice' => 306,
    'turfId' => 1
];

$response = makeRequest('POST', $baseUrl . '/slot-bookings', $overlappingBooking, $token);
echo "Status Code: " . $response['code'] . "\n";
echo "Response: " . json_encode($response['data'], JSON_PRETTY_PRINT) . "\n\n";

if ($response['code'] === 400) {
    echo "✓ Correctly prevented overlapping booking\n\n";
} else {
    echo "✗ Should have prevented overlapping booking\n\n";
}

echo "=== Testing Complete ===\n";

