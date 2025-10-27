<?php

/**
 * Simple test script to verify the review system
 * This script demonstrates how to use the review API endpoints
 */

// Base URL for your API
$baseUrl = 'http://localhost:8000/api';

// Sample test data
$testData = [
    'turf_id' => 1,
    'rating' => 5,
    'comment' => 'This is a test review for the turf system.'
];

// Test functions
function testCreateReview($baseUrl, $testData, $token = null) {
    $url = $baseUrl . '/reviews';
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'http_code' => $httpCode,
        'response' => json_decode($response, true)
    ];
}

function testGetTurfReviews($baseUrl, $turfId) {
    $url = $baseUrl . '/turfs/' . $turfId . '/reviews';
    
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

function testGetReviewStats($baseUrl, $turfId) {
    $url = $baseUrl . '/turfs/' . $turfId . '/reviews/stats';
    
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

// Run tests
echo "=== Review System API Tests ===\n\n";

// Test 1: Get turf reviews (should work without authentication)
echo "Test 1: Get turf reviews\n";
$result = testGetTurfReviews($baseUrl, 1);
echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

// Test 2: Get review statistics
echo "Test 2: Get review statistics\n";
$result = testGetReviewStats($baseUrl, 1);
echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

// Test 3: Create review (requires authentication)
echo "Test 3: Create review (requires authentication)\n";
$result = testCreateReview($baseUrl, $testData);
echo "HTTP Code: " . $result['http_code'] . "\n";
echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";

echo "=== Test Complete ===\n";
echo "Note: To test authenticated endpoints, you need to:\n";
echo "1. Register a user or login to get a token\n";
echo "2. Use the token in the Authorization header\n";
echo "3. Make sure you have a turf with ID 1 in your database\n";
