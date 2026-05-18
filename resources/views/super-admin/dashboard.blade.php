<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Super Admin Dashboard - Kite</title>

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
                <!-- Logo -->
                <div class="flex items-center">
                    <h1 class="text-3xl font-black text-emerald-700">Kite</h1>
                    <span class="ml-4 px-3 py-1 bg-black text-white text-sm font-bold rounded">SUPER ADMIN</span>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('super-admin.dashboard') }}" class="text-lg font-bold text-black hover:text-emerald-700 transition-colors {{ request()->routeIs('super-admin.dashboard') ? 'text-emerald-700' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('super-admin.pending-queue') }}" class="text-lg font-bold text-black hover:text-emerald-700 transition-colors {{ request()->routeIs('super-admin.pending-queue') ? 'text-emerald-700' : '' }}">
                        Pending Queue
                    </a>
                    <a href="{{ route('super-admin.restaurants') }}" class="text-lg font-bold text-black hover:text-emerald-700 transition-colors {{ request()->routeIs('super-admin.restaurants') ? 'text-emerald-700' : '' }}">
                        All Restaurants
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
            <h1 class="text-6xl font-black text-black mb-4">Super Admin Dashboard</h1>
            <p class="text-xl text-gray-600 font-medium">Manage restaurants, approvals, and system overview</p>
        </div>

        <!-- Success Message -->
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

        <!-- Stats Bento Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Total Revenue -->
            <div class="bg-white border-2 border-black p-8 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-black text-black">Total Revenue</h3>
                    <div class="w-12 h-12 bg-emerald-100 border-2 border-black flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-black text-emerald-700">Rs {{ number_format($stats['total_revenue'], 2) }}</p>
                <p class="text-sm font-bold text-gray-600 mt-2">From all subscriptions</p>
            </div>

            <!-- Active Tenants -->
            <div class="bg-white border-2 border-black p-8 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-black text-black">Active Tenants</h3>
                    <div class="w-12 h-12 bg-blue-100 border-2 border-black flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-black text-blue-700">{{ $stats['active_tenants'] }}</p>
                <p class="text-sm font-bold text-gray-600 mt-2">Verified restaurants</p>
            </div>

            <!-- Pending Reviews -->
            <div class="bg-white border-2 border-black p-8 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-black text-black">Pending Reviews</h3>
                    <div class="w-12 h-12 bg-yellow-100 border-2 border-black flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-black text-yellow-700">{{ $stats['pending_reviews'] }}</p>
                <p class="text-sm font-bold text-gray-600 mt-2">Awaiting approval</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Pending Restaurants -->
            <div class="bg-white border-2 border-black p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-black text-black">Recent Pending</h2>
                    <a href="{{ route('super-admin.pending-queue') }}" class="px-4 py-2 bg-yellow-400 border-2 border-black font-bold text-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all">
                        View All
                    </a>
                </div>
                
                @if($pendingRestaurants->count() > 0)
                    <div class="space-y-4">
                        @foreach($pendingRestaurants as $restaurant)
                            <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200">
                                <div>
                                    <h4 class="font-bold text-black">{{ $restaurant->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $restaurant->getOwner()?->email ?? 'No owner' }}</p>
                                    <p class="text-xs text-gray-500">{{ $restaurant->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <form method="POST" action="{{ route('super-admin.pending.approve', $restaurant) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1 bg-emerald-400 border border-black text-xs font-bold text-black hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all">
                                            Approve
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 font-medium">No pending restaurants</p>
                @endif
            </div>

            <!-- Recent Active Restaurants -->
            <div class="bg-white border-2 border-black p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-black text-black">Recently Active</h2>
                    <a href="{{ route('super-admin.restaurants') }}" class="px-4 py-2 bg-emerald-400 border-2 border-black font-bold text-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all">
                        View All
                    </a>
                </div>
                
                @if($recentRestaurants->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentRestaurants as $restaurant)
                            <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200">
                                <div>
                                    <h4 class="font-bold text-black">{{ $restaurant->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $restaurant->getOwner()?->email ?? 'No owner' }}</p>
                                    <p class="text-xs text-gray-500">{{ $restaurant->days_remaining }} days remaining</p>
                                </div>
                                <div class="flex items-center">
                                    @if($restaurant->is_active)
                                        <span class="px-2 py-1 bg-emerald-100 border border-emerald-300 text-xs font-bold text-emerald-800">Active</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 border border-red-300 text-xs font-bold text-red-800">Suspended</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 font-medium">No active restaurants</p>
                @endif
            </div>
        </div>
    </div>
</body>
</html>