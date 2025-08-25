<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/update-status', function (Request $request) {
    $identifier = $request->input('student_identifier');
    $status = $request->input('status');
    $confidence = $request->input('confidence');

    // This writes to: storage/logs/laravel.log
    Log::info("API Endpoint Hit! Data Received: ID -> {$identifier}, Status -> {$status}, Confidence -> {$confidence}");

    return response()->json([
        'message' => 'Data received successfully.'
    ]);
});