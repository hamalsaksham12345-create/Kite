@extends('layouts.master')

@section('title', 'Register - Kite Restaurant SaaS')

@section('content')
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 pt-32">
    <!-- Header -->
    <div class="sm:mx-auto sm:w-full sm:max-w-lg">
        <div class="text-center">
            <h2 class="text-5xl md:text-6xl font-black text-gray-900 mb-4">
                Create Restaurant Account
            </h2>
            <p class="text-lg text-gray-600 font-medium">
                Already have an account?
                <a href="{{ route('login') }}" class="font-bold text-emerald-700 hover:text-emerald-600">
                    Sign in here
                </a>
            </p>
        </div>
    </div>

    <!-- Form -->
    <div class="mt-12 sm:mx-auto sm:w-full sm:max-w-lg">
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-8 bg-red-50 rounded-3xl p-6 border border-red-200 shadow-sm">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="font-bold text-red-800 mb-2">Registration Failed</h3>
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm text-red-700">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Restaurant Name -->
                <div>
                    <label for="restaurant_name" class="block text-sm font-semibold text-gray-900 mb-2">Restaurant Name</label>
                    <input id="restaurant_name" name="restaurant_name" type="text" required 
                           value="{{ old('restaurant_name') }}"
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base @error('restaurant_name') border-red-500 @enderror"
                           placeholder="Enter your restaurant name">
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-semibold text-gray-900 mb-2">Restaurant Slug</label>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-500 font-medium text-sm">kite.test/</span>
                        <input id="slug" name="slug" type="text" required 
                               value="{{ old('slug') }}"
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base @error('slug') border-red-500 @enderror"
                               placeholder="your-restaurant-name">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Only letters, numbers, dashes, and underscores allowed</p>
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">Email Address</label>
                    <input id="email" name="email" type="email" required 
                           value="{{ old('email') }}"
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base @error('email') border-red-500 @enderror"
                           placeholder="Enter your email">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">Password</label>
                    <input id="password" name="password" type="password" required 
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base @error('password') border-red-500 @enderror"
                           placeholder="Create a password (min 8 characters)">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 mb-2">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required 
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base"
                           placeholder="Confirm your password">
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-6 rounded-xl text-base font-bold text-white bg-emerald-700 hover:bg-emerald-800 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                        Create Restaurant Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-generate slug from restaurant name
    document.getElementById('restaurant_name').addEventListener('input', function(e) {
        const name = e.target.value;
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9\s-_]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        document.getElementById('slug').value = slug;
    });
</script>
@endpush
@endsection