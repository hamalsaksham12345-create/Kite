<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Join {{ $restaurant->name }} - Kite</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <a href="{{ route('home') }}" class="text-3xl font-black text-emerald-700">Kite</a>
            </div>
            <h2 class="mt-6 text-center text-3xl font-black text-gray-900">
                Join {{ $restaurant->name }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Create your staff account to get started
            </p>
        </div>

        <!-- Restaurant Info -->
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-emerald-50 border-2 border-emerald-200 rounded-2xl p-6 mb-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-emerald-100 border-2 border-emerald-300 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-black text-emerald-900">{{ $restaurant->name }}</h3>
                        <p class="text-sm text-emerald-700 font-medium">{{ $restaurant->slug }}.kite.test</p>
                        @if($restaurant->address)
                            <p class="text-xs text-emerald-600 mt-1">{{ $restaurant->address }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white py-8 px-4 shadow-lg sm:rounded-2xl sm:px-10 border border-gray-100">
                <form method="POST" action="{{ route('staff.register', $restaurant) }}" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input id="name" name="name" type="text" required 
                               value="{{ old('name') }}"
                               class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                               placeholder="Enter your full name">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input id="email" name="email" type="email" required 
                               value="{{ old('email') }}"
                               class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                               placeholder="Enter your email">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Your Role</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <input type="radio" id="waiter" name="role" value="waiter" 
                                       class="sr-only peer" {{ old('role', 'waiter') === 'waiter' ? 'checked' : '' }}>
                                <label for="waiter" class="flex flex-col items-center justify-center p-4 bg-white border-2 border-gray-300 rounded-xl cursor-pointer hover:bg-gray-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all">
                                    <svg class="w-8 h-8 text-gray-400 peer-checked:text-emerald-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm font-bold text-gray-700">Waiter</span>
                                    <span class="text-xs text-gray-500 text-center">Take orders & manage tables</span>
                                </label>
                            </div>
                            <div>
                                <input type="radio" id="chef" name="role" value="chef" 
                                       class="sr-only peer" {{ old('role') === 'chef' ? 'checked' : '' }}>
                                <label for="chef" class="flex flex-col items-center justify-center p-4 bg-white border-2 border-gray-300 rounded-xl cursor-pointer hover:bg-gray-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all">
                                    <svg class="w-8 h-8 text-gray-400 peer-checked:text-emerald-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm font-bold text-gray-700">Chef</span>
                                    <span class="text-xs text-gray-500 text-center">Kitchen display & orders</span>
                                </label>
                            </div>
                        </div>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input id="password" name="password" type="password" required 
                               class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                               placeholder="Create a password">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                               class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                               placeholder="Confirm your password">
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Invite Code (Optional) -->
                    <div>
                        <label for="invite_code" class="block text-sm font-medium text-gray-700 mb-2">
                            Invite Code 
                            <span class="text-gray-500 text-xs">(Optional)</span>
                        </label>
                        <input id="invite_code" name="invite_code" type="text" 
                               value="{{ old('invite_code') }}"
                               class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                               placeholder="Enter invite code if provided">
                        @error('invite_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-emerald-700 hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                            Join {{ $restaurant->name }}
                        </button>
                    </div>
                </form>

                <!-- Links -->
                <div class="mt-6 text-center space-y-2">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="font-medium text-emerald-700 hover:text-emerald-600">
                            Sign in here
                        </a>
                    </p>
                    <a href="{{ route('home') }}" class="block text-sm text-gray-600 hover:text-emerald-700 transition-colors">
                        ← Back to home
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>