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

    .btn-primary {
        background-color: var(--color-primary);
        border-color: var(--color-secondary);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--color-secondary);
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
                <a href="#menu" class="font-bold hover:text-blue-600">Menu</a>
                <a href="#about" class="font-bold hover:text-blue-600">About</a>
                <a href="{{ route('website.contact.path', $restaurant->slug) }}" class="font-bold hover:text-blue-600">Contact</a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
@if($websiteSetting->banner_path)
    <div class="relative h-96 overflow-hidden">
        <img src="{{ Storage::url($websiteSetting->banner_path) }}" alt="Banner" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
            <div class="text-center text-white">
                <h2 class="text-5xl font-bold mb-4" style="font-family: var(--font-heading);">{{ $websiteSetting->hero_title ?? $restaurant->name }}</h2>
                <p class="text-2xl">{{ $websiteSetting->hero_subtitle }}</p>
            </div>
        </div>
    </div>
@else
    <div class="py-20 text-center border-b-4 border-black" style="background-color: var(--color-primary);">
        <h2 class="text-5xl font-bold text-white mb-4" style="font-family: var(--font-heading);">{{ $websiteSetting->hero_title ?? $restaurant->name }}</h2>
        <p class="text-2xl text-white">{{ $websiteSetting->hero_subtitle }}</p>
    </div>
@endif

<!-- About Section -->
@if($websiteSetting->about_section)
    <section id="about" class="py-16 border-b-4 border-black">
        <div class="max-w-4xl mx-auto px-4">
            <h2 class="text-4xl font-bold mb-8 border-b-4 border-black pb-4" style="font-family: var(--font-heading);">About Us</h2>
            <div class="prose max-w-none">
                {!! nl2br(e($websiteSetting->about_section)) !!}
            </div>
        </div>
    </section>
@endif

<!-- Menu Preview Section -->
@if($websiteSetting->show_menu_preview && $categories->count() > 0)
    <section id="menu" class="py-16 border-b-4 border-black">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-4xl font-bold mb-12 border-b-4 border-black pb-4" style="font-family: var(--font-heading);">Featured Menu Items</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories as $category)
                    @foreach($category->menuItems->take(2) as $item)
                        <div class="border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                            <div class="p-6">
                                <h3 class="text-xl font-bold mb-2" style="font-family: var(--font-heading);">{{ $item->name }}</h3>
                                <p class="text-gray-600 text-sm mb-4">{{ $item->description }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold" style="color: var(--color-primary);">Rs {{ number_format($item->price, 2) }}</span>
                                    <span class="text-xs font-bold bg-gray-200 px-2 py-1">{{ $category->name }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('website.menu.path', $restaurant->slug) }}" class="inline-block px-8 py-4 btn-primary font-bold border-2 border-black">
                    View Full Menu
                </a>
            </div>
        </div>
    </section>
@endif

<!-- CTA Section -->
<section class="py-16 border-b-4 border-black" style="background-color: var(--color-primary);">
    <div class="max-w-4xl mx-auto px-4 text-center text-white">
        <h2 class="text-4xl font-bold mb-6" style="font-family: var(--font-heading);">Ready to Order?</h2>
        <p class="text-xl mb-8">Explore our full menu and place your order now</p>
        <a href="{{ route('restaurant.menu.path', $restaurant->slug) }}" class="inline-block px-8 py-4 bg-white text-black font-bold border-2 border-white hover:bg-gray-100">
            Order Now
        </a>
    </div>
</section>

<!-- Contact Section -->
<section class="py-16 border-b-4 border-black">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="text-4xl font-bold mb-12 border-b-4 border-black pb-4" style="font-family: var(--font-heading);">Get in Touch</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                @if($websiteSetting->phone)
                    <div class="mb-6">
                        <p class="font-bold text-lg mb-2">Phone</p>
                        <a href="tel:{{ $websiteSetting->phone }}" class="text-blue-600 hover:text-blue-800">{{ $websiteSetting->phone }}</a>
                    </div>
                @endif

                @if($websiteSetting->email)
                    <div class="mb-6">
                        <p class="font-bold text-lg mb-2">Email</p>
                        <a href="mailto:{{ $websiteSetting->email }}" class="text-blue-600 hover:text-blue-800">{{ $websiteSetting->email }}</a>
                    </div>
                @endif

                @if($websiteSetting->address)
                    <div class="mb-6">
                        <p class="font-bold text-lg mb-2">Address</p>
                        <p>{{ $websiteSetting->address }}</p>
                        @if($websiteSetting->city)
                            <p>{{ $websiteSetting->city }}@if($websiteSetting->state), {{ $websiteSetting->state }}@endif</p>
                        @endif
                    </div>
                @endif
            </div>

            <div>
                @if($websiteSetting->facebook_url || $websiteSetting->instagram_url || $websiteSetting->twitter_url)
                    <p class="font-bold text-lg mb-4">Follow Us</p>
                    <div class="flex gap-4">
                        @if($websiteSetting->facebook_url)
                            <a href="{{ $websiteSetting->facebook_url }}" target="_blank" class="font-bold text-blue-600 hover:text-blue-800">Facebook</a>
                        @endif
                        @if($websiteSetting->instagram_url)
                            <a href="{{ $websiteSetting->instagram_url }}" target="_blank" class="font-bold text-pink-600 hover:text-pink-800">Instagram</a>
                        @endif
                        @if($websiteSetting->twitter_url)
                            <a href="{{ $websiteSetting->twitter_url }}" target="_blank" class="font-bold text-blue-400 hover:text-blue-600">Twitter</a>
                        @endif
                    </div>
                @endif

                @if($websiteSetting->whatsapp_number)
                    <div class="mt-6">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $websiteSetting->whatsapp_number) }}" target="_blank" class="inline-block px-6 py-3 bg-green-500 text-white font-bold border-2 border-green-700 hover:bg-green-600">
                            Chat on WhatsApp
                        </a>
                    </div>
                @endif
            </div>
        </div>
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
