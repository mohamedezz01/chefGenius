<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SavedRecipe; // Ensure this is imported
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SavedRecipeController extends Controller
{
    /**
     * Display a listing of the authenticated user's saved recipes.
     * CORRECTED to return recipes, not the user object.
     */
    public function index(Request $request): JsonResponse
    {
        Log::info('API /saved-recipes index hit.');
        $user = $request->user(); // Get authenticated user

        if (!$user) {
             Log::warning('API /saved-recipes index: Unauthenticated access detected IN CONTROLLER.');
             return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        // --- CORRECTED LOGIC: Fetch and return recipes ---
        try {
            // Retrieve recipes belonging to the user, newest first
            // Assumes 'savedRecipes' relationship exists on User model
            $recipes = $user->savedRecipes()->latest()->get();

            Log::info("API /saved-recipes index: Found " . $recipes->count() . " recipes for user {$user->id}");
            return response()->json($recipes); // Return the collection of recipes

        } catch (\Exception $e) {
             Log::error("API /saved-recipes index: Error fetching recipes for user {$user->id}: " . $e->getMessage());
             return response()->json(['error' => 'Could not retrieve saved recipes.'], 500);
        }
        // --- END CORRECTED LOGIC ---
    }

    /**
     * Store a newly created saved recipe in storage for the authenticated user.
     */
    public function store(Request $request): JsonResponse
    {
         Log::info('API /saved-recipes store hit.');
         // ... (logging for auth check) ...
         Log::info('Auth Check via Request: ' . ($request->user() ? 'User ID: '.$request->user()->id : 'NULL'));
         Log::info('Request Data: ', $request->except('password'));

        $user = $request->user();
        if (!$user) {
             Log::warning('API /saved-recipes store: Unauthenticated access detected.');
             return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $validatedData = $request->validate([ /* ... validation ... */ 'name' => 'required|string|max:255', 
            'description' => 'nullable|string', 
            'estimatedPrepTime' => 'nullable|string|max:50',
            'difficulty' => 'nullable|string|max:50', 
            'steps' => 'required|array', 'steps.*' => 'string', ]);
        $validatedData['user_id'] = $user->id; $validatedData['source'] = 'gemini';
        $existing = SavedRecipe::where('user_id', $user->id)->where('name', $validatedData['name'])->first();
        if ($existing) { Log::info("API /saved-recipes store: Recipe '{$validatedData['name']}' already saved for user {$user->id}."); return response()->json(['message' => 'Recipe already saved.'], 409); }
        try { $savedRecipe = SavedRecipe::create($validatedData); Log::info("Recipe saved for user {$user->id}: {$savedRecipe->name} (ID: {$savedRecipe->id})"); return response()->json($savedRecipe, 201); } catch (\Exception $e) { Log::error("Failed to save recipe for user {$user->id}: " . $e->getMessage()); return response()->json(['error' => 'Failed to save recipe.'], 500); }
    }

    /**
     * Remove the specified saved recipe from storage.
     */
    public function destroy(Request $request, SavedRecipe $savedRecipe): JsonResponse
    {
         Log::info("API /saved-recipes destroy hit for recipe ID: {$savedRecipe->id}");
         // ... (logging for auth check) ...
         Log::info('Auth Check via Request: ' . ($request->user() ? 'User ID: '.$request->user()->id : 'NULL'));

        $user = $request->user();
        if (!$user) { Log::warning("API /saved-recipes destroy: Unauthenticated attempt for recipe ID: {$savedRecipe->id}"); return response()->json(['error' => 'Unauthenticated.'], 401); }
        if ($user->id !== $savedRecipe->user_id) { Log::warning("User {$user->id} attempted to delete recipe {$savedRecipe->id} owned by user {$savedRecipe->user_id}"); return response()->json(['error' => 'Forbidden.'], 403); }
        try { $recipeName = $savedRecipe->name; $savedRecipe->delete(); Log::info("Recipe deleted for user {$user->id}: {$recipeName} (ID: {$savedRecipe->id})"); return response()->json(null, 204); } catch (\Exception $e) { Log::error("Failed to delete recipe {$savedRecipe->id} for user {$user->id}: " . $e->getMessage()); return response()->json(['error' => 'Failed to delete recipe.'], 500); }
    }
}
