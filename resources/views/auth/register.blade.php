<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register - Kite Restaurant SaaS</title>

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
        <div class="sm:mx-auto sm:w-full sm:max-w-lg">
            <div class="text-center">
                <a href="{{ route('home') }}" class="text-4xl font-black text-emerald-700">Kite</a>
            </div>
            <h2 class="mt-8 text-center text-5xl font-black text-black">
                Create Restaurant Account
            </h2>
            <p class="mt-4 text-center text-lg font-medium text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-bold text-emerald-700 hover:text-emerald-600 underline">
                    Sign in here
                </a>
            </p>
        </div>

        <!-- Form -->
        <div class="mt-12 sm:mx-auto sm:w-full sm:max-w-lg">
            <div class="bg-white py-10 px-8 border-2 border-black hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <form method="POST" action="{{ route('register') }}" class="space-y-8">
                    @csrf

                    <!-- Restaurant Name -->
                    <div>
                        <label for="restaurant_name" class="block text-lg font-black text-black mb-3">Restaurant Name</label>
                        <input id="restaurant_name" name="restaurant_name" type="text" required 
                               value="{{ old('restaurant_name') }}"
                               class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium"
                               placeholder="Enter your restaurant name">
                        @error('restaurant_name')
                            <p class="mt-2 text-sm font-bold text-red-600 bg-red-50 border border-red-600 px-3 py-2 rounded">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-lg font-black text-black mb-3">Restaurant Slug</label>
                        <div class="flex items-center">
                            <span class="text-gray-500 font-medium mr-2">kite.com/</span>
                            <input id="slug" name="slug" type="text" required 
                                   value="{{ old('slug') }}"
                                   class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium"
                                   placeholder="your-restaurant-name">
                        </div>
                        <p class="mt-1 text-sm text-gray-600">Only letters, numbers, dashes, and underscores allowed</p>
                        @error('slug')
                            <p class="mt-2 text-sm font-bold text-red-600 bg-red-50 border border-red-600 px-3 py-2 rounded">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-lg font-black text-black mb-3">Email Address</label>
                        <input id="email" name="email" type="email" required 
                               value="{{ old('email') }}"
                               class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium"
                               placeholder="Enter your email">
                        @error('email')
                            <p class="mt-2 text-sm font-bold text-red-600 bg-red-50 border border-red-600 px-3 py-2 rounded">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-lg font-black text-black mb-3">Password</label>
                        <input id="password" name="password" type="password" required 
                               class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium"
                               placeholder="Create a password (min 8 characters)">
                        @error('password')
                            <p class="mt-2 text-sm font-bold text-red-600 bg-red-50 border border-red-600 px-3 py-2 rounded">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-lg font-black text-black mb-3">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                               class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium"
                               placeholder="Confirm your password">
                    </div>

                    <!-- Subscription Plan -->
                    <div>
                        <label for="plan" class="block text-lg font-black text-black mb-3">Subscription Plan</label>
                        <select id="plan" name="plan" required 
                                class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium bg-white">
                            <option value="">Select a plan</option>
                            <option value="1 month" {{ old('plan') == '1 month' ? 'selected' : '' }}>1 Month - $29.99/month</option>
                            <option value="6 months" {{ old('plan') == '6 months' ? 'selected' : '' }}>6 Months - $149.99 (Save 17%)</option>
                            <option value="12 months" {{ old('plan') == '12 months' ? 'selected' : '' }}>12 Months - $299.99 (Save 17%)</option>
                        </select>
                        @error('plan')
                            <p class="mt-2 text-sm font-bold text-red-600 bg-red-50 border border-red-600 px-3 py-2 rounded">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="group relative w-full flex justify-center py-4 px-6 border-2 border-black text-lg font-black text-white bg-emerald-600 hover:bg-emerald-700 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                            Create Restaurant Account
                        </button>
                    </div>
                </form>

                <!-- Back to Home -->
                <div class="mt-8 text-center">
                    <a href="{{ route('home') }}" class="text-lg font-bold text-gray-600 hover:text-emerald-700 transition-colors">
                        ← Back to home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-generate slug from restaurant name
        document.getElementById('restaurant_name').addEventListener('input', function(e) {
            const name = e.target.value;
            const slug = name.toLowerCase()
                .replace(/[^a-z0-9\s-_]/g, '') // Remove special characters
                .replace(/\s+/g, '-') // Replace spaces with dashes
                .replace(/-+/g, '-') // Replace multiple dashes with single dash
                .trim();
            document.getElementById('slug').value = slug;
        });
    </script>
</body>
</html>