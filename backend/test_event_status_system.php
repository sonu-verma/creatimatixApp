<?php

/**
 * Test script for Event Status System
 * This script demonstrates how to use the event status API endpoints
 */

// Base URL for your API
$baseUrl = 'http://localhost:8000/api';

// Test functions
function testGetAllEvents($baseUrl) {
    $url = $baseUrl . '/events';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'http_code' => $httpCode,
        'response' => json_decode($response, true)
    ];
}

function testGetEventsByStatus($baseUrl, $status) {
    $url = $baseUrl . '/events/status/' . $status;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'http_code' => $httpCode,
        'response' => json_decode($response, true)
    ];
}

function testGetUpcomingEvents($baseUrl, $days = 7) {
    $url = $baseUrl . '/events/upcoming?days=' . $days;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'http_code' => $httpCode,
        'response' => json_decode($response, true)
    ];
}

function testGetTodayEvents($baseUrl) {
    $url = $baseUrl . '/events/today';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'http_code' => $httpCode,
        'response' => json_decode($response, true)
    ];
}

function testGetEventStatistics($baseUrl) {
    $url = $baseUrl . '/events/statistics';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'http_code' => $httpCode,
        'response' => json_decode($response, true)
    ];
}

function testGetEventsByDateRange($baseUrl, $startDate, $endDate) {
    $url = $baseUrl . '/events/date-range';
    $data = json_encode([
        'start_date' => $startDate,
        'end_date' => $endDate
    ]);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'http_code' => $httpCode,
        'response' => json_decode($response, true)
    ];
}

// Run tests
echo "=== Event Status System API Tests ===\n\n";

// Test 1: Get all events
echo "Test 1: Get all events\n";
$result = testGetAllEvents($baseUrl);
echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

// Test 2: Get upcoming events
echo "Test 2: Get upcoming events\n";
$result = testGetEventsByStatus($baseUrl, 'upcoming');
echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

// Test 3: Get ongoing events
echo "Test 3: Get ongoing events\n";
$result = testGetEventsByStatus($baseUrl, 'ongoing');
echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

// Test 4: Get completed events
echo "Test 4: Get completed events\n";
$result = testGetEventsByStatus($baseUrl, 'completed');
echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

// Test 5: Get cancelled events
echo "Test 5: Get cancelled events\n";
$result = testGetEventsByStatus($baseUrl, 'cancelled');
echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

// Test 6: Get upcoming events for next 14 days
echo "Test 6: Get upcoming events for next 14 days\n";
$result = testGetUpcomingEvents($baseUrl, 14);
echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

// Test 7: Get today's events
echo "Test 7: Get today's events\n";
$result = testGetTodayEvents($baseUrl);
echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

// Test 8: Get event statistics
echo "Test 8: Get event statistics\n";
$result = testGetEventStatistics($baseUrl);
echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

// Test 9: Get events by date range
echo "Test 9: Get events by date range\n";
$result = testGetEventsByDateRange($baseUrl, '2025-01-15', '2025-01-20');
echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

echo "=== Test Complete ===\n";
echo "Note: Make sure you have events in your database with different dates to see various statuses.\n";
echo "You can create test events with:\n";
echo "- Past dates (completed)\n";
echo "- Future dates (upcoming)\n";
echo "- Current date range (ongoing)\n";
echo "- is_active = false (cancelled)\n";
