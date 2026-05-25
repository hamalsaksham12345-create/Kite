@extends('layouts.master')

@section('title', 'Get Started - Kite Restaurant SaaS')

@section('content')
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 pt-32">
    <!-- Header -->
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <h2 class="text-5xl md:text-6xl font-black text-gray-900 mb-4">
                Launch your restaurant
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
    <div class="mt-12 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300">
            <form method="POST" action="{{ route('onboarding.register') }}" x-data="registrationForm()">
                @csrf

                <!-- Restaurant Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Restaurant Information</h3>
                    
                    <div class="mb-6">
                        <label for="restaurant_name" class="block text-sm font-semibold text-gray-900 mb-2">Restaurant Name</label>
                        <input id="restaurant_name" name="restaurant_name" type="text" required 
                               value="{{ old('restaurant_name') }}"
                               x-model="restaurantName"
                               @input="generateSlug"
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base"
                               placeholder="e.g., Mario's Pizza Palace">
                        @error('restaurant_name')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="desired_slug" class="block text-sm font-semibold text-gray-900 mb-2">
                            Your Restaurant URL
                        </label>
                        <div class="flex rounded-xl overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-emerald-500">
                            <span class="inline-flex items-center px-4 bg-gray-50 text-gray-500 text-sm font-medium border-r border-gray-300">
                                kite.test/
                            </span>
                            <input id="desired_slug" name="desired_slug" type="text" required 
                                   value="{{ old('desired_slug') }}"
                                   x-model="slug"
                                   class="flex-1 block w-full px-4 py-3 focus:outline-none text-base"
                                   placeholder="marios-pizza">
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Your restaurant will be available at: <span class="font-medium" x-text="'kite.test/' + slug"></span></p>
                    </div>

                    <div class="mb-6">
                        <label for="phone" class="block text-sm font-semibold text-gray-900 mb-2">Phone (Optional)</label>
                        <input id="phone" name="phone" type="tel" 
                               value="{{ old('phone') }}"
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base"
                               placeholder="+1 (555) 123-4567">
                    </div>

                    <div class="mb-6">
                        <label for="address" class="block text-sm font-semibold text-gray-900 mb-2">Address (Optional)</label>
                        <textarea id="address" name="address" rows="3" 
                                  class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base"
                                  placeholder="123 Main Street, City, State 12345">{{ old('address') }}</textarea>
                    </div>
                </div>

                <!-- Owner Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Owner Information</h3>
                    
                    <div class="mb-6">
                        <label for="owner_name" class="block text-sm font-semibold text-gray-900 mb-2">Full Name</label>
                        <input id="owner_name" name="owner_name" type="text" required 
                               value="{{ old('owner_name') }}"
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base"
                               placeholder="John Doe">
                        @error('owner_name')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="owner_email" class="block text-sm font-semibold text-gray-900 mb-2">Email Address</label>
                        <input id="owner_email" name="owner_email" type="email" required 
                               value="{{ old('owner_email') }}"
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base"
                               placeholder="john@example.com">
                        @error('owner_email')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">Password</label>
                        <input id="password" name="password" type="password" required 
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base"
                               placeholder="••••••••">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 mb-2">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                               class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base"
                               placeholder="••••••••">
                    </div>
                </div>

                <!-- Terms -->
                <div class="mb-8">
                    <div class="flex items-start gap-3">
                        <input id="terms" name="terms" type="checkbox" required 
                               class="h-5 w-5 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded mt-1">
                        <label for="terms" class="text-sm text-gray-700">
                            I agree to the <a href="#" class="text-emerald-700 hover:text-emerald-600 font-semibold">Terms of Service</a> and <a href="#" class="text-emerald-700 hover:text-emerald-600 font-semibold">Privacy Policy</a>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-6 rounded-xl text-base font-bold text-white bg-emerald-700 hover:bg-emerald-800 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                        Continue to Subscription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
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
@endpush
@endsection