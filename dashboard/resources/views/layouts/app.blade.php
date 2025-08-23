<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- Head content remains the same --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('InsightEdu-Logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="font-sans antialiased bg-gray-100">
    {{-- 1. Initialize Alpine.js state. 'sidebarOpen' is true by default on large screens --}}
    <div x-data="{ sidebarOpen: window.innerWidth > 1024 }" class="flex h-screen bg-gray-100">
        
        <!-- Sidebar -->
        <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" 
            class="w-72 bg-black text-white flex flex-col flex-shrink-0 absolute lg:relative z-20">

            <!-- Logo Section -->
            <div class="p-8 text-center flex flex-col items-center">
                <img src="{{ asset('images/InsightEdu-Logo.png') }}" alt="InsightEdu Logo" class="h-20 mb-2">
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 px-6 py-4 space-y-8">
                {{-- Active Link --}}
                <a href="#" class="relative flex items-center px-4 py-3 rounded-lg bg-gray-700 text-white font-semibold">
                    {{-- Gradient Glow Effect --}}
                    <span class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-[#1DE9B6] to-transparent rounded-r-full"></span>
                    <svg class="h-6 w-6 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Home</span>
                </a>
                
                {{-- Inactive Link Example --}}
                <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition">
                    <svg class="h-6 w-6 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" /></svg>
                    <span>Concentration Level</span>
                </a>
                <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition">
                    <svg class="h-6 w-6 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4" /></svg>
                    <span>Session Insights</span>
                </a>
                <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-white transition">
                    <svg class="h-6 w-6 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0V15m0-8l-8 8-4-4-6 6" /></svg>
                    <span>Engagement Trends</span>
                </a>
            </nav>

            <!-- Sign Out Button at the bottom -->
            <div class="p-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); this.closest('form').submit();"
                    class="flex items-center px-4 py-3 rounded-lg text-red-500 hover:bg-gray-800 transition">
                        <svg class="h-6 w-6 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1" /></svg>
                        <span>Sign Out</span>
                    </a>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-[#1DE9B6] p-4 flex justify-between items-center">
                <div class="flex items-center">
                    {{-- 3. The hamburger button will toggle the 'sidebarOpen' state --}}
                    <button @click="sidebarOpen = !sidebarOpen" class="text-black focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <h2 class="ml-4 text-2xl font-bold text-black">INSIGHTEDU</h2>
                </div>
                {{-- Profile and Bell Icons remain the same --}}
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <svg class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
                    </div>
                    <img src="https://i.pravatar.cc/40" alt="Profile" class="h-10 w-10 rounded-full border-2 border-white">
                </div>
            </header>

            <!-- Page Content Slot -->
            <div class="flex-1 p-6 overflow-y-auto">
                {{ $slot }}
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>