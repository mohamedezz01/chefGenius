@extends('layouts.app')

@section('title', 'Your Profile')

@section('content')
    <div class="flex justify-center items-start">
        <div id="profile-screen-container" class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md"> 
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Your Profile</h2>

            <div class="profile-pic-placeholder">
                <span>👤</span>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-gray-600 text-sm font-bold mb-1">Name:</label>
                    <p id="profile-name" class="text-gray-900 text-lg p-3 bg-gray-100 rounded-md">{{ $user->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-gray-600 text-sm font-bold mb-1">Email:</label>
                    <p id="profile-email" class="text-gray-900 text-lg p-3 bg-gray-100 rounded-md">{{ $user->email ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-gray-600 text-sm font-bold mb-1">Joined:</label>
                    <p id="profile-joined" class="text-gray-900 text-lg p-3 bg-gray-100 rounded-md">{{ $user->created_at ? $user->created_at->format('F j, Y') : 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
