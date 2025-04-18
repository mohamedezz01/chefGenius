<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ChefGenius')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('head-scripts')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" data-is-authenticated="{{ Auth::check() ? 'true' : 'false' }}">
    <header class="bg-white border-b border-gray-200 text-gray-800 p-4 shadow-sm flex justify-between items-center sticky top-0 z-40">
        {{-- Logo and Title --}}
        <div class="flex items-center gap-2">
             <a href="{{ auth()->check() ? route('home') : route('login') }}" class="flex items-center gap-2">
                 <svg class="w-8 h-8 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L1.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L24 5.25l-.813 2.846a4.5 4.5 0 0 0-3.09 3.09L18.25 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09L12 18.75l.813-2.846a4.5 4.5 0 0 0 3.09-3.09L18.25 12Z" /></svg>
                <h1 class="text-2xl font-bold text-emerald-600">ChefGenius</h1>
             </a>
        </div>
       
            <div class="dropdown" id="account-dropdown">
                <button id="account-button" class="btn bg-gray-100 text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 inline-flex items-center shadow-none">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                    <span>Account</span>
                    <svg class="ml-2 w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                </button>
                <div class="dropdown-content" id="dropdown-menu">
                    {{-- Items populated by JS --}}
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto py-10 px-4 flex-grow">
        @yield('content')
    </main>

    <div id="message-box">
        <p id="message-text"></p>
    </div>

    <footer class="bg-gray-800 text-gray-300 text-center p-5 mt-auto">
        <p>© <span id="current-year">{{ date('Y') }}</span> ChefGenius. Cook something amazing!</p>
    </footer>

    <script src="{{ asset('js/script.js') }}" defer></script>
    @yield('footer-scripts')
</body>
</html>
