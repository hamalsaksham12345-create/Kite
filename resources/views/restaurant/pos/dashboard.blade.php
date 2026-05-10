<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>POS System - {{ auth()->user()->restaurant->name }}</title>

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
                    <h1 class="text-3xl font-black text-emerald-700">{{ auth()->user()->restaurant->name }}</h1>
                    <span class="ml-4 px-3 py-1 bg-blue-100 border border-blue-300 text-blue-800 text-sm font-bold rounded">POS</span>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('restaurant.pos.dashboard') }}" class="text-lg font-bold text-blue-700">
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
                <p class="text-3xl font-black text-emerald-700">$0.00</p>
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

        <!-- Main POS Interface -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Menu Categories & Items -->
            <div class="lg:col-span-2 bg-white border-2 border-black p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-black text-black">Menu</h2>
                    <div class="flex space-x-2">
                        <button class="px-4 py-2 bg-blue-400 border-2 border-black font-bold text-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all">
                            All Items
                        </button>
                        <button class="px-4 py-2 bg-gray-200 border-2 border-black font-bold text-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all">
                            Appetizers
                        </button>
                        <button class="px-4 py-2 bg-gray-200 border-2 border-black font-bold text-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all">
                            Mains
                        </button>
                    </div>
                </div>

                <!-- Menu Items Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <!-- Sample Menu Items -->
                    <div class="bg-gray-50 border-2 border-gray-300 p-4 hover:border-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all cursor-pointer">
                        <div class="w-full h-24 bg-gray-200 border border-gray-300 rounded mb-3 flex items-center justify-center">
                            <span class="text-gray-400 text-sm">No Image</span>
                        </div>
                        <h4 class="font-bold text-black mb-1">Sample Item</h4>
                        <p class="text-sm text-gray-600 mb-2">Menu setup required</p>
                        <p class="text-lg font-black text-emerald-700">$0.00</p>
                    </div>

                    <!-- Add Menu Item Placeholder -->
                    <div class="bg-gray-50 border-2 border-dashed border-gray-300 p-4 flex flex-col items-center justify-center text-center min-h-[140px]">
                        <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <p class="text-sm font-bold text-gray-500">Add menu items in admin panel</p>
                    </div>
                </div>
            </div>

            <!-- Current Order -->
            <div class="bg-white border-2 border-black p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-black text-black">Current Order</h2>
                    <button class="px-3 py-1 bg-red-400 border border-black font-bold text-black text-sm hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all">
                        Clear
                    </button>
                </div>

                <!-- Table Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-black mb-2">Table Number</label>
                    <select class="w-full px-3 py-2 border-2 border-gray-300 rounded focus:border-black focus:outline-none">
                        <option>Select Table</option>
                        <option>Table 1</option>
                        <option>Table 2</option>
                        <option>Table 3</option>
                        <option>Table 4</option>
                        <option>Table 5</option>
                    </select>
                </div>

                <!-- Order Items -->
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-black mb-3">Order Items</h3>
                    <div class="space-y-2 min-h-[200px]">
                        <!-- Empty state -->
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="font-medium">No items added</p>
                            <p class="text-sm">Select items from the menu</p>
                        </div>
                    </div>
                </div>

                <!-- Order Total -->
                <div class="border-t-2 border-black pt-4 mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-bold text-black">Subtotal:</span>
                        <span class="font-bold text-black">$0.00</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-bold text-black">Tax:</span>
                        <span class="font-bold text-black">$0.00</span>
                    </div>
                    <div class="flex justify-between items-center text-lg border-t border-gray-300 pt-2">
                        <span class="font-black text-black">Total:</span>
                        <span class="font-black text-emerald-700">$0.00</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <button class="w-full py-3 bg-emerald-400 border-2 border-black font-bold text-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all" disabled>
                        Send to Kitchen
                    </button>
                    <button class="w-full py-3 bg-blue-400 border-2 border-black font-bold text-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all" disabled>
                        Save Draft
                    </button>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="mt-12 bg-white border-2 border-black p-8">
            <h2 class="text-3xl font-black text-black mb-6">Recent Orders</h2>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b-2 border-black">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-black text-black">Order #</th>
                            <th class="px-4 py-3 text-left text-sm font-black text-black">Table</th>
                            <th class="px-4 py-3 text-left text-sm font-black text-black">Items</th>
                            <th class="px-4 py-3 text-left text-sm font-black text-black">Total</th>
                            <th class="px-4 py-3 text-left text-sm font-black text-black">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-black text-black">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="font-medium">No orders yet</p>
                                    <p class="text-sm">Orders will appear here once you start taking them</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>