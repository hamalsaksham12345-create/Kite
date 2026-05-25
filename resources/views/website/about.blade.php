@extends('layouts.app')

@section('content')
<style>
    :root {
        --color-primary: {{ $websiteSetting->primary_color ?? '#10b981' }};
        --color-secondary: {{ $websiteSetting->secondary_color ?? '#059669' }};
        --color-accent: {{ $websiteSetting->accent_color ?? '#047857' }};
        --color-text: {{ $websiteSetting->text_color ?? '#000000' }};
        --color-background: {{ $websiteSetting->background_color ?? '#ffffff' }};
        --font-family: '{{ $websiteSetting->font_family ?? 'Inter' }}', sans-serif;
        --font-heading: '{{ $websiteSetting->heading_font ?? 'Inter' }}', sans-serif;
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

<div class="min-h-screen bg-white">
    <!-- Header -->
    <div class="bg-black text-white p-8 border-b-4 border-black">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-5xl font-black mb-2">About Us</h1>
            <p class="text-lg font-bold">{{ $restaurant->name }}</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto p-8">
        <!-- About Section -->
        <div class="mb-12 border-4 border-black bg-white p-8">
            <h2 class="text-3xl font-black mb-4">Our Story</h2>
            <p class="text-lg font-bold text-gray-700 mb-4">
                {{ $websiteSetting->about_section ?? 'Welcome to ' . $restaurant->name . '. We are committed to providing the best dining experience.' }}
            </p>
        </div>

        <!-- Features Section -->
        <div class="mb-12 border-4 border-black bg-white p-8">
            <h2 class="text-3xl font-black mb-4">Why Choose Us</h2>
            <p class="text-lg font-bold text-gray-700 mb-4">
                {{ $websiteSetting->features_section ?? 'Quality food, excellent service, and a welcoming atmosphere.' }}
            </p>
        </div>

        <!-- Contact Info -->
        <div class="border-4 border-black bg-white p-8">
            <h2 class="text-3xl font-black mb-6">Get In Touch</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="border-2 border-gray-300 p-4">
                    <p class="text-sm font-bold text-gray-600 mb-2">PHONE</p>
                    <p class="text-lg font-bold text-black">{{ $websiteSetting->phone ?? $restaurant->phone ?? 'Not provided' }}</p>
                </div>
                <div class="border-2 border-gray-300 p-4">
                    <p class="text-sm font-bold text-gray-600 mb-2">EMAIL</p>
                    <p class="text-lg font-bold text-black">{{ $websiteSetting->email ?? $restaurant->email ?? 'Not provided' }}</p>
                </div>
                <div class="border-2 border-gray-300 p-4">
                    <p class="text-sm font-bold text-gray-600 mb-2">ADDRESS</p>
                    <p class="text-lg font-bold text-black">{{ $websiteSetting->address ?? $restaurant->address ?? 'Not provided' }}</p>
                </div>
                <div class="border-2 border-gray-300 p-4">
                    <p class="text-sm font-bold text-gray-600 mb-2">CITY</p>
                    <p class="text-lg font-bold text-black">{{ $websiteSetting->city ?? 'Not provided' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
