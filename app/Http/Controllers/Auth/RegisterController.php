<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; 
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use App\Models\UserOtp;
use Illuminate\Support\Facades\Mail; 
use App\Mail\SendOtpMail;        


class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'], // Hashed by model cast
        ]);

        // --- Generate and Store OTP in Database ---
        $otpCode = random_int(100000, 999999);
        $expiresAt = now()->addMinutes(10); // OTP valid for 10 minutes

        // Delete any previous OTPs for this user
        UserOtp::where('user_id', $user->id)->delete();

        // Create the new OTP record
        UserOtp::create([
            'user_id' => $user->id,
            'code' => $otpCode, // Storing plain code for simplicity now
            'expires_at' => $expiresAt,
        ]);

        // --- Send OTP via Email (using Mail facade and Mailable) ---
        try {
            Mail::to($user->email)->send(new SendOtpMail($otpCode)); // Send the email
             Log::info("OTP email initiated successfully to {$user->email}");
        } catch (\Exception $e) {
            // Log error if email sending fails
             Log::error("Failed to send OTP email to {$user->email}: " . $e->getMessage());
             // Consider how to handle this - maybe prevent login until verified?
             // For now, we proceed but log the error.
        }

        // event(new Registered($user));

        // Store email in session to identify user on OTP screen
        $request->session()->put('email_for_otp_verification', $user->email);

        // Redirect to OTP verification notice screen
        return redirect()->route('verification.notice')
                         ->with('status', 'Registration successful! Please check your email for the 6-digit verification code.');
    }
}

