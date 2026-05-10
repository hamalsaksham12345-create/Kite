<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>All Restaurants - Super Admin - Kite</title>

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
                    <a href="{{ route('super-admin.dashboard') }}">
                        <h1 class="text-3xl font-black text-emerald-700">Kite</h1>
                    </a>
                    <span class="ml-4 px-3 py-1 bg-black text-white text-sm font-bold rounded">SUPER ADMIN</span>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('super-admin.dashboard') }}" class="text-lg font-bold text-black hover:text-emerald-700 transition-colors">
                        Dashboard
                    </a>
                    <a href="{{ route('super-admin.pending-queue') }}" class="text-lg font-bold text-black hover:text-emerald-700 transition-colors">
                        Pending Queue
                    </a>
                    <a href="{{ route('super-admin.restaurants') }}" class="text-lg font-bold text-emerald-700">
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
            <h1 class="text-6xl font-black text-black mb-4">All Restaurants</h1>
            <p class="text-xl text-gray-600 font-medium">Manage active restaurants and their subscriptions</p>
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

        <!-- Stats -->
        <div class="mb-8 p-6 bg-emerald-50 border-2 border-emerald-400">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black text-black">{{ $restaurants->total() }} Active Restaurants</h3>
                    <p class="text-gray-600 font-medium">Verified and managing their operations</p>
                </div>
                <div class="w-16 h-16 bg-emerald-400 border-2 border-black flex items-center justify-center">
                    <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Restaurants Table -->
        @if($restaurants->count() > 0)
            <div class="bg-white border-2 border-black overflow-hidden">
                <!-- Table Header -->
                <div class="bg-gray-100 border-b-2 border-black p-6">
                    <h2 class="text-3xl font-black text-black">Active Restaurants</h2>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b-2 border-black">
                            <tr>
                                <th class="px-6 py-4 text-left text-lg font-black text-black border-r border-gray-300">Restaurant</th>
                                <th class="px-6 py-4 text-left text-lg font-black text-black border-r border-gray-300">Owner</th>
                                <th class="px-6 py-4 text-left text-lg font-black text-black border-r border-gray-300">Plan</th>
                                <th class="px-6 py-4 text-left text-lg font-black text-black border-r border-gray-300">Days Remaining</th>
                                <th class="px-6 py-4 text-left text-lg font-black text-black border-r border-gray-300">Status</th>
                                <th class="px-6 py-4 text-left text-lg font-black text-black">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-gray-200">
                            @foreach($restaurants as $restaurant)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-6 border-r border-gray-200">
                                        <div>
                                            <h4 class="text-lg font-bold text-black">{{ $restaurant->name }}</h4>
                                            <p class="text-sm text-gray-600 font-medium">{{ $restaurant->slug }}</p>
                                            @if($restaurant->phone)
                                                <p class="text-xs text-gray-500 mt-1">{{ $restaurant->phone }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 border-r border-gray-200">
                                        @php $owner = $restaurant->getOwner(); @endphp
                                        @if($owner)
                                            <div>
                                                <p class="text-lg font-bold text-black">{{ $owner->name }}</p>
                                                <p class="text-sm text-gray-600 font-medium">{{ $owner->email }}</p>
                                            </div>
                                        @else
                                            <span class="text-red-500 font-bold">No Owner</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-6 border-r border-gray-200">
                                        <div>
                                            <span class="px-3 py-1 bg-blue-100 border border-blue-300 text-sm font-bold text-blue-800 rounded">
                                                {{ ucfirst(str_replace('_', ' ', $restaurant->subscription_plan)) }}
                                            </span>
                                            @if($restaurant->subscription_amount)
                                                <p class="text-sm text-gray-600 font-bold mt-1">${{ number_format($restaurant->subscription_amount, 2) }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 border-r border-gray-200">
                                        <div>
                                            @php $daysRemaining = $restaurant->days_remaining; @endphp
                                            <p class="text-lg font-bold {{ $daysRemaining <= 7 ? 'text-red-600' : ($daysRemaining <= 30 ? 'text-yellow-600' : 'text-emerald-600') }}">
                                                {{ $daysRemaining }} days
                                            </p>
                                            @if($restaurant->subscription_expires_at)
                                                <p class="text-xs text-gray-500">Expires {{ $restaurant->subscription_expires_at->format('M d, Y') }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 border-r border-gray-200">
                                        <div class="flex flex-col space-y-2">
                                            @if($restaurant->is_active)
                                                <span class="px-3 py-1 bg-emerald-100 border border-emerald-300 text-sm font-bold text-emerald-800 rounded text-center">
                                                    Active
                                                </span>
                                            @else
                                                <span class="px-3 py-1 bg-red-100 border border-red-300 text-sm font-bold text-red-800 rounded text-center">
                                                    Suspended
                                                </span>
                                            @endif
                                            
                                            @if($restaurant->isSubscriptionExpired())
                                                <span class="px-3 py-1 bg-yellow-100 border border-yellow-300 text-xs font-bold text-yellow-800 rounded text-center">
                                                    Expired
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="flex flex-col space-y-2">
                                            @if($restaurant->is_active)
                                                <!-- Suspend Button -->
                                                <form method="POST" action="{{ route('super-admin.suspend', $restaurant) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            onclick="return confirm('Are you sure you want to suspend {{ $restaurant->name }}?')"
                                                            class="w-full px-3 py-2 bg-red-400 border-2 border-black font-bold text-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all text-sm">
                                                        Suspend
                                                    </button>
                                                </form>
                                            @else
                                                <!-- Reactivate Button -->
                                                <form method="POST" action="{{ route('super-admin.reactivate', $restaurant) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            onclick="return confirm('Are you sure you want to reactivate {{ $restaurant->name }}?')"
                                                            class="w-full px-3 py-2 bg-emerald-400 border-2 border-black font-bold text-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all text-sm">
                                                        Reactivate
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- View Details Button -->
                                            <a href="https://{{ $restaurant->slug }}.kite.test" target="_blank" 
                                               class="w-full px-3 py-2 bg-blue-400 border-2 border-black font-bold text-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all text-sm text-center">
                                                View Site
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($restaurants->hasPages())
                    <div class="border-t-2 border-black p-6">
                        {{ $restaurants->links() }}
                    </div>
                @endif
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white border-2 border-black p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 border-2 border-black mx-auto mb-6 flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-3xl font-black text-black mb-4">No Active Restaurants</h3>
                <p class="text-xl text-gray-600 font-medium">No restaurants have been approved yet.</p>
            </div>
        @endif
    </div>
</body>
</html>