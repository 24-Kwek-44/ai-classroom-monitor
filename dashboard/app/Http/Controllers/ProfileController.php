<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     * This method retrieves the authenticated user and passes it to the view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request): View
    {
        // Return the 'profile.edit' view, passing the currently authenticated user
        // so their information can be displayed in the form fields.
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     * This method handles the validation and persistence of the updated profile data.
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Fill the user model with the validated data from the form request.
        // The `validated()` method ensures we only use data that has passed
        // the validation rules defined in `ProfileUpdateRequest`.
        $request->user()->fill($request->validated());

        // If the user has changed their email address, we must reset their verification
        // status to null. This is a security and data integrity measure to ensure
        // the user confirms ownership of the new email address.
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Persist the updated user information to the database.
        $request->user()->save();

        // Redirect the user back to the profile edit page with a 'profile-updated'
        // status message, which can be used to display a success notification.
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     * This is a sensitive, destructive action and requires password confirmation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // First, validate the request to ensure the user has provided their current
        // password. This is a security measure to confirm their identity before deletion.
        // Errors are stored in the 'userDeletion' error bag.
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // Retrieve the user instance before we log them out, as we'll need it
        // after the session is destroyed.
        $user = $request->user();

        // Log the user out of the application.
        Auth::logout();

        // Permanently delete the user's record from the database.
        $user->delete();

        // Invalidate the user's session and regenerate their CSRF token. This is a crucial
        // security step to ensure the old session cannot be reused.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Finally, redirect the user to the application's homepage.
        return Redirect::to('/');
    }
}