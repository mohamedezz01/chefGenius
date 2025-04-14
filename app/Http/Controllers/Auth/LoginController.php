<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log; // <-- Import Log facade

class LoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
        {
            $credentials = $request->validate([
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
            ]);

            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                // Authentication was successful...
                $request->session()->regenerate();

                // --- ADD LOGGING HERE ---
                Log::info('Login successful in LoginController. Auth::check() is: ' . (Auth::check() ? 'TRUE' : 'FALSE'));
                // ------------------------

                return redirect()->intended(route('home'));
            }

            // Authentication failed...
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }


    /**
     * Destroy an authenticated session (logout).
     */
    public function destroy(Request $request): RedirectResponse
    {
        $userId = Auth::id(); // Get user ID before logout for logging
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Log::info('User logged out: ' . $userId); // Log logout
        return redirect('/');
    }
}
