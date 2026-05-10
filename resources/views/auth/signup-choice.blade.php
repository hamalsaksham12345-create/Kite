<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Get Started - Kite Restaurant SaaS</title>

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
                    <a href="{{ route('home') }}">
                        <h1 class="text-3xl font-black text-emerald-700">Kite</h1>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('login') }}" class="text-lg font-bold text-black hover:text-emerald-700 transition-colors">
                        Sign In
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-6xl font-black text-black mb-6">Choose Your Path</h1>
            <p class="text-xl text-gray-600 font-medium max-w-2xl mx-auto">
                Are you starting a new restaurant or joining an existing team?
            </p>
        </div>

        <!-- Signup Options -->
        <div class="grid md:grid-cols-2 gap-8 mb-12">
            <!-- Restaurant Owner -->
            <div class="bg-white border-2 border-black p-8 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200 group">
                <div class="text-center">
                    <div class="w-20 h-20 bg-emerald-100 border-2 border-black mx-auto mb-6 flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                        <svg class="w-10 h-10 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    
                    <h2 class="text-3xl font-black text-black mb-4">Restaurant Owner</h2>
                    <p class="text-gray-600 font-medium mb-8 leading-relaxed">
                        Launch your restaurant on Kite with a complete POS system, kitchen display, and branded webapp.
                    </p>
                    
                    <div class="space-y-3 mb-8 text-left">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-bold text-gray-700">Complete restaurant setup</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-bold text-gray-700">Custom branding & domain</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-bold text-gray-700">Staff management tools</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-bold text-gray-700">Analytics & reporting</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('onboarding.register') }}" 
                       class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-4 px-6 rounded-xl text-lg font-bold transition-all hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] border-2 border-black inline-block text-center">
                        Start Your Restaurant
                    </a>
                </div>
            </div>

            <!-- Staff Member -->
            <div class="bg-white border-2 border-black p-8 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200 group">
                <div class="text-center">
                    <div class="w-20 h-20 bg-blue-100 border-2 border-black mx-auto mb-6 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <svg class="w-10 h-10 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    
                    <h2 class="text-3xl font-black text-black mb-4">Staff Member</h2>
                    <p class="text-gray-600 font-medium mb-8 leading-relaxed">
                        Join an existing restaurant team as a waiter, chef, or other staff member.
                    </p>
                    
                    <div class="space-y-3 mb-8 text-left">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-bold text-gray-700">POS system access</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-bold text-gray-700">Kitchen display system</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-bold text-gray-700">Order management</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-bold text-gray-700">Role-based permissions</span>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <a href="{{ route('register') }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 px-6 rounded-xl text-lg font-bold transition-all hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] border-2 border-black inline-block text-center">
                            Join as Staff
                        </a>
                        <p class="text-xs text-gray-500">
                            Have a restaurant invite link? Use that instead.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Restaurant Lookup -->
        <div class="bg-gray-50 border-2 border-gray-300 p-8 rounded-xl" x-data="{ showLookup: false }">
            <div class="text-center">
                <h3 class="text-2xl font-black text-black mb-4">Looking for a specific restaurant?</h3>
                <p class="text-gray-600 font-medium mb-6">
                    If you have a restaurant's invite link or know their name, you can join directly.
                </p>
                
                <button @click="showLookup = !showLookup" 
                        class="px-6 py-3 bg-gray-200 border-2 border-black font-bold text-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all">
                    Find Restaurant
                </button>

                <div x-show="showLookup" x-transition class="mt-6 max-w-md mx-auto">
                    <form action="{{ route('restaurant.lookup') }}" method="GET" class="flex space-x-3">
                        <input type="text" name="search" placeholder="Restaurant name or slug" 
                               class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-black focus:outline-none">
                        <button type="submit" 
                                class="px-6 py-3 bg-black text-white font-bold rounded-xl hover:bg-gray-800 transition-colors">
                            Search
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-12">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-emerald-700 font-medium transition-colors">
                ← Back to home
            </a>
        </div>
    </div>
</body>
</html>