<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecipeSuggestionController;
use App\Http\Controllers\Api\SavedRecipeController; // <-- Make sure this is imported

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| These routes are loaded by the RouteServiceProvider with the 'api' prefix
| and the 'api' middleware group.
*/

// Public route for getting suggestions
Route::post('/suggest-recipes', [RecipeSuggestionController::class, 'suggest']);

// Default route often used for user info with Sanctum/API tokens (optional)
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// --- Routes requiring authentication for saved recipes ---
// Use 'auth:sanctum' middleware. The base 'api' group (including
// EnsureFrontendRequestsAreStateful) is applied by RouteServiceProvider.
Route::middleware('auth:sanctum')->group(function () { // <-- Use auth:sanctum
    // GET /api/saved-recipes
    Route::get('/saved-recipes', [SavedRecipeController::class, 'index'])->name('api.saved-recipes.index');

    // POST /api/saved-recipes
    Route::post('/saved-recipes', [SavedRecipeController::class, 'store'])->name('api.saved-recipes.store');

    // DELETE /api/saved-recipes/{savedRecipe}
    Route::delete('/saved-recipes/{savedRecipe}', [SavedRecipeController::class, 'destroy'])->name('api.saved-recipes.destroy');
});

// REMOVED: The extra Route::middleware('web')->group(...) wrapper
