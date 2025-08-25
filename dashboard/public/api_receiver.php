<?php

// Set the header to indicate a JSON response
header('Content-Type: application/json');

// Get the raw POST data from the request body
$json_data = file_get_contents('php://input');

// Decode the JSON data into a PHP associative array
$data = json_decode($json_data, true);

// Prepare the log message
$identifier = $data['student_identifier'] ?? 'unknown_id';
$status = $data['status'] ?? 'unknown_status';
$confidence = $data['confidence'] ?? '0.0';
$log_message = date('[Y-m-d H:i:s]') . " API RECEIVER HIT! Data: ID -> {$identifier}, Status -> {$status}, Confidence -> {$confidence}" . PHP_EOL;

// Define the path to the log file (relative to this script's location)
// We will write to a new log file to keep things clean
$log_file_path = __DIR__ . '/../storage/logs/api.log';

// Append the message to the log file
file_put_contents($log_file_path, $log_message, FILE_APPEND);

// Send a success response back
echo json_encode([
    'message' => 'Data received successfully by api_receiver.php'
]);

exit();