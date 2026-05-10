<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pending Queue - Super Admin - Kite</title>

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
                    <a href="{{ route('super-admin.pending-queue') }}" class="text-lg font-bold text-emerald-700">
                        Pending Queue
                    </a>
                    <a href="{{ route('super-admin.restaurants') }}" class="text-lg font-bold text-black hover:text-emerald-700 transition-colors">
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
            <h1 class="text-6xl font-black text-black mb-4">Verification Queue</h1>
            <p class="text-xl text-gray-600 font-medium">Review and approve pending restaurant applications</p>
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
        <div class="mb-8 p-6 bg-yellow-50 border-2 border-yellow-400">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black text-black">{{ $pendingRestaurants->total() }} Pending Applications</h3>
                    <p class="text-gray-600 font-medium">Awaiting your review and approval</p>
                </div>
                <div class="w-16 h-16 bg-yellow-400 border-2 border-black flex items-center justify-center">
                    <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Restaurants Table -->
        @if($pendingRestaurants->count() > 0)
            <div class="bg-white border-2 border-black overflow-hidden">
                <!-- Table Header -->
                <div class="bg-gray-100 border-b-2 border-black p-6">
                    <h2 class="text-3xl font-black text-black">Pending Applications</h2>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b-2 border-black">
                            <tr>
                                <th class="px-6 py-4 text-left text-lg font-black text-black border-r border-gray-300">Restaurant</th>
                                <th class="px-6 py-4 text-left text-lg font-black text-black border-r border-gray-300">Owner</th>
                                <th class="px-6 py-4 text-left text-lg font-black text-black border-r border-gray-300">Slug</th>
                                <th class="px-6 py-4 text-left text-lg font-black text-black border-r border-gray-300">Plan</th>
                                <th class="px-6 py-4 text-left text-lg font-black text-black border-r border-gray-300">Submitted</th>
                                <th class="px-6 py-4 text-left text-lg font-black text-black">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-gray-200">
                            @foreach($pendingRestaurants as $restaurant)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-6 border-r border-gray-200">
                                        <div>
                                            <h4 class="text-lg font-bold text-black">{{ $restaurant->name }}</h4>
                                            @if($restaurant->phone)
                                                <p class="text-sm text-gray-600 font-medium">{{ $restaurant->phone }}</p>
                                            @endif
                                            @if($restaurant->address)
                                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($restaurant->address, 50) }}</p>
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
                                        <div class="flex items-center">
                                            <code class="px-2 py-1 bg-gray-100 border border-gray-300 text-sm font-bold text-black">{{ $restaurant->slug }}</code>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">kite.test/{{ $restaurant->slug }}</p>
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
                                            <p class="text-sm font-bold text-black">{{ $restaurant->created_at->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-500">{{ $restaurant->created_at->diffForHumans() }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="flex space-x-3">
                                            <!-- Approve Button -->
                                            <form method="POST" action="{{ route('super-admin.approve', $restaurant) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to approve {{ $restaurant->name }}?')"
                                                        class="px-4 py-2 bg-emerald-400 border-2 border-black font-bold text-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all">
                                                    ✓ Approve
                                                </button>
                                            </form>

                                            <!-- Reject Button -->
                                            <button type="button" 
                                                    x-data="{ showRejectModal: false }"
                                                    @click="showRejectModal = true"
                                                    class="px-4 py-2 bg-red-400 border-2 border-black font-bold text-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all">
                                                ✗ Reject
                                            </button>

                                            <!-- Reject Modal -->
                                            <div x-data="{ showRejectModal: false }" x-show="showRejectModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                                                <div class="flex items-center justify-center min-h-screen px-4">
                                                    <div class="fixed inset-0 bg-black opacity-50" @click="showRejectModal = false"></div>
                                                    <div class="bg-white border-2 border-black p-8 max-w-md w-full relative">
                                                        <h3 class="text-2xl font-black text-black mb-4">Reject {{ $restaurant->name }}?</h3>
                                                        <form method="POST" action="{{ route('super-admin.reject', $restaurant) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="mb-4">
                                                                <label class="block text-sm font-bold text-black mb-2">Reason (Optional)</label>
                                                                <textarea name="reason" rows="3" class="w-full px-3 py-2 border-2 border-gray-300 focus:border-black focus:outline-none" placeholder="Provide a reason for rejection..."></textarea>
                                                            </div>
                                                            <div class="flex space-x-3">
                                                                <button type="submit" class="px-4 py-2 bg-red-400 border-2 border-black font-bold text-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all">
                                                                    Confirm Reject
                                                                </button>
                                                                <button type="button" @click="showRejectModal = false" class="px-4 py-2 bg-gray-200 border-2 border-black font-bold text-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all">
                                                                    Cancel
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($pendingRestaurants->hasPages())
                    <div class="border-t-2 border-black p-6">
                        {{ $pendingRestaurants->links() }}
                    </div>
                @endif
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white border-2 border-black p-12 text-center">
                <div class="w-24 h-24 bg-gray-100 border-2 border-black mx-auto mb-6 flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-3xl font-black text-black mb-4">All Caught Up!</h3>
                <p class="text-xl text-gray-600 font-medium">No pending restaurant applications to review.</p>
            </div>
        @endif
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>