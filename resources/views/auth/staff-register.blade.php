<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Join {{ $restaurant->name }} - Staff Registration</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <!-- Header Block -->
            <div class="bg-black text-white p-8 border-4 border-black mb-8">
                <h1 class="text-4xl font-black mb-2">Join Staff Team</h1>
                <p class="text-xl font-bold">{{ $restaurant->name }}</p>
            </div>

            <!-- Registration Form Card -->
            <div class="bg-white border-4 border-black p-8">
                <form method="POST" action="{{ route('staff.register', $restaurant->slug) }}">
                    @csrf

                    <!-- Name Field -->
                    <div class="mb-8">
                        <label for="name" class="block text-sm font-black text-black uppercase tracking-widest mb-3">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                            class="w-full px-4 py-3 border-4 border-black font-bold text-black placeholder-gray-500 focus:outline-none focus:ring-0"
                            placeholder="Enter your full name">
                        @error('name')
                            <p class="text-red-600 font-bold text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="mb-8">
                        <label for="email" class="block text-sm font-black text-black uppercase tracking-widest mb-3">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 border-4 border-black font-bold text-black placeholder-gray-500 focus:outline-none focus:ring-0"
                            placeholder="Enter your email">
                        @error('email')
                            <p class="text-red-600 font-bold text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role Selection -->
                    <div class="mb-8">
                        <label for="role" class="block text-sm font-black text-black uppercase tracking-widest mb-3">Staff Role</label>
                        <select id="role" name="role" required
                            class="w-full px-4 py-3 border-4 border-black font-bold text-black bg-white focus:outline-none focus:ring-0">
                            <option value="">Select your role</option>
                            <option value="waiter">Waiter</option>
                            <option value="chef">Chef</option>
                        </select>
                        @error('role')
                            <p class="text-red-600 font-bold text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="mb-8">
                        <label for="password" class="block text-sm font-black text-black uppercase tracking-widest mb-3">Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 border-4 border-black font-bold text-black placeholder-gray-500 focus:outline-none focus:ring-0"
                            placeholder="Minimum 8 characters">
                        @error('password')
                            <p class="text-red-600 font-bold text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation Field -->
                    <div class="mb-8">
                        <label for="password_confirmation" class="block text-sm font-black text-black uppercase tracking-widest mb-3">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-4 py-3 border-4 border-black font-bold text-black placeholder-gray-500 focus:outline-none focus:ring-0"
                            placeholder="Re-enter your password">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full px-6 py-4 bg-black border-4 border-black text-white font-black text-lg uppercase tracking-wide hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                        Create Account
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-8 pt-8 border-t-4 border-black text-center">
                    <p class="text-sm font-bold text-gray-700">Already have an account?</p>
                    <a href="{{ route('login') }}" class="text-black font-black text-sm uppercase hover:underline">
                        Sign In
                    </a>
                </div>
            </div>

            <!-- Restaurant Info Block -->
            <div class="bg-gray-50 border-4 border-black p-6 mt-8">
                <p class="text-xs font-black text-black uppercase tracking-widest mb-2">Restaurant Information</p>
                <p class="text-sm font-bold text-gray-700">{{ $restaurant->name }}</p>
                <p class="text-xs font-medium text-gray-600 mt-2">{{ $restaurant->slug }}</p>
            </div>
        </div>
    </div>
</body>
</html>
