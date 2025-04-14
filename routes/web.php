<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Root Route -> Shows Login for Guests, Redirects Auth Users ---
Route::get('/', [LoginController::class, 'create']) // Show login form directly
    ->middleware('guest') // Apply guest middleware HERE
    ->name('login'); // Name the root route 'login'

// --- Manual Authentication Routes ---

// Login POST Route (Handles form submission from '/')
Route::post('/login', [LoginController::class, 'store']) // Handles POST to /login path
    ->middleware('guest');
    // Action in login form should be action="{{ url('/login') }}"

// Registration Routes
Route::get('/register', [RegisterController::class, 'create'])
    ->middleware('guest')
    ->name('register');
Route::post('/register', [RegisterController::class, 'store'])
    ->middleware('guest');

// OTP Email Verification Routes
Route::get('/verify-otp', [OtpVerificationController::class, 'show'])
    ->name('verification.notice');
Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])
    ->name('verification.verify');
Route::post('/resend-otp', [OtpVerificationController::class, 'resend'])
    ->name('verification.send');

// Logout Route
Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');


// --- Authenticated User Routes ---
Route::middleware(['auth'])->group(function () { // Add 'verified' later if needed

    // Main application page for logged-in users
    Route::get('/home', [RecipeController::class, 'welcome'])
        ->name('home'); // <-- Default redirect for logged-in users

    // Profile Display Route
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');

    // Add other authenticated routes here...

});

// REMOVED: The separate GET /login route, as '/' handles showing the login form now.

