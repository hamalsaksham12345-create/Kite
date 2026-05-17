<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pending Queue - Kite Super Admin</title>

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
            <h1 class="text-6xl font-black text-black mb-4">Pending Queue</h1>
            <p class="text-xl text-gray-600 font-medium">Review and approve restaurant applications</p>
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

        <!-- Error Message -->
        @if(session('error'))
            <div class="mb-8 p-6 bg-red-50 border-2 border-red-500 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-red-800 font-bold">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Pending Restaurants -->
        @if($pendingRestaurants->count() > 0)
            <div class="space-y-6">
                @foreach($pendingRestaurants as $restaurant)
                    <div class="bg-white border-4 border-black p-8 hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                        <!-- Restaurant Header -->
                        <div class="mb-6">
                            <h2 class="text-4xl font-black text-black mb-2">{{ $restaurant->name }}</h2>
                            <p class="text-lg font-bold text-gray-700">{{ $restaurant->slug }}</p>
                        </div>

                        <!-- Restaurant Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- Owner Information -->
                            <div class="bg-gray-50 border-2 border-gray-300 p-6">
                                <h3 class="text-sm font-black text-black mb-4 uppercase tracking-wide">Owner Information</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-xs font-bold text-gray-600 uppercase">Name</p>
                                        <p class="text-lg font-bold text-black">{{ $restaurant->getOwner()?->name ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-600 uppercase">Email</p>
                                        <p class="text-lg font-bold text-black break-all">{{ $restaurant->getOwner()?->email ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Restaurant Information -->
                            <div class="bg-gray-50 border-2 border-gray-300 p-6">
                                <h3 class="text-sm font-black text-black mb-4 uppercase tracking-wide">Restaurant Information</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-xs font-bold text-gray-600 uppercase">Subscription Plan</p>
                                        <p class="text-lg font-bold text-black capitalize">{{ $restaurant->subscription_plan ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-600 uppercase">Applied On</p>
                                        <p class="text-lg font-bold text-black">{{ $restaurant->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Restaurant Description -->
                        @if($restaurant->description)
                            <div class="mb-8 bg-gray-50 border-2 border-gray-300 p-6">
                                <h3 class="text-sm font-black text-black mb-4 uppercase tracking-wide">Description</h3>
                                <p class="text-base font-medium text-gray-700 leading-relaxed">{{ $restaurant->description }}</p>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <!-- Approve Button -->
                            <form method="POST" action="{{ route('super-admin.pending.approve', $restaurant) }}" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full px-6 py-4 bg-emerald-600 border-4 border-black text-white font-black text-lg uppercase tracking-wide hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:-translate-x-1 hover:-translate-y-1 transition-all duration-200">
                                    Approve Restaurant
                                </button>
                            </form>

                            <!-- Reject Button -->
                            <form method="POST" action="{{ route('super-admin.pending.reject', $restaurant) }}" class="flex-1" onsubmit="return confirm('Are you sure you want to reject this restaurant? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-6 py-4 bg-red-600 border-4 border-black text-white font-black text-lg uppercase tracking-wide hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:-translate-x-1 hover:-translate-y-1 transition-all duration-200">
                                    Reject Restaurant
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($pendingRestaurants->hasPages())
                <div class="mt-12">
                    {{ $pendingRestaurants->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white border-4 border-black p-12 text-center">
                <h2 class="text-3xl font-black text-black mb-4">No Pending Restaurants</h2>
                <p class="text-lg font-medium text-gray-600 mb-8">All restaurant applications have been reviewed. Check back later for new submissions.</p>
                <a href="{{ route('super-admin.dashboard') }}" class="inline-block px-8 py-4 bg-black border-4 border-black text-white font-black text-lg uppercase tracking-wide hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:-translate-x-1 hover:-translate-y-1 transition-all duration-200">
                    Back to Dashboard
                </a>
            </div>
        @endif
    </div>
</body>
</html>
