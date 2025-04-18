<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\SavedRecipeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Root Route -> Shows Login for Guests, Redirects Auth Users ---
Route::get('/', [LoginController::class, 'create'])
    ->middleware('guest')
    ->name('login');

// --- Manual Authentication Routes ---
Route::post('/login', [LoginController::class, 'store'])
    ->middleware('guest');
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
    ->middleware('auth') // This uses the default guard ('web'), which is fine
    ->name('logout');


// --- Authenticated User Routes ---
// Use 'auth:web' explicitly here too for consistency, although 'auth' should work
Route::middleware(['auth:web'])->group(function () { // Add 'verified' later if needed

    // Main application page for logged-in users
    Route::get('/home', [RecipeController::class, 'welcome'])
        ->name('home');

    // Profile Display Route
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');

    // --- Saved Recipe API Routes (within web group) ---
    // These routes automatically get the 'web' middleware group applied first by the RouteServiceProvider
    // We explicitly add 'auth:web' to ensure the correct guard checks authentication.
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/saved-recipes', [SavedRecipeController::class, 'index'])->name('saved-recipes.index');
        Route::post('/saved-recipes', [SavedRecipeController::class, 'store'])->name('saved-recipes.store');
        Route::delete('/saved-recipes/{savedRecipe}', [SavedRecipeController::class, 'destroy'])->name('saved-recipes.destroy');
    });
    // --- END Saved Recipe Routes ---

});

