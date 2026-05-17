<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Restaurants Management - Kite Super Admin</title>

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
            <h1 class="text-6xl font-black text-black mb-4">Restaurants Management</h1>
            <p class="text-xl text-gray-600 font-medium">Monitor and manage all active and suspended restaurants</p>
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

        <!-- Metrics Bento Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Total Registered Platforms -->
            <div class="bg-white border-4 border-black p-8 hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <h3 class="text-sm font-black text-black mb-4 uppercase tracking-wide">Total Registered</h3>
                <p class="text-6xl font-black text-black mb-2">{{ $restaurants->total() }}</p>
                <p class="text-sm font-bold text-gray-600">Platforms</p>
            </div>

            <!-- Active Subscriptions -->
            <div class="bg-white border-4 border-black p-8 hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <h3 class="text-sm font-black text-black mb-4 uppercase tracking-wide">Active Subscriptions</h3>
                <p class="text-6xl font-black text-emerald-600 mb-2">{{ $restaurants->where('is_active', true)->count() }}</p>
                <p class="text-sm font-bold text-gray-600">Running</p>
            </div>

            <!-- Suspended Accounts -->
            <div class="bg-white border-4 border-black p-8 hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <h3 class="text-sm font-black text-black mb-4 uppercase tracking-wide">Suspended Accounts</h3>
                <p class="text-6xl font-black text-red-600 mb-2">{{ $restaurants->where('is_active', false)->count() }}</p>
                <p class="text-sm font-bold text-gray-600">Inactive</p>
            </div>
        </div>

        <!-- Restaurants Table -->
        <div class="bg-white border-4 border-black overflow-hidden">
            <!-- Table Header -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-4 border-black bg-gray-100">
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase tracking-wide">Logo</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase tracking-wide">Restaurant Name</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase tracking-wide">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase tracking-wide">Subscription Expires</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($restaurants as $restaurant)
                            <tr class="border-b-2 border-gray-300 hover:bg-gray-50 transition-colors">
                                <!-- Logo Placeholder -->
                                <td class="px-6 py-4">
                                    <div class="w-12 h-12 bg-gray-200 border-2 border-black flex items-center justify-center">
                                        <span class="text-xs font-black text-gray-600">LOGO</span>
                                    </div>
                                </td>

                                <!-- Restaurant Name -->
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-lg font-black text-black">{{ $restaurant->name }}</p>
                                        <p class="text-sm font-medium text-gray-600">{{ $restaurant->slug }}</p>
                                    </div>
                                </td>

                                <!-- Status Badge -->
                                <td class="px-6 py-4">
                                    @if($restaurant->is_active)
                                        <span class="inline-block px-4 py-2 bg-emerald-100 border-2 border-emerald-600 text-emerald-800 font-black text-sm uppercase">Active</span>
                                    @else
                                        <span class="inline-block px-4 py-2 bg-red-100 border-2 border-red-600 text-red-800 font-black text-sm uppercase">Suspended</span>
                                    @endif
                                </td>

                                <!-- Subscription Expiration Date -->
                                <td class="px-6 py-4">
                                    <div>
                                        @if($restaurant->subscription_expires_at)
                                            <p class="text-base font-bold text-black">{{ $restaurant->subscription_expires_at->format('M d, Y') }}</p>
                                            <p class="text-xs font-medium text-gray-600">
                                                @if($restaurant->subscription_expires_at->isFuture())
                                                    {{ $restaurant->subscription_expires_at->diffForHumans() }}
                                                @else
                                                    Expired
                                                @endif
                                            </p>
                                        @else
                                            <p class="text-base font-bold text-gray-500">N/A</p>
                                        @endif
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4">
                                    @if($restaurant->is_active)
                                        <form method="POST" action="{{ route('super-admin.suspend', $restaurant) }}" class="inline" onsubmit="return confirm('Are you sure you want to suspend this restaurant?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-4 py-2 bg-red-600 border-2 border-black text-white font-black text-sm uppercase hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all duration-200">
                                                Suspend
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('super-admin.reactivate', $restaurant) }}" class="inline" onsubmit="return confirm('Are you sure you want to reactivate this restaurant?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-4 py-2 bg-emerald-600 border-2 border-black text-white font-black text-sm uppercase hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:-translate-x-0.5 hover:-translate-y-0.5 transition-all duration-200">
                                                Reactivate
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="text-lg font-bold text-gray-600">No restaurants found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($restaurants->hasPages())
            <div class="mt-12">
                {{ $restaurants->links() }}
            </div>
        @endif
    </div>
</body>
</html>
