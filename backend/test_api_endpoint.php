<?php

// Simple test script to check if the API endpoint works
$url = 'http://127.0.0.1:8000/api/slots/turf/1?date=2025-10-25';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n";

// Check if response is valid JSON
$decoded = json_decode($response, true);
if (json_last_error() === JSON_ERROR_NONE) {
    echo "JSON is valid\n";
} else {
    echo "JSON Error: " . json_last_error_msg() . "\n";
}

