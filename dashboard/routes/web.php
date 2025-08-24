<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
