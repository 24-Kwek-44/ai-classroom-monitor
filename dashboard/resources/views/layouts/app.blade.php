<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- Head content remains the same --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('images/InsightEdu-Logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div x-data="{ sidebarOpen: true }" class="flex min-h-screen">
        
        <!-- Sidebar -->
        <aside class="bg-black text-white transition-all duration-300 flex flex-col flex-shrink-0" 
            :class="sidebarOpen ? 'w-72' : 'w-24'">

            <!-- Logo Section -->
            <div class="mb-8 flex items-center" 
                 :class="sidebarOpen ? 'p-6 justify-start gap-4' : 'py-6 justify-center'">
                <img src="{{ asset('images/InsightEdu-Logo.png') }}" alt="InsightEdu Logo" class="h-12 flex-shrink-0">
                <span x-show="sidebarOpen" class="text-2xl font-bold tracking-widest">INSIGHTEDU</span>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 px-6 py-4 space-y-8">

                {{-- Home Link --}}
                <a href="{{ route('dashboard') }}" class="relative flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white font-semibold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    @if(request()->routeIs('dashboard'))
                        <span class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-[#1DE9B6] to-transparent rounded-r-full"></span>
                    @endif
                    <svg class="h-6 w-6 mr-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span x-show="sidebarOpen">Home</span>
                </a>

                {{-- Concentration Level Link --}}
                <a href="{{ route('concentration') }}" class="relative flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('concentration') ? 'bg-gray-700 text-white font-semibold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    @if(request()->routeIs('concentration'))
                        <span class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-[#1DE9B6] to-transparent rounded-r-full"></span>
                    @endif
                    <svg class="h-6 w-6 mr-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                    <span x-show="sidebarOpen">Concentration Level</span>
                </a>

                {{-- Session Insights Link --}}
                <a href="{{ route('insights') }}" class="relative flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('insights') ? 'bg-gray-700 text-white font-semibold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    @if(request()->routeIs('insights'))
                        <span class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-[#1DE9B6] to-transparent rounded-r-full"></span>
                    @endif
                    <svg class="h-6 w-6 mr-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4"/></svg>
                    <span x-show="sidebarOpen">Session Insights</span>
                </a>
                
                {{-- Engagement Trends Link --}}
                <a href="{{ route('trends') }}" class="relative flex items-center px-4 py-3 rounded-lg transition {{ request()->routeIs('trends') ? 'bg-gray-700 text-white font-semibold' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    @if(request()->routeIs('trends'))
                        <span class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-[#1DE9B6] to-transparent rounded-r-full"></span>
                    @endif
                    <svg class="h-6 w-6 mr-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M13 7h8m0 0V15m0-8l-8 8-4-4-6 6"/></svg>
                    <span x-show="sidebarOpen">Engagement Trends</span>
                </a>
            </nav>

            <!-- Sign Out Button at the bottom -->
            <div class="p-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); this.closest('form').submit();"
                    class="w-full flex items-center px-4 py-3 rounded-lg text-red-500 hover:bg-gray-800 transition">
                        <svg class="h-6 w-6 mr-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1"/></svg>
                        <span x-show="sidebarOpen">Sign Out</span>
                    </a>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-[#1DE9B6] p-4 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-800 hover:bg-cyan-300 p-2 rounded-md">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">INSIGHTEDU</h1>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <svg class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <div class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></div>
                    </div>
                    <img src="https://i.pravatar.cc/32" alt="Profile" class="w-8 h-8 rounded-full">
                </div>
            </header>

            <!-- Page Content Slot -->
            <main class="flex-1 p-6 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>