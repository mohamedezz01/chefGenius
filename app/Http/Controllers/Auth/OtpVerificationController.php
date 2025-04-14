<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserOtp; 
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail; 
use App\Mail\SendOtpMail;          

class OtpVerificationController extends Controller
{
    /**
     * Display the OTP verification view.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $email = $request->session()->get('email_for_otp_verification');
        if (!$email) {
            return redirect()->route('login')->withErrors(['email' => 'Verification session expired. Please log in or register again.']);
        }
        return view('auth.verify-email', ['email' => $email]);
    }

    /**
     * Mark the user's email address as verified using OTP.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'digits:6'],
        ]);

        $email = $request->session()->get('email_for_otp_verification');
        if (!$email) {
             return redirect()->route('login')->withErrors(['email' => 'Verification session expired. Please try again.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
             return back()->withErrors(['otp' => 'User not found.']);
        }
        $userOtp = UserOtp::where('user_id', $user->id)->latest()->first();

        if ($userOtp && $request->otp == $userOtp->code && $userOtp->expires_at->isAfter(now())) {
            if (! $user->hasVerifiedEmail()) {
                 $user->markEmailAsVerified();
            }
            UserOtp::where('user_id', $user->id)->delete(); // Delete used OTP
            Log::info("OTP verified successfully for {$email}.");

            Auth::login($user);
            $request->session()->regenerate();
            $request->session()->forget('email_for_otp_verification');

            return redirect()->intended(route('home'))->with('status', 'Email successfully verified!');

        } else {
             Log::warning("Incorrect or expired OTP submitted for {$email}.");
            return back()->withErrors(['otp' => 'The provided verification code is incorrect or has expired.']);
        }
    }

    /**
     * Resend the email verification notification (OTP).
     */
     public function resend(Request $request): RedirectResponse
     {
         $email = $request->session()->get('email_for_otp_verification');
         $user = $email ? User::where('email', $email)->first() : null;

         if (!$user) {
             return back()->withErrors(['email' => 'Could not find user to resend code.']);
         }
         if ($user->hasVerifiedEmail()) {
             Auth::login($user); // Log them in if already verified
             $request->session()->regenerate();
             $request->session()->forget('email_for_otp_verification');
             return redirect()->route('home')->with('status', 'Email already verified.');
         }

         $otpCode = random_int(100000, 999999);
         $expiresAt = now()->addMinutes(10);
         UserOtp::where('user_id', $user->id)->delete(); // Delete old OTPs
         UserOtp::create([
             'user_id' => $user->id,
             'code' => $otpCode,
             'expires_at' => $expiresAt,
         ]);

         try {
             Mail::to($user->email)->send(new SendOtpMail($otpCode));
             Log::info("OTP email resent successfully to {$user->email}");
             return back()->with('status', 'A fresh verification code has been sent to your email address.');
         } catch (\Exception $e) {
             Log::error("Failed to resend OTP email to {$user->email}: " . $e->getMessage());
             return back()->withErrors(['email' => 'Could not resend verification code at this time. Please try again later.']);
         }
     }
}
