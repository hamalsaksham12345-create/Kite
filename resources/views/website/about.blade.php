@extends('layouts.master')

@section('title', 'About ' . $restaurant->name)
@section('description', 'Learn more about ' . $restaurant->name)

@section('content')
<div class="pt-32 pb-24 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <div class="mb-12">
            <a href="{{ route('website.index.path', $restaurant->slug) }}" class="text-emerald-700 hover:text-emerald-800 font-semibold">← Back to Home</a>
        </div>

        <!-- Header -->
        <div class="mb-16">
            <h1 class="text-6xl md:text-7xl font-black text-gray-900 leading-[0.9] mb-8 tracking-tight">
                About <span class="bg-gradient-to-r from-emerald-700 to-emerald-600 bg-clip-text text-transparent">{{ $restaurant->name }}</span>
            </h1>
            <p class="text-xl text-gray-600 font-medium">
                Discover our story, values, and commitment to excellence
            </p>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-3xl p-12 shadow-sm border border-gray-100 mb-16">
            <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed">
                <p>
                    {{ $websiteSetting->about_section ?? 'Welcome to ' . $restaurant->name . ', where culinary excellence meets warm hospitality. We are dedicated to providing our guests with an unforgettable dining experience through carefully crafted dishes, exceptional service, and a welcoming atmosphere.' }}
                </p>

                <h2 class="text-3xl font-black text-gray-900 mt-12 mb-6">Our Mission</h2>
                <p>
                    To deliver exceptional food and service that brings people together and creates lasting memories. 
                    We believe in using the finest ingredients and traditional cooking methods to create dishes that 
                    delight the senses and nourish the soul.
                </p>

                <h2 class="text-3xl font-black text-gray-900 mt-12 mb-6">Our Values</h2>
                <ul class="space-y-4">
                    <li class="flex gap-4">
                        <span class="text-emerald-700 font-black text-2xl">✓</span>
                        <span><strong>Quality:</strong> We never compromise on the quality of our ingredients or preparation</span>
                    </li>
                    <li class="flex gap-4">
                        <span class="text-emerald-700 font-black text-2xl">✓</span>
                        <span><strong>Hospitality:</strong> Every guest is treated with warmth and respect</span>
                    </li>
                    <li class="flex gap-4">
                        <span class="text-emerald-700 font-black text-2xl">✓</span>
                        <span><strong>Innovation:</strong> We continuously evolve our menu while honoring tradition</span>
                    </li>
                    <li class="flex gap-4">
                        <span class="text-emerald-700 font-black text-2xl">✓</span>
                        <span><strong>Sustainability:</strong> We are committed to responsible sourcing and environmental practices</span>
                    </li>
                </ul>

                <h2 class="text-3xl font-black text-gray-900 mt-12 mb-6">Why Choose Us</h2>
                <p>
                    {{ $websiteSetting->features_section ?? 'Quality food, excellent service, and a welcoming atmosphere. Our team is dedicated to making every visit memorable.' }}
                </p>
            </div>
        </div>

        <!-- Contact Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-16">
            @if($websiteSetting->phone)
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <p class="text-sm font-semibold text-gray-500 mb-2">PHONE</p>
                    <a href="tel:{{ $websiteSetting->phone }}" class="text-2xl font-black text-emerald-700 hover:text-emerald-800">{{ $websiteSetting->phone }}</a>
                </div>
            @endif

            @if($websiteSetting->email)
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <p class="text-sm font-semibold text-gray-500 mb-2">EMAIL</p>
                    <a href="mailto:{{ $websiteSetting->email }}" class="text-2xl font-black text-emerald-700 hover:text-emerald-800 break-all">{{ $websiteSetting->email }}</a>
                </div>
            @endif

            @if($websiteSetting->address)
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <p class="text-sm font-semibold text-gray-500 mb-2">ADDRESS</p>
                    <p class="text-lg font-bold text-gray-900">{{ $websiteSetting->address }}</p>
                </div>
            @endif

            @if($websiteSetting->city)
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <p class="text-sm font-semibold text-gray-500 mb-2">CITY</p>
                    <p class="text-lg font-bold text-gray-900">{{ $websiteSetting->city }}@if($websiteSetting->state), {{ $websiteSetting->state }}@endif</p>
                </div>
            @endif
        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-br from-emerald-700 to-emerald-800 rounded-3xl p-12 text-center text-white">
            <h2 class="text-4xl font-black mb-6">Ready to Visit Us?</h2>
            <p class="text-lg text-emerald-100 mb-8">
                Experience the difference quality and hospitality make
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('restaurant.menu.path', $restaurant->slug) }}" class="bg-white hover:bg-gray-100 text-emerald-700 px-8 py-4 rounded-2xl font-bold transition-all transform hover:scale-105">
                    View Menu
                </a>
                <a href="{{ route('website.contact.path', $restaurant->slug) }}" class="border-2 border-white text-white hover:bg-white/10 px-8 py-4 rounded-2xl font-bold transition-all">
                    Get in Touch
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
