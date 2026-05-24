@extends('layouts.app')

@section('content')
<style>
    :root {
        --color-primary: {{ $websiteSetting->primary_color }};
        --color-secondary: {{ $websiteSetting->secondary_color }};
        --color-accent: {{ $websiteSetting->accent_color }};
        --color-text: {{ $websiteSetting->text_color }};
        --color-background: {{ $websiteSetting->background_color }};
        --font-family: '{{ $websiteSetting->font_family }}', sans-serif;
        --font-heading: '{{ $websiteSetting->heading_font }}', sans-serif;
    }

    body {
        font-family: var(--font-family);
        color: var(--color-text);
        background-color: var(--color-background);
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: var(--font-heading);
    }
</style>

<!-- Navigation -->
<nav class="border-b-4 border-black bg-white shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <div class="flex items-center gap-4">
                @if($websiteSetting->logo_path)
                    <img src="{{ Storage::url($websiteSetting->logo_path) }}" alt="{{ $restaurant->name }}" class="h-12">
                @endif
                <h1 class="text-2xl font-bold" style="font-family: var(--font-heading);">{{ $restaurant->name }}</h1>
            </div>
            <div class="flex gap-6">
                <a href="{{ route('website.index.path', $restaurant->slug) }}" class="font-bold hover:text-blue-600">Home</a>
                <a href="{{ route('website.contact.path', $restaurant->slug) }}" class="font-bold hover:text-blue-600">Contact</a>
            </div>
        </div>
    </div>
</nav>

<!-- Header -->
<div class="py-12 border-b-4 border-black" style="background-color: var(--color-primary);">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-5xl font-bold text-white" style="font-family: var(--font-heading);">Our Menu</h1>
        <p class="text-white text-lg mt-2">Explore our delicious offerings</p>
    </div>
</div>

<!-- Menu Content -->
<div class="max-w-7xl mx-auto px-4 py-16">
    @foreach($categories as $category)
        <div class="mb-16">
            <h2 class="text-4xl font-bold mb-8 pb-4 border-b-4 border-black" style="font-family: var(--font-heading);">{{ $category->name }}</h2>
            
            @if($category->description)
                <p class="text-gray-600 mb-8">{{ $category->description }}</p>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($category->menuItems->where('is_available', true) as $item)
                    <div class="border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-2xl font-bold" style="font-family: var(--font-heading);">{{ $item->name }}</h3>
                                @if($item->is_featured)
                                    <span class="inline-block mt-2 px-3 py-1 bg-yellow-300 text-black font-bold text-sm border-2 border-black">Featured</span>
                                @endif
                            </div>
                            <span class="text-3xl font-bold" style="color: var(--color-primary);">Rs {{ number_format($item->price, 2) }}</span>
                        </div>

                        <p class="text-gray-600 mb-4">{{ $item->description }}</p>

                        @if($item->preparation_time)
                            <p class="text-sm text-gray-500 mb-2">Prep time: {{ $item->preparation_time }} mins</p>
                        @endif

                        @if($item->ingredients && count($item->ingredients) > 0)
                            <div class="mb-3">
                                <p class="text-sm font-bold text-gray-700">Ingredients:</p>
                                <p class="text-sm text-gray-600">{{ implode(', ', $item->ingredients) }}</p>
                            </div>
                        @endif

                        @if($item->allergens && count($item->allergens) > 0)
                            <div class="mb-3 p-2 bg-red-50 border-2 border-red-300">
                                <p class="text-sm font-bold text-red-700">Allergens:</p>
                                <p class="text-sm text-red-600">{{ implode(', ', $item->allergens) }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<!-- CTA Section -->
<section class="py-16 border-t-4 border-black" style="background-color: var(--color-primary);">
    <div class="max-w-4xl mx-auto px-4 text-center text-white">
        <h2 class="text-4xl font-bold mb-6" style="font-family: var(--font-heading);">Ready to Order?</h2>
        <a href="{{ route('restaurant.menu.path', $restaurant->slug) }}" class="inline-block px-8 py-4 bg-white text-black font-bold border-2 border-white hover:bg-gray-100">
            Place Your Order
        </a>
    </div>
</section>

<!-- Footer -->
<footer class="border-t-4 border-black bg-gray-900 text-white py-8">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <p class="font-bold mb-2">{{ $restaurant->name }}</p>
        <p class="text-gray-400 text-sm">Powered by Kite Restaurant Management</p>
    </div>
</footer>
@endsection
