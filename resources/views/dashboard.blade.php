<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard - {{ auth()->user()->restaurant->name ?? 'Kite' }}</title>

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
                    <span class="ml-4 px-3 py-1 bg-emerald-100 border border-emerald-300 text-emerald-800 text-sm font-bold rounded">ADMIN</span>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/dashboard" class="text-lg font-bold text-emerald-700">
                        Dashboard
                    </a>
                    <a href="#" class="text-lg font-bold text-black hover:text-emerald-700 transition-colors">
                        Menu
                    </a>
                    <a href="#" class="text-lg font-bold text-black hover:text-emerald-700 transition-colors">
                        Orders
                    </a>
                    <a href="#" class="text-lg font-bold text-black hover:text-emerald-700 transition-colors">
                        Staff
                    </a>
                    
                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
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
            <h1 class="text-6xl font-black text-black mb-4">Restaurant Dashboard</h1>
            <p class="text-xl text-gray-600 font-medium">Welcome back, {{ auth()->user()->name }}!</p>
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

        @if(session('info'))
            <div class="mb-8 p-6 bg-blue-50 border-2 border-blue-500 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-blue-800 font-bold">{{ session('info') }}</p>
                </div>
            </div>
        @endif

        <!-- Restaurant Status -->
        @if(auth()->user()->restaurant)
        <div class="mb-8 p-6 bg-emerald-50 border-2 border-emerald-400">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black text-black">Restaurant Status</h3>
                    <p class="text-emerald-700 font-medium">{{ auth()->user()->restaurant->name }} is {{ auth()->user()->restaurant->is_active ? 'active' : 'inactive' }} and {{ auth()->user()->restaurant->is_verified ? 'verified' : 'pending verification' }}</p>
                    @if(auth()->user()->restaurant->subscription_expires_at)
                    <p class="text-sm text-gray-600 mt-1">
                        Subscription: {{ ucfirst(str_replace('_', ' ', auth()->user()->restaurant->subscription_plan)) }} 
                        ({{ auth()->user()->restaurant->days_remaining }} days remaining)
                    </p>
                    @endif
                </div>
                <div class="flex items-center space-x-4">
                    <span class="px-4 py-2 bg-{{ auth()->user()->restaurant->is_active ? 'emerald' : 'gray' }}-100 border border-{{ auth()->user()->restaurant->is_active ? 'emerald' : 'gray' }}-300 text-{{ auth()->user()->restaurant->is_active ? 'emerald' : 'gray' }}-800 font-bold rounded">
                        {{ auth()->user()->restaurant->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Stats Bento Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- Today's Orders -->
            <div class="bg-white border-2 border-black p-6 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-black text-black">Today's Orders</h3>
                    <div class="w-10 h-10 bg-blue-100 border-2 border-black flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-blue-700">0</p>
                <p class="text-sm font-bold text-gray-600 mt-1">No orders yet</p>
            </div>

            <!-- Revenue -->
            <div class="bg-white border-2 border-black p-6 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-black text-black">Revenue</h3>
                    <div class="w-10 h-10 bg-emerald-100 border-2 border-black flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-emerald-700">Rs 0.00</p>
                <p class="text-sm font-bold text-gray-600 mt-1">Today's total</p>
            </div>

            <!-- Active Staff -->
            <div class="bg-white border-2 border-black p-6 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-black text-black">Active Staff</h3>
                    <div class="w-10 h-10 bg-purple-100 border-2 border-black flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-purple-700">{{ auth()->user()->restaurant ? auth()->user()->restaurant->users->where('is_active', true)->count() : 0 }}</p>
                <p class="text-sm font-bold text-gray-600 mt-1">Team members</p>
            </div>

            <!-- Menu Items -->
            <div class="bg-white border-2 border-black p-6 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-black text-black">Menu Items</h3>
                    <div class="w-10 h-10 bg-yellow-100 border-2 border-black flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-yellow-700">0</p>
                <p class="text-sm font-bold text-gray-600 mt-1">Items available</p>
            </div>
        </div>

        <!-- Getting Started -->
        <div class="bg-white border-2 border-black p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-black text-black">Getting Started</h2>
                <div class="w-12 h-12 bg-emerald-100 border-2 border-black flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center p-6 bg-gray-50 border border-gray-200 rounded">
                    <div class="w-16 h-16 bg-emerald-100 border border-emerald-300 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-emerald-700 font-black text-xl">1</span>
                    </div>
                    <h4 class="font-black text-black mb-2">Set up your menu</h4>
                    <p class="text-sm text-gray-600 mb-4">Add categories and menu items</p>
                    <button class="px-4 py-2 bg-emerald-400 border border-black font-bold text-black hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all text-sm">
                        Start
                    </button>
                </div>

                <div class="text-center p-6 bg-gray-50 border border-gray-200 rounded">
                    <div class="w-16 h-16 bg-blue-100 border border-blue-300 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-blue-700 font-black text-xl">2</span>
                    </div>
                    <h4 class="font-black text-black mb-2">Invite your staff</h4>
                    <p class="text-sm text-gray-600 mb-4">Add waiters and kitchen staff</p>
                    <button class="px-4 py-2 bg-blue-400 border border-black font-bold text-black hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all text-sm">
                        Invite
                    </button>
                </div>

                <div class="text-center p-6 bg-gray-50 border border-gray-200 rounded">
                    <div class="w-16 h-16 bg-purple-100 border border-purple-300 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-purple-700 font-black text-xl">3</span>
                    </div>
                    <h4 class="font-black text-black mb-2">Customize branding</h4>
                    <p class="text-sm text-gray-600 mb-4">Upload logo and set colors</p>
                    <button class="px-4 py-2 bg-purple-400 border border-black font-bold text-black hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all text-sm">
                        Customize
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>