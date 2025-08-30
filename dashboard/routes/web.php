<?php

// CRITICAL DIFFERENCE #1: Make sure these 'use' statements are at the top
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ... (All your other routes like /dashboard, /concentration, etc. stay here) ...
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/concentration', function () {
    return view('concentration');
})->middleware(['auth', 'verified'])->name('concentration');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/insights', function () {
    return view('session-insights');
})->middleware(['auth', 'verified'])->name('insights');

Route::get('/trends', function () {
    return view('engagement-trends');
})->middleware(['auth', 'verified'])->name('trends');

Route::get('/api/get-status', function () {
    // Define the path to our live status file
    $statusFilePath = storage_path('app/status.json');

    // Check if the file exists before we try to read it
    if (!file_exists($statusFilePath)) {
        // If the file doesn't exist yet, return an empty array
        return response()->json([]);
    }

    // Read the content of the file
    $statusData = file_get_contents($statusFilePath);

    // Send the content back as a JSON response
    // The 'Content-Type: application/json' header will be set automatically
    return $statusData;
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::post('/api/update-status', function (Request $request) {
    $identifier = $request->input('student_identifier');
    $status = $request->input('status');
    $confidence = $request->input('confidence');

    Log::info("SUCCESS! Data Received: ID -> {$identifier}, Status -> {$status}, Confidence -> {$confidence}");

    return response()->json([
        'message' => 'Data received successfully.'
    ]);
}); // <