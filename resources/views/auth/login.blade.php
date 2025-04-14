@extends('layouts.app') {{-- Use the main app layout --}}

@section('title', 'Login')

@section('content')
    <div class="flex justify-center items-start">
        <div id="login-form-container" class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Welcome Back!</h2>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-400 rounded">
                    @if ($errors->has('email') && count($errors->all()) == 1)
                         <p class="text-sm">{{ $errors->first('email') }}</p>
                    @else
                        <ul class="list-disc list-inside text-sm">
                            <li>Please correct the errors below.</li>
                        </ul>
                    @endif
                </div>
            @endif
            <form id="login-form-element" method="POST" action="{{ url('/login') }}">
                @csrf {{-- CSRF Protection --}}

                <div class="mb-4">
                    <label for="login-email" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Email') }}</label>
                    <input type="email" id="login-email" name="email"
                           class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-500 @error('email') border-red-500 @enderror"
                           placeholder="your@email.com" value="{{ old('email') }}" required autofocus autocomplete="username">
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="login-password" class="block text-gray-700 text-sm font-bold mb-2">{{ __('Password') }}</label>
                    <input type="password" id="login-password" name="password"
                           class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-500 @error('password') border-red-500 @enderror"
                           placeholder="************" required autocomplete="current-password">
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    @if (Route::has('password.request')) 
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <button class="btn btn-primary" type="submit">
                        {{ __('Log in') }}
                    </button>
                </div>
                 <div class="text-center mt-6">
                     <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('register') }}">
                        {{ __('Don\'t have an account? Register') }}
                    </a>
                 </div>
            </form>
        </div>
    </div>
@endsection
