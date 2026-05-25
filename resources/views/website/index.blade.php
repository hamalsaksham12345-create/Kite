@extends('layouts.master')

@section('title', $restaurant->name . ' - Restaurant Website')
@section('description', 'Explore ' . $restaurant->name . ' menu and order online')

@section('content')
<style>
    :root {
        --color-primary: {{ $websiteSetting->primary_color ?? '#10b981' }};
        --color-secondary: {{ $websiteSetting->secondary_color ?? '#065f46' }};
        --color-accent: {{ $websiteSetting->accent_color ?? '#059669' }};
        --color-text: {{ $websiteSetting->text_color ?? '#111827' }};
        --color-background: {{ $websiteSetting->background_color ?? '#ffffff' }};
    }

    body {
        color: var(--color-text);
        background-color: var(--color-background);
    }

    .btn-primary {
        background-color: var(--color-primary);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--color-secondary);
    }
</style>

<!-- Hero Section -->
<section class="pt-32 pb-24 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="text-center">
            @if($websiteSetting->logo_path)
                <img src="{{ Storage::url($websiteSetting->logo_path) }}" alt="{{ $restaurant->name }}" class="h-20 mx-auto mb-8">
            @endif
            <h1 class="text-6xl md:text-7xl font-black text-gray-900 leading-[0.9] mb-8 tracking-tight">
                {{ $websiteSetting->hero_title ?? $restaurant->name }}
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 max-w-4xl mx-auto mb-16 leading-relaxed font-medium">
                {{ $websiteSetting->hero_subtitle ?? 'Discover our delicious menu and place your order online' }}
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                <a href="#menu" class="btn-primary px-10 py-5 rounded-2xl text-xl font-bold transition-all transform hover:scale-105 shadow-xl hover:shadow-2xl">
                    View Menu
                </a>
                <a href="{{ route('website.contact.path', $restaurant->slug) }}" class="text-emerald-700 hover:text-emerald-800 px-10 py-5 rounded-2xl text-xl font-bold border-2 border-emerald-700 hover:border-emerald-800 transition-colors bg-white hover:bg-gray-50">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
@if($websiteSetting->about_section)
    <section id="about" class="py-24 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-5xl md:text-6xl font-black text-gray-900 mb-12 tracking-tight">
                About <span class="bg-gradient-to-r from-emerald-700 to-emerald-600 bg-clip-text text-transparent">{{ $restaurant->name }}</span>
            </h2>
            <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed">
                {!! nl2br(e($websiteSetting->about_section)) !!}
            </div>
        </div>
    </section>
@endif

<!-- Menu Preview Section -->
@if($websiteSetting->show_menu_preview && $categories->count() > 0)
    <section id="menu" class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-5xl md:text-6xl font-black text-gray-900 mb-8 tracking-tight">
                    Featured <span class="bg-gradient-to-r from-emerald-700 to-emerald-600 bg-clip-text text-transparent">Menu Items</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto font-medium">
                    Explore our carefully curated selection of dishes
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach($categories as $category)
                    @foreach($category->menuItems->take(2) as $item)
                        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                            <div class="mb-6">
                                <span class="inline-block px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-sm font-bold mb-4">{{ $category->name }}</span>
                                <h3 class="text-2xl font-black text-gray-900 mb-2">{{ $item->name }}</h3>
                                <p class="text-gray-600 leading-relaxed">{{ $item->description }}</p>
                            </div>
                            <div class="flex justify-between items-center pt-6 border-t border-gray-100">
                                <span class="text-3xl font-black btn-primary">Rs {{ number_format($item->price, 2) }}</span>
                                @if($item->preparation_time)
                                    <span class="text-sm font-semibold text-gray-500">{{ $item->preparation_time }} min</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>

            <div class="text-center mt-16">
                <a href="{{ route('website.menu.path', $restaurant->slug) }}" class="btn-primary px-10 py-5 rounded-2xl text-xl font-bold transition-all transform hover:scale-105 shadow-xl hover:shadow-2xl inline-block">
                    View Full Menu
                </a>
            </div>
        </div>
    </section>
@endif

<!-- CTA Section -->
<section class="py-24 bg-gradient-to-br from-emerald-700 to-emerald-800">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-5xl md:text-6xl font-black text-white mb-8 tracking-tight">
            Ready to Order?
        </h2>
        <p class="text-xl text-emerald-100 mb-12 leading-relaxed font-medium">
            Explore our full menu and place your order now
        </p>
        <a href="{{ route('restaurant.menu.path', $restaurant->slug) }}" class="bg-white hover:bg-gray-100 text-emerald-700 px-10 py-5 rounded-2xl text-xl font-black transition-all transform hover:scale-105 shadow-xl hover:shadow-2xl inline-block">
            Order Now
        </a>
    </div>
</section>

<!-- Contact Section -->
<section class="py-24 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-5xl md:text-6xl font-black text-gray-900 mb-16 tracking-tight">
            Get in <span class="bg-gradient-to-r from-emerald-700 to-emerald-600 bg-clip-text text-transparent">Touch</span>
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
            <div class="space-y-8">
                @if($websiteSetting->phone)
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                        <p class="font-bold text-lg text-gray-900 mb-3">Phone</p>
                        <a href="tel:{{ $websiteSetting->phone }}" class="text-emerald-700 hover:text-emerald-800 text-xl font-semibold">{{ $websiteSetting->phone }}</a>
                    </div>
                @endif

                @if($websiteSetting->email)
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                        <p class="font-bold text-lg text-gray-900 mb-3">Email</p>
                        <a href="mailto:{{ $websiteSetting->email }}" class="text-emerald-700 hover:text-emerald-800 text-xl font-semibold">{{ $websiteSetting->email }}</a>
                    </div>
                @endif

                @if($websiteSetting->address)
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                        <p class="font-bold text-lg text-gray-900 mb-3">Address</p>
                        <p class="text-gray-600 text-lg">{{ $websiteSetting->address }}</p>
                        @if($websiteSetting->city)
                            <p class="text-gray-600 text-lg">{{ $websiteSetting->city }}@if($websiteSetting->state), {{ $websiteSetting->state }}@endif</p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="space-y-8">
                @if($websiteSetting->facebook_url || $websiteSetting->instagram_url || $websiteSetting->twitter_url)
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                        <p class="font-bold text-lg text-gray-900 mb-6">Follow Us</p>
                        <div class="flex flex-wrap gap-4">
                            @if($websiteSetting->facebook_url)
                                <a href="{{ $websiteSetting->facebook_url }}" target="_blank" class="px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-colors">Facebook</a>
                            @endif
                            @if($websiteSetting->instagram_url)
                                <a href="{{ $websiteSetting->instagram_url }}" target="_blank" class="px-6 py-3 bg-pink-600 text-white rounded-xl font-bold hover:bg-pink-700 transition-colors">Instagram</a>
                            @endif
                            @if($websiteSetting->twitter_url)
                                <a href="{{ $websiteSetting->twitter_url }}" target="_blank" class="px-6 py-3 bg-blue-400 text-white rounded-xl font-bold hover:bg-blue-500 transition-colors">Twitter</a>
                            @endif
                        </div>
                    </div>
                @endif

                @if($websiteSetting->whatsapp_number)
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $websiteSetting->whatsapp_number) }}" target="_blank" class="inline-block w-full px-8 py-4 bg-green-500 text-white font-bold rounded-2xl hover:bg-green-600 transition-colors text-center text-lg">
                            💬 Chat on WhatsApp
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
