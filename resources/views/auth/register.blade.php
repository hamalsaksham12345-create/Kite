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
            <!-- Error Messages in Red Bento Box -->
            @if ($errors->any())
                <div class="mb-8 p-6 bg-red-50 border-2 border-red-600 hover:shadow-[4px_4px_0px_0px_rgba(220,38,38,1)] transition-all duration-200">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-black text-red-800">
                                Registration Failed
                            </h3>
                            <div class="mt-2 text-sm">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li class="text-red-700 font-bold">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white py-10 px-8 border-2 border-black hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <form method="POST" action="{{ route('register') }}" class="space-y-8">
                    @csrf

                    <!-- Restaurant Name -->
                    <div>
                        <label for="restaurant_name" class="block text-lg font-black text-black mb-3">Restaurant Name</label>
                        <input id="restaurant_name" name="restaurant_name" type="text" required 
                               value="{{ old('restaurant_name') }}"
                               class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('restaurant_name') border-red-600 @enderror"
                               placeholder="Enter your restaurant name">
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-lg font-black text-black mb-3">Restaurant Slug</label>
                        <div class="flex items-center">
                            <span class="text-gray-500 font-medium mr-2">kite.com/</span>
                            <input id="slug" name="slug" type="text" required 
                                   value="{{ old('slug') }}"
                                   class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('slug') border-red-600 @enderror"
                                   placeholder="your-restaurant-name">
                        </div>
                        <p class="mt-1 text-sm text-gray-600">Only letters, numbers, dashes, and underscores allowed</p>
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-lg font-black text-black mb-3">Email Address</label>
                        <input id="email" name="email" type="email" required 
                               value="{{ old('email') }}"
                               class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('email') border-red-600 @enderror"
                               placeholder="Enter your email">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-lg font-black text-black mb-3">Password</label>
                        <input id="password" name="password" type="password" required 
                               class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('password') border-red-600 @enderror"
                               placeholder="Create a password (min 8 characters)">
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-lg font-black text-black mb-3">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                               class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium"
                               placeholder="Confirm your password">
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