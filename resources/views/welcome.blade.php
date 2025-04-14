@extends('layouts.app')

@section('title', 'Find Recipes') 

@section('content')
    <div class="flex justify-center items-start">
        <div id="recipe-generator-area" class="bg-white rounded-lg shadow-lg p-6 md:p-8 w-full max-w-2xl">
            <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-6 text-center">What's in your kitchen?</h2>

            <div id="ingredient-input-area" class="mb-6">
                <label for="ingredients" class="block text-gray-700 text-sm font-bold mb-2">Add Ingredients:</label>
                <div class="flex space-x-2 mb-3">
                    <input type="text" id="ingredients" placeholder="e.g., chicken thighs, soy sauce, ginger" class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-500 transition duration-150 ease-in-out">
                    <button id="add-ingredient-button" class="btn btn-secondary flex-shrink-0">Add</button>
                </div>
                <ul id="ingredient-list" class="list-none p-0 m-0 mb-4">
                </ul>
            </div>

            <div id="recipe-suggestion-area" class="mb-6">
                <button id="suggest-recipe-button" class="btn btn-primary w-full text-lg py-3">
                    ✨ Find Recipes
                </button>
                <div id="recipe-suggestions" class="mt-6 space-y-4">
                    <p id="placeholder-text" class="text-gray-500 text-center py-6">Your recipe suggestions will appear here!</p>
                </div>
            </div>

            @auth {{-- show only if user is logged in --}}
            <div id="saved-recipes-area">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Your Saved Recipes:</h3>
                <ul id="saved-recipe-list" class="list-none p-0 m-0 space-y-3">
                </ul>
            </div>
            @endauth
        </div>
    </div>
@endsection
