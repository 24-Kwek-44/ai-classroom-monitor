<?php

// Set the header to indicate a JSON response
header('Content-Type: application/json');

// --- 1. DEFINE THE PATH FOR OUR LIVE STATUS FILE ---
// We will store the live data in `storage/app/status.json`.
// `__DIR__` gives us the current directory (`public`), so `../storage/app/` goes up one level and then into storage/app.
$statusFilePath = __DIR__ . '/../storage/app/status.json';

// --- 2. GET THE RAW JSON DATA FROM THE PYTHON SCRIPT ---
// This gets the entire body of the request, which is the JSON string.
$json_data = file_get_contents('php://input');

// --- 3. WRITE THE LATEST DATA DIRECTLY TO THE FILE ---
// file_put_contents will create the file if it doesn't exist,
// and overwrite it with the new data if it does. This is exactly what we want.
if ($json_data) { // Only write if we actually received data
    file_put_contents($statusFilePath, $json_data);
}

// --- 4. (OPTIONAL) LOG THAT WE RECEIVED DATA FOR DEBUGGING ---
// This is useful to see if the script is being hit, even if there's a problem with the data.
$log_message = date('[Y-m-d H:i:s]') . " API RECEIVER: Updated status.json" . PHP_EOL;
$log_file_path = __DIR__ . '/../storage/logs/api.log';
file_put_contents($log_file_path, $log_message, FILE_APPEND);

// --- 5. SEND A SUCCESS RESPONSE BACK TO THE PYTHON SCRIPT ---
// This lets the Python script know that the data was received successfully.
echo json_encode([
    'message' => 'Status file updated successfully.'
]);

exit();