<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Restaurant Search Results - Kite</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                    <a href="{{ route('register') }}" class="text-lg font-bold text-black hover:text-emerald-700 transition-colors">
                        Sign Up
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-black text-black mb-4">Search Results</h1>
            <p class="text-xl text-gray-600 font-medium">
                Found {{ $restaurants->count() }} restaurant{{ $restaurants->count() !== 1 ? 's' : '' }} matching "{{ $search }}"
            </p>
        </div>

        <!-- Search Again -->
        <div class="mb-8">
            <form action="{{ route('restaurant.lookup') }}" method="GET" class="flex space-x-3 max-w-md mx-auto">
                <input type="text" name="search" value="{{ $search }}" placeholder="Restaurant name or slug" 
                       class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-black focus:outline-none">
                <button type="submit" 
                        class="px-6 py-3 bg-black text-white font-bold rounded-xl hover:bg-gray-800 transition-colors">
                    Search
                </button>
            </form>
        </div>

        <!-- Results -->
        @if($restaurants->count() > 0)
            <div class="grid gap-6 mb-12">
                @foreach($restaurants as $restaurant)
                    <div class="bg-white border-2 border-black p-6 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="text-2xl font-black text-black mb-2">{{ $restaurant->name }}</h3>
                                <p class="text-gray-600 font-medium mb-1">{{ $restaurant->slug }}.kite.test</p>
                                @if($restaurant->address)
                                    <p class="text-sm text-gray-500">{{ $restaurant->address }}</p>
                                @endif
                                @if($restaurant->phone)
                                    <p class="text-sm text-gray-500">{{ $restaurant->phone }}</p>
                                @endif
                            </div>
                            <div class="flex flex-col space-y-3">
                                <a href="{{ route('staff.register.form', $restaurant) }}" 
                                   class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] border-2 border-black text-center">
                                    Join Team
                                </a>
                                <a href="https://{{ $restaurant->slug }}.kite.test" target="_blank"
                                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-black font-bold rounded-xl transition-all hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] border-2 border-black text-center text-sm">
                                    View Site
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- No Results -->
            <div class="bg-white border-2 border-black p-12 text-center mb-12">
                <div class="w-24 h-24 bg-gray-100 border-2 border-black mx-auto mb-6 flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-3xl font-black text-black mb-4">No restaurants found</h3>
                <p class="text-xl text-gray-600 font-medium mb-8">
                    We couldn't find any restaurants matching "{{ $search }}". Try a different search term or check the spelling.
                </p>
                <div class="space-y-4">
                    <p class="text-gray-600 font-medium">You can also:</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('onboarding.register') }}" 
                           class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] border-2 border-black">
                            Start Your Own Restaurant
                        </a>
                        <a href="{{ route('register') }}" 
                           class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] border-2 border-black">
                            Create General Account
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Back Links -->
        <div class="text-center space-y-4">
            <a href="{{ route('register') }}" class="block text-gray-600 hover:text-emerald-700 font-medium transition-colors">
                ← Back to signup options
            </a>
            <a href="{{ route('home') }}" class="block text-gray-600 hover:text-emerald-700 font-medium transition-colors">
                ← Back to home
            </a>
        </div>
    </div>
</body>
</html>