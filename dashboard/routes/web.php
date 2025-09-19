<?php

// These 'use' statements import the necessary classes for this file.
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

//==============================================================================
// PUBLIC-FACING ROUTES
//==============================================================================

// The main landing page for the application.
Route::get('/', function () {
    return view('welcome');
});

//==============================================================================
// AUTHENTICATED WEB PAGES
//==============================================================================
// These routes require a user to be logged in to access them.

// Displays the main user dashboard after login.
// 'auth' middleware ensures the user is logged in.
// 'verified' middleware ensures the user has verified their email address.
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Displays the concentration analysis page.
Route::get('/concentration', function () {
    return view('concentration');
})->middleware(['auth', 'verified'])->name('concentration');

// A shorthand route to directly display the 'profile' view.
// Note: This is different from the profile editing routes below.
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Displays the session insights page.
Route::get('/insights', function () {
    return view('session-insights');
})->middleware(['auth', 'verified'])->name('insights');

// Displays the engagement trends page.
Route::get('/trends', function () {
    return view('engagement-trends');
})->middleware(['auth', 'verified'])->name('trends');

//==============================================================================
// API-LIKE ROUTES (For Frontend Polling)
//==============================================================================

// API endpoint for the frontend to fetch the latest status data.
// This is likely called periodically (e.g., every few seconds) to update the UI.
Route::get('/api/get-status', function () {
    // Define the full path to the JSON file that acts as a simple data store.
    $statusFilePath = storage_path('app/status.json');

    // Safety check: Before reading, ensure the file actually exists.
    if (!file_exists($statusFilePath)) {
        // If the file hasn't been created yet, return an empty JSON array
        // to prevent errors on the frontend.
        return response()->json([]);
    }

    // If the file exists, read its entire raw content.
    $statusData = file_get_contents($statusFilePath);

    // Return the raw content. The frontend expects this to be a valid JSON string.
    // Laravel will automatically set the 'Content-Type: application/json' header.
    return $statusData;
});

// API endpoint for an external service (e.g., a Python script) to post status updates.
Route::post('/api/update-status', function (Request $request) {
    // Extract the data from the incoming POST request's body.
    $identifier = $request->input('student_identifier');
    $status = $request->input('status');
    $confidence = $request->input('confidence');

    // Log the received data to `storage/logs/laravel.log` for debugging and auditing.
    Log::info("SUCCESS! Data Received: ID -> {$identifier}, Status -> {$status}, Confidence -> {$confidence}");

    // Return a simple JSON response to confirm that the data was received successfully.
    return response()->json([
        'message' => 'Data received successfully.'
    ]);
});

//==============================================================================
// AUTHENTICATED USER PROFILE MANAGEMENT
//==============================================================================

// Group of routes for handling user profile actions. All require authentication.
Route::middleware('auth')->group(function () {
    // Display the profile editing form.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Handle the submission of the updated profile information.
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Handle the user account deletion request.
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//==============================================================================
// AUTHENTICATION ROUTES
//==============================================================================

// This file includes all the standard authentication routes provided by Laravel Breeze,
// such as login, registration, password reset, and logout.
require __DIR__.'/auth.php';