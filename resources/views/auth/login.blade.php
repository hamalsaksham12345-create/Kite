@extends('layouts.master')

@section('title', 'Sign In - Kite Restaurant SaaS')

@section('content')
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 pt-32">
    <!-- Header -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <h2 class="text-5xl md:text-6xl font-black text-gray-900 mb-4">
                Welcome back
            </h2>
            <p class="text-lg text-gray-600 font-medium">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-bold text-emerald-700 hover:text-emerald-600">
                    Get started here
                </a>
            </p>
        </div>
    </div>

    <!-- Form -->
    <div class="mt-12 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">Email Address</label>
                    <input id="email" name="email" type="email" required 
                           value="{{ old('email') }}"
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base"
                           placeholder="Enter your email">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">Password</label>
                    <input id="password" name="password" type="password" required 
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base"
                           placeholder="Enter your password">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                               class="h-5 w-5 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                        <label for="remember" class="ml-3 block text-sm font-medium text-gray-700">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-semibold text-emerald-700 hover:text-emerald-600">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-6 rounded-xl text-base font-bold text-white bg-emerald-700 hover:bg-emerald-800 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                        Sign In
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="mt-8">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500 font-medium">New to Kite?</span>
                    </div>
                </div>

                <div class="mt-8">
                    <a href="{{ route('register') }}" 
                       class="w-full flex justify-center py-3 px-6 rounded-xl text-base font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition-all">
                        Create Account
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection