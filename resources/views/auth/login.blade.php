<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sign In - Kite Restaurant SaaS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-white">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <a href="{{ route('home') }}" class="text-4xl font-black text-emerald-700">Kite</a>
            </div>
            <h2 class="mt-8 text-center text-5xl font-black text-black">
                Welcome back
            </h2>
            <p class="mt-4 text-center text-lg font-medium text-gray-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-bold text-emerald-700 hover:text-emerald-600 underline">
                    Get started here
                </a>
            </p>
        </div>

        <!-- Form -->
        <div class="mt-12 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-10 px-8 border-2 border-black hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <form method="POST" action="{{ route('login') }}" class="space-y-8">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-lg font-bold text-black mb-3">Email Address</label>
                        <input id="email" name="email" type="email" required 
                               value="{{ old('email') }}"
                               class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium"
                               placeholder="Enter your email">
                        @error('email')
                            <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-lg font-bold text-black mb-3">Password</label>
                        <input id="password" name="password" type="password" required 
                               class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium"
                               placeholder="Enter your password">
                        @error('password')
                            <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" 
                                   class="h-5 w-5 text-emerald-600 focus:ring-emerald-500 border-2 border-black">
                            <label for="remember" class="ml-3 block text-lg font-bold text-black">
                                Remember me
                            </label>
                        </div>

                        <div class="text-lg">
                            <a href="#" class="font-bold text-emerald-700 hover:text-emerald-600 underline">
                                Forgot password?
                            </a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="group relative w-full flex justify-center py-4 px-6 border-2 border-black text-lg font-black text-white bg-emerald-600 hover:bg-emerald-700 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                            Sign In
                        </button>
                    </div>
                </form>

                <!-- Divider -->
                <div class="mt-10">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t-2 border-black"></div>
                        </div>
                        <div class="relative flex justify-center text-lg">
                            <span class="px-4 bg-white font-bold text-black">New to Kite?</span>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('register') }}" 
                           class="w-full flex justify-center py-4 px-6 border-2 border-black text-lg font-black text-black bg-white hover:bg-gray-50 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                            Create Account
                        </a>
                    </div>
                </div>

                <!-- Back to Home -->
                <div class="mt-8 text-center">
                    <a href="{{ route('home') }}" class="text-lg font-bold text-gray-600 hover:text-emerald-700 transition-colors">
                        ← Back to home
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>