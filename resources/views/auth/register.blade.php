@extends('layouts.app') {{-- Use the main app layout --}}

@section('title', 'Register')

@section('content')
     <div class="flex justify-center items-start">
        {{-- Container matching the style of other ChefGenius screens --}}
        <div id="signup-form-container" class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Create Account</h2>

             {{-- Display General Errors (Optional but good practice) --}}
            {{-- @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 border border-red-400 rounded">
                    <ul class="list-disc list-inside text-sm">
                        <li>Please correct the errors below.</li>
                    </ul>
                </div>
            @endif --}}

            <form id="signup-form-element" method="POST" action="{{ route('register') }}">
                 @csrf

                {{-- Name --}}
                <div class="mb-4">
                    <label for="signup-name" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Name') }}</label>
                    <input type="text" id="signup-name" name="name"
                           {{-- Add border-red-500 class if there's an error for this field --}}
                           class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-500 @error('name') border-red-500 @enderror"
                           placeholder="Your Name" value="{{ old('name') }}" required autofocus autocomplete="name">
                    {{-- Display specific error message for 'name' --}}
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email Address --}}
                <div class="mb-4">
                    <label for="signup-email" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Email') }}</label>
                    <input type="email" id="signup-email" name="email"
                           class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-500 @error('email') border-red-500 @enderror"
                           placeholder="your@email.com" value="{{ old('email') }}" required autocomplete="username">
                    {{-- Display specific error message for 'email' --}}
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label for="signup-password" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Password') }}</label>
                    <input type="password" id="signup-password" name="password"
                           class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-500 @error('password') border-red-500 @enderror"
                           placeholder="Minimum 8 characters" required autocomplete="new-password">
                    {{-- Display specific error message for 'password' --}}
                    {{-- Note: Laravel shows password confirmation errors under the 'password' field name by default --}}
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6">
                    <label for="signup-confirm-password" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Confirm Password') }}</label>
                    <input type="password" id="signup-confirm-password" name="password_confirmation" {{-- Name must be field_confirmation --}}
                           class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-500"
                           placeholder="Re-enter password" required autocomplete="new-password">
                    {{-- No separate error needed here, confirmation error shows under 'password' --}}
                </div>

                {{-- Submit Button --}}
                <div class="flex items-center justify-end mt-6">
                     <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <button id="signup-submit-button" class="btn btn-secondary" type="submit">
                        {{ __('Register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
