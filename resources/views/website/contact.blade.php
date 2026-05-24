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
                <a href="{{ route('website.menu.path', $restaurant->slug) }}" class="font-bold hover:text-blue-600">Menu</a>
            </div>
        </div>
    </div>
</nav>

<!-- Header -->
<div class="py-12 border-b-4 border-black" style="background-color: var(--color-primary);">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-5xl font-bold text-white" style="font-family: var(--font-heading);">Contact Us</h1>
        <p class="text-white text-lg mt-2">We'd love to hear from you</p>
    </div>
</div>

<!-- Contact Content -->
<div class="max-w-7xl mx-auto px-4 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Contact Information -->
        <div>
            <h2 class="text-3xl font-bold mb-8 pb-4 border-b-4 border-black" style="font-family: var(--font-heading);">Get in Touch</h2>

            @if($websiteSetting->phone)
                <div class="mb-8 border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
                    <p class="font-bold text-lg mb-2">Phone</p>
                    <a href="tel:{{ $websiteSetting->phone }}" class="text-blue-600 hover:text-blue-800 text-xl font-bold">{{ $websiteSetting->phone }}</a>
                </div>
            @endif

            @if($websiteSetting->email)
                <div class="mb-8 border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
                    <p class="font-bold text-lg mb-2">Email</p>
                    <a href="mailto:{{ $websiteSetting->email }}" class="text-blue-600 hover:text-blue-800 text-xl font-bold">{{ $websiteSetting->email }}</a>
                </div>
            @endif

            @if($websiteSetting->address)
                <div class="mb-8 border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
                    <p class="font-bold text-lg mb-2">Address</p>
                    <p class="text-gray-700">{{ $websiteSetting->address }}</p>
                    @if($websiteSetting->city)
                        <p class="text-gray-700">{{ $websiteSetting->city }}@if($websiteSetting->state), {{ $websiteSetting->state }}@endif @if($websiteSetting->postal_code){{ $websiteSetting->postal_code }}@endif</p>
                    @endif
                    @if($websiteSetting->country)
                        <p class="text-gray-700">{{ $websiteSetting->country }}</p>
                    @endif
                </div>
            @endif

            <!-- Social Media & WhatsApp -->
            <div class="border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6">
                <p class="font-bold text-lg mb-4">Connect With Us</p>
                <div class="flex flex-wrap gap-3">
                    @if($websiteSetting->facebook_url)
                        <a href="{{ $websiteSetting->facebook_url }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white font-bold border-2 border-blue-800 hover:bg-blue-700">
                            Facebook
                        </a>
                    @endif
                    @if($websiteSetting->instagram_url)
                        <a href="{{ $websiteSetting->instagram_url }}" target="_blank" class="px-4 py-2 bg-pink-600 text-white font-bold border-2 border-pink-800 hover:bg-pink-700">
                            Instagram
                        </a>
                    @endif
                    @if($websiteSetting->twitter_url)
                        <a href="{{ $websiteSetting->twitter_url }}" target="_blank" class="px-4 py-2 bg-blue-400 text-white font-bold border-2 border-blue-600 hover:bg-blue-500">
                            Twitter
                        </a>
                    @endif
                    @if($websiteSetting->whatsapp_number)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $websiteSetting->whatsapp_number) }}" target="_blank" class="px-4 py-2 bg-green-500 text-white font-bold border-2 border-green-700 hover:bg-green-600">
                            WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div>
            <h2 class="text-3xl font-bold mb-8 pb-4 border-b-4 border-black" style="font-family: var(--font-heading);">Send us a Message</h2>

            <form id="contactForm" class="border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Name</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Email</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Phone (Optional)</label>
                    <input type="tel" name="phone" class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Subject</label>
                    <input type="text" name="subject" required class="w-full px-3 py-2 border-2 border-gray-300 font-bold">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Message</label>
                    <textarea name="message" required rows="6" class="w-full px-3 py-2 border-2 border-gray-300 font-bold"></textarea>
                </div>

                <button type="submit" class="w-full px-6 py-3 font-bold text-white border-2 border-black" style="background-color: var(--color-primary);">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="border-t-4 border-black bg-gray-900 text-white py-8 mt-16">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <p class="font-bold mb-2">{{ $restaurant->name }}</p>
        <p class="text-gray-400 text-sm">Powered by Kite Restaurant Management</p>
    </div>
</footer>

<script>
document.getElementById('contactForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    try {
        const response = await fetch('{{ route("website.contact.submit.path", $restaurant->slug) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        const result = await response.json();
        if (result.success) {
            alert(result.message);
            this.reset();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error sending message');
    }
});
</script>
@endsection
