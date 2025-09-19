<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are typically stateless and are loaded by the RouteServiceProvider.
|
*/

/**
 * Handles incoming POST requests to log a student's status update.
 *
 * This endpoint is designed to be called by an external service or device. It accepts
 * a student's identifier, a status, and a confidence score, logs this information
 * for auditing/debugging, and then returns a success confirmation.
 *
 * @route   POST /api/update-status
 *
 * @param \Illuminate\Http\Request $request The incoming HTTP request.
 * @bodyParam string student_identifier required The unique identifier for the student. Example: "S12345"
 * @bodyParam string status required The status being reported. Example: "attentive"
 * @bodyParam float confidence required The confidence level of the status detection. Example: 0.95
 *
 * @return \Illuminate\Http\JsonResponse A JSON response confirming data receipt.
 */
Route::post('/update-status', function (Request $request) {
    // 1. Extract the expected data from the incoming request's body.
    $identifier = $request->input('student_identifier');
    $status = $request->input('status');
    $confidence = $request->input('confidence');

    // 2. Log the received information. This is the primary purpose of this endpoint.
    // The log entry provides a clear audit trail of what data was received and when.
    // In Laravel, this writes to: storage/logs/laravel.log by default.
    Log::info("API Endpoint Hit! Data Received: ID -> {$identifier}, Status -> {$status}, Confidence -> {$confidence}");

    // 3. Return a standardized JSON response to the caller.
    // This acknowledges that the server has successfully received and processed the request.
    return response()->json([
        'message' => 'Data received successfully.'
    ]);
});