@extends('layouts.app') {{-- Use main layout --}}

@section('title', 'Verify Email')

@section('content')
    <div class="flex justify-center items-start">
        <div id="confirm-email-container" class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md text-center">
             <svg class="w-16 h-16 text-emerald-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
             </svg>
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">Enter Verification Code</h2>

            {{-- Display status message from session (e.g., after registration or resend) --}}
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded border border-green-200">
                    {{ session('status') }}
                </div>
            @endif

             {{-- Display validation errors (e.g., incorrect OTP) --}}
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 border border-red-400 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <p class="text-gray-600 mb-6">
                {{-- Get email from session passed by controller, or show default --}}
                We've sent a 6-digit code to <strong id="confirmation-email-address">{{ session('email_for_otp_verification', 'your email address') }}</strong>. Please enter it below.
            </p>

            {{-- CORRECTED: Point form action to the 'verification.verify' route --}}
            <form id="otp-form-element" method="POST" action="{{ route('verification.verify') }}" class="mb-6">
                 @csrf {{-- CSRF Protection --}}
                 <div>
                     <label for="otp-input" class="sr-only">Verification Code</label>
                     <input
                        type="text"
                        id="otp-input"
                        name="otp" {{-- Name matches validation key --}}
                        maxlength="6"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        required
                        placeholder="Enter 6-digit code"
                        class="shadow-sm appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-500 text-center text-2xl tracking-[0.5em] @error('otp') border-red-500 @enderror"
                        autofocus
                     >
                 </div>
                 <button id="verify-otp-button" class="btn btn-primary w-full mt-4" type="submit">
                     Verify Account
                 </button>
            </form>

            <div class="space-y-2">
                 {{-- Resend Form - Point to resend route --}}
                 <form method="POST" action="{{ route('verification.send') }}">
                     @csrf
                     <button id="resend-otp-button" class="btn btn-link text-sm" type="submit">
                         Didn't receive code? Resend OTP
                     </button>
                 </form>

                 {{-- Link back to login --}}
                 <a href="{{ route('login') }}" class="btn btn-link text-sm text-gray-600 hover:text-gray-800">
                     Back to Login
                 </a>
            </div>
        </div>
    </div>
@endsection
