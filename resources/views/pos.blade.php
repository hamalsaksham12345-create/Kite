<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>POS System - {{ auth()->user()->restaurant->name ?? 'Kite' }}</title>

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
                    <span class="ml-4 px-3 py-1 bg-blue-100 border border-blue-300 text-blue-800 text-sm font-bold rounded">POS</span>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/pos" class="text-lg font-bold text-blue-700">
                        POS System
                    </a>
                    <a href="#" class="text-lg font-bold text-black hover:text-blue-700 transition-colors">
                        Orders
                    </a>
                    <a href="#" class="text-lg font-bold text-black hover:text-blue-700 transition-colors">
                        Tables
                    </a>
                    
                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }} (Waiter)</span>
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
            <h1 class="text-6xl font-black text-black mb-4">POS System</h1>
            <p class="text-xl text-gray-600 font-medium">Take orders and manage tables efficiently</p>
        </div>

        <!-- Success/Info Messages -->
        @if(session('success'))
            <div class="mb-8 p-6 bg-emerald-50 border-2 border-emerald-500 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-emerald-800 font-bold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <!-- Active Orders -->
            <div class="bg-white border-2 border-black p-6 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-black text-black">Active Orders</h3>
                    <div class="w-10 h-10 bg-red-100 border-2 border-black flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-red-700">0</p>
                <p class="text-sm font-bold text-gray-600 mt-1">In progress</p>
            </div>

            <!-- Tables Occupied -->
            <div class="bg-white border-2 border-black p-6 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-black text-black">Tables</h3>
                    <div class="w-10 h-10 bg-blue-100 border-2 border-black flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-blue-700">0/10</p>
                <p class="text-sm font-bold text-gray-600 mt-1">Occupied</p>
            </div>

            <!-- Today's Sales -->
            <div class="bg-white border-2 border-black p-6 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-black text-black">Today's Sales</h3>
                    <div class="w-10 h-10 bg-emerald-100 border-2 border-black flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-emerald-700">Rs 0.00</p>
                <p class="text-sm font-bold text-gray-600 mt-1">Revenue</p>
            </div>

            <!-- My Orders -->
            <div class="bg-white border-2 border-black p-6 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-black text-black">My Orders</h3>
                    <div class="w-10 h-10 bg-purple-100 border-2 border-black flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-purple-700">0</p>
                <p class="text-sm font-bold text-gray-600 mt-1">Today</p>
            </div>
        </div>

        <!-- POS Interface Placeholder -->
        <div class="bg-white border-2 border-black p-8">
            <h2 class="text-3xl font-black text-black mb-6">Point of Sale Interface</h2>
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-blue-100 border-2 border-black mx-auto mb-6 flex items-center justify-center">
                    <svg class="w-12 h-12 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-black mb-4">POS System Ready</h3>
                <p class="text-gray-600 font-medium mb-6">The full POS interface will be available once menu items are configured.</p>
                <button class="px-6 py-3 bg-blue-600 border-2 border-black text-white font-black hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all">
                    Configure Menu
                </button>
            </div>
        </div>
    </div>
</body>
</html>