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
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <a href="{{ route('home') }}" class="text-3xl font-black text-emerald-700">Kite</a>
            </div>
            <h2 class="mt-6 text-center text-3xl font-black text-gray-900">
                Launch your restaurant
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-emerald-700 hover:text-emerald-600">
                    Sign in here
                </a>
            </p>
        </div>

        <!-- Form -->
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-lg sm:rounded-2xl sm:px-10 border border-gray-100">
                <form method="POST" action="{{ route('onboarding.register') }}" x-data="registrationForm()">
                    @csrf

                    <!-- Restaurant Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Restaurant Information</h3>
                        
                        <div class="mb-4">
                            <label for="restaurant_name" class="block text-sm font-medium text-gray-700 mb-2">Restaurant Name</label>
                            <input id="restaurant_name" name="restaurant_name" type="text" required 
                                   value="{{ old('restaurant_name') }}"
                                   x-model="restaurantName"
                                   @input="generateSlug"
                                   class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                   placeholder="e.g., Mario's Pizza Palace">
                            @error('restaurant_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="desired_slug" class="block text-sm font-medium text-gray-700 mb-2">
                                Your Restaurant URL
                                <span class="text-gray-500 text-xs">(letters, numbers, and hyphens only)</span>
                            </label>
                            <div class="flex rounded-xl shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    kite.test/
                                </span>
                                <input id="desired_slug" name="desired_slug" type="text" required 
                                       value="{{ old('desired_slug') }}"
                                       x-model="slug"
                                       class="flex-1 block w-full px-3 py-3 border border-gray-300 rounded-none rounded-r-xl placeholder-gray-400 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                                       placeholder="marios-pizza">
                            </div>
                            @error('desired_slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Your restaurant will be available at: <span class="font-medium" x-text="'kite.test/' + slug"></span></p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone (Optional)</label>
                                <input id="phone" name="phone" type="tel" 
                                       value="{{ old('phone') }}"
                                       class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                       placeholder="+1 (555) 123-4567">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address (Optional)</label>
                            <textarea id="address" name="address" rows="3" 
                                      class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                      placeholder="123 Main Street, City, State 12345">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Restaurant Logo (Optional)</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-emerald-400 transition-colors">
                                <div class="w-16 h-16 bg-gray-100 rounded-xl mx-auto mb-3 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">Upload your restaurant logo</p>
                                <p class="text-xs text-gray-500">You can upload this later in your dashboard</p>
                            </div>
                        </div>
                    </div>

                    <!-- Owner Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Owner Information</h3>
                        
                        <div class="mb-4">
                            <label for="owner_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input id="owner_name" name="owner_name" type="text" required 
                                   value="{{ old('owner_name') }}"
                                   class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                   placeholder="John Doe">
                            @error('owner_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="owner_email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input id="owner_email" name="owner_email" type="email" required 
                                   value="{{ old('owner_email') }}"
                                   class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                   placeholder="john@example.com">
                            @error('owner_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input id="password" name="password" type="password" required 
                                   class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                   placeholder="••••••••">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required 
                                   class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-xl placeholder-gray-400 focus:outline-none focus:ring-sky-500 focus:border-sky-500 sm:text-sm"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="mb-6">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="terms" name="terms" type="checkbox" required 
                                       class="focus:ring-sky-500 h-4 w-4 text-sky-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="terms" class="text-gray-700">
                                    I agree to the <a href="#" class="text-sky-600 hover:text-sky-500">Terms of Service</a> and <a href="#" class="text-sky-600 hover:text-sky-500">Privacy Policy</a>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-emerald-700 hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                            Continue to Subscription
                        </button>
                    </div>
                </form>

                <!-- Back to Home -->
                <div class="mt-6 text-center">
                    <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-emerald-700 transition-colors">
                        ← Back to home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function registrationForm() {
            return {
                restaurantName: '',
                slug: '',
                generateSlug() {
                    this.slug = this.restaurantName
                        .toLowerCase()
                        .replace(/[^a-z0-9\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .replace(/^-|-$/g, '');
                }
            }
        }
    </script>
</body>
</html>