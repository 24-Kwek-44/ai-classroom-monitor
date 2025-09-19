<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

//==============================================================================
// GUEST (UNAUTHENTICATED) ROUTES
//==============================================================================
// These routes are only accessible to users who are not logged in.
// The 'guest' middleware redirects authenticated users to the home page.
Route::middleware('guest')->group(function () {
    // Registration Routes
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register'); // Display the registration form.

    Route::post('register', [RegisteredUserController::class, 'store']); // Handle the submission of the registration form.

    // Login Routes
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login'); // Display the login form.

    Route::post('login', [AuthenticatedSessionController::class, 'store']); // Handle an incoming authentication request (login attempt).

    // Password Reset (Forgot Password) Routes
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request'); // Display the form to request a password reset link.

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email'); // Handle the request to send the password reset link via email.

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset'); // Display the password reset form, identified by the token.

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store'); // Handle the submission of the new password.
});


//==============================================================================
// AUTHENTICATED ROUTES
//==============================================================================
// These routes require the user to be logged in.
// The 'auth' middleware redirects unauthenticated users to the login page.
Route::middleware('auth')->group(function () {
    // Email Verification Routes
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice'); // Display the email verification prompt/notice page.

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1']) // The 'signed' middleware prevents URL tampering.
        ->name('verification.verify'); // Handle the actual email verification link click.

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1') // 'throttle' prevents users from spamming verification emails.
        ->name('verification.send'); // Resend the email verification notification.

    // Password Confirmation Routes (for sensitive actions)
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm'); // Display the password confirmation form.

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']); // Handle the submission of the password for confirmation.

    // Password Update Route
    Route::put('password', [PasswordController::class, 'update'])->name('password.update'); // Allow the user to update their current password.

    // Logout Route
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout'); // Log the user out of the application.
});