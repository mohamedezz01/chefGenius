<?php
// app/Http/Controllers/RecipeController.php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth; // <-- Import Auth facade
use Illuminate\Support\Facades\Log; 

use Illuminate\Http\Request;
use Illuminate\View\View;

class RecipeController extends Controller
{
    /**
     * Show the main recipe generator page.
     */
    public function welcome(): View
    {
        // You could fetch some initial data if needed, e.g., popular ingredients
        // $popularIngredients = Ingredient::popular()->take(5)->get();
        // return view('welcome', ['popularIngredients' => $popularIngredients]);
        Log::info('Entering RecipeController@welcome. Auth::check() is: ' . (Auth::check() ? 'TRUE' : 'FALSE'));
        // Just return the view for now
        return view('welcome'); // Loads resources/views/welcome.blade.php
    }
}