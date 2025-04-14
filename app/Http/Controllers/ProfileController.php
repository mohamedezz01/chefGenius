<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Use the Auth facade
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse; // Might need for update/delete later

class ProfileController extends Controller
{
    /**
     * Display the user's profile page.
     * Handles GET requests to /profile
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request): View
    {
        // Get the currently authenticated user object
        // The 'auth' middleware on the route ensures only logged-in users reach here
        $user = $request->user(); // Or Auth::user();

        // Return the profile view, passing the user data to it
        // Assumes you have a view at resources/views/profile/show.blade.php
        return view('profile.show', [
            'user' => $user,
        ]);
    }

    // If you installed Breeze previously, it might have added edit(), update(), destroy() methods.
    // You can keep them if you plan to use them, or remove them if not needed.
    // Example edit method (often added by Breeze):
    /*
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }
    */

    // Example update method (often added by Breeze):
    /*
    public function update(ProfileUpdateRequest $request): RedirectResponse // Uses a Form Request for validation
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
    */

     // Example destroy method (often added by Breeze):
    /*
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    */

}
