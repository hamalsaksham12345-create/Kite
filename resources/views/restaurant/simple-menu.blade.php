<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $currentRestaurant->name }} - Menu</title>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Restaurant Branding CSS -->
    {!! $restaurantBrandingCSS !!}
</head>
<body class="bg-gray-50">
    <!-- Restaurant Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <div class="flex items-center space-x-4">
                <!-- Restaurant Logo -->
                @if($currentRestaurant->logo)
                    <img src="{{ asset('storage/' . $currentRestaurant->logo) }}" 
                         alt="{{ $currentRestaurant->name }}" 
                         class="h-16 w-16 rounded-full object-cover border-2 border-brand-primary">
                @else
                    <div class="h-16 w-16 rounded-full brand-primary flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">
                            {{ substr($currentRestaurant->name, 0, 1) }}
                        </span>
                    </div>
                @endif
                
                <!-- Restaurant Info -->
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $currentRestaurant->name }}</h1>
                    <p class="text-gray-600">{{ $currentRestaurant->description ?? 'Welcome to our restaurant' }}</p>
                    @if($currentRestaurant->phone)
                        <p class="text-sm text-gray-500">{{ $currentRestaurant->phone }}</p>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Menu Content -->
    <main class="max-w-4xl mx-auto px-4 py-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Our Menu</h2>
            <p class="text-gray-600">Delicious dishes made with love</p>
        </div>

        <!-- Sample Menu Items (using brand colors) -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- Sample Item 1 -->
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                <div class="h-48 bg-gray-200"></div>
                <div class="p-4">
                    <h3 class="font-bold text-lg text-gray-900 mb-2">Sample Dish</h3>
                    <p class="text-gray-600 text-sm mb-3">A delicious sample dish description</p>
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-brand-primary">$12.99</span>
                        <button class="brand-primary text-white px-4 py-2 rounded-lg font-medium hover:opacity-90 transition-opacity">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sample Item 2 -->
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                <div class="h-48 bg-gray-200"></div>
                <div class="p-4">
                    <h3 class="font-bold text-lg text-gray-900 mb-2">Another Dish</h3>
                    <p class="text-gray-600 text-sm mb-3">Another delicious sample dish</p>
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-brand-primary">$15.99</span>
                        <button class="brand-primary text-white px-4 py-2 rounded-lg font-medium hover:opacity-90 transition-opacity">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sample Item 3 -->
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                <div class="h-48 bg-gray-200"></div>
                <div class="p-4">
                    <h3 class="font-bold text-lg text-gray-900 mb-2">Special Item</h3>
                    <p class="text-gray-600 text-sm mb-3">Our chef's special recommendation</p>
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-brand-primary">$18.99</span>
                        <button class="brand-secondary text-white px-4 py-2 rounded-lg font-medium hover:opacity-90 transition-opacity">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Branding Demo -->
        <div class="mt-12 p-6 bg-white rounded-lg shadow-sm border">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Restaurant Branding Demo</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 mb-2">Primary Color:</p>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 brand-primary rounded border"></div>
                        <span class="font-mono text-sm">{{ $currentRestaurant->primary_color ?? '#10b981' }}</span>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-2">Secondary Color:</p>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 brand-secondary rounded border"></div>
                        <span class="font-mono text-sm">{{ $currentRestaurant->secondary_color ?? '#065f46' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-4xl mx-auto px-4 py-6 text-center">
            <p class="text-gray-600">
                &copy; {{ date('Y') }} {{ $currentRestaurant->name }}. All rights reserved.
            </p>
            @if($currentRestaurant->address)
                <p class="text-sm text-gray-500 mt-1">{{ $currentRestaurant->address }}</p>
            @endif
        </div>
    </footer>
</body>
</html>