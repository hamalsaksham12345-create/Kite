<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kitchen Display - {{ auth()->user()->restaurant->name ?? 'Kite' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-white">
    <!-- Navigation -->
    <nav class="bg-white border-b-2 border-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo & Restaurant Name -->
                <div class="flex items-center">
                    <h1 class="text-3xl font-black text-emerald-700">{{ auth()->user()->restaurant->name ?? 'Kite' }}</h1>
                    <span class="ml-4 px-3 py-1 bg-red-100 border border-red-300 text-red-800 text-sm font-bold rounded">KITCHEN</span>
                </div>

                <!-- Status & Controls -->
                <div class="hidden md:flex items-center space-x-8">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-emerald-500 rounded-full mr-2 animate-pulse"></div>
                            <span class="text-sm font-bold text-emerald-700">Kitchen Online</span>
                        </div>
                        <button class="px-4 py-2 bg-yellow-400 border-2 border-black font-bold text-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all text-sm">
                            Refresh Orders
                        </button>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }} (Chef)</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-gray-500 hover:text-black transition-colors">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Header -->
        <div class="mb-12">
            <h1 class="text-6xl font-black text-black mb-4">Kitchen Display System</h1>
            <p class="text-xl text-gray-600 font-medium">Manage incoming orders and track preparation status</p>
        </div>

        <!-- Kitchen Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <!-- Pending Orders -->
            <div class="bg-white border-2 border-black p-6 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-black text-black">Pending</h3>
                    <div class="w-10 h-10 bg-red-100 border-2 border-black flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-red-700">0</p>
                <p class="text-sm font-bold text-gray-600 mt-1">New orders</p>
            </div>

            <!-- In Progress -->
            <div class="bg-white border-2 border-black p-6 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-black text-black">Cooking</h3>
                    <div class="w-10 h-10 bg-yellow-100 border-2 border-black flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-yellow-700">0</p>
                <p class="text-sm font-bold text-gray-600 mt-1">In progress</p>
            </div>

            <!-- Ready -->
            <div class="bg-white border-2 border-black p-6 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-black text-black">Ready</h3>
                    <div class="w-10 h-10 bg-emerald-100 border-2 border-black flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-emerald-700">0</p>
                <p class="text-sm font-bold text-gray-600 mt-1">For pickup</p>
            </div>

            <!-- Completed Today -->
            <div class="bg-white border-2 border-black p-6 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-black text-black">Completed</h3>
                    <div class="w-10 h-10 bg-blue-100 border-2 border-black flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-blue-700">0</p>
                <p class="text-sm font-bold text-gray-600 mt-1">Today</p>
            </div>
        </div>

        <!-- Kitchen Display Placeholder -->
        <div class="bg-white border-2 border-black p-8">
            <h2 class="text-3xl font-black text-black mb-6">Kitchen Display System</h2>
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-red-100 border-2 border-black mx-auto mb-6 flex items-center justify-center">
                    <svg class="w-12 h-12 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-black mb-4">Kitchen Display Ready</h3>
                <p class="text-gray-600 font-medium mb-6">Orders will appear here once the POS system starts sending them to the kitchen.</p>
                <div class="flex justify-center space-x-4">
                    <button class="px-6 py-3 bg-emerald-600 border-2 border-black text-white font-black hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all">
                        Kitchen Online
                    </button>
                    <button class="px-6 py-3 bg-yellow-400 border-2 border-black text-black font-black hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all">
                        Refresh Orders
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>