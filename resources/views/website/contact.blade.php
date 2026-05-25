@extends('layouts.master')

@section('title', 'Contact ' . $restaurant->name)
@section('description', 'Get in touch with ' . $restaurant->name)

@section('content')
<div class="pt-32 pb-24 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Breadcrumb -->
        <div class="mb-12">
            <a href="{{ route('website.index.path', $restaurant->slug) }}" class="text-emerald-700 hover:text-emerald-800 font-semibold">← Back to Home</a>
        </div>

        <!-- Header -->
        <div class="mb-16">
            <h1 class="text-6xl md:text-7xl font-black text-gray-900 leading-[0.9] mb-8 tracking-tight">
                Get in <span class="bg-gradient-to-r from-emerald-700 to-emerald-600 bg-clip-text text-transparent">Touch</span>
            </h1>
            <p class="text-xl text-gray-600 font-medium">
                We'd love to hear from you. Send us a message and we'll respond as soon as possible.
            </p>
        </div>

        <!-- Contact Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
            <!-- Contact Information -->
            <div class="space-y-6">
                <h2 class="text-3xl font-black text-gray-900 mb-8">Contact Information</h2>

                @if($websiteSetting->phone)
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:shadow-xl transition-all">
                        <p class="text-sm font-semibold text-gray-500 mb-2">PHONE</p>
                        <a href="tel:{{ $websiteSetting->phone }}" class="text-2xl font-black text-emerald-700 hover:text-emerald-800">{{ $websiteSetting->phone }}</a>
                    </div>
                @endif

                @if($websiteSetting->email)
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:shadow-xl transition-all">
                        <p class="text-sm font-semibold text-gray-500 mb-2">EMAIL</p>
                        <a href="mailto:{{ $websiteSetting->email }}" class="text-2xl font-black text-emerald-700 hover:text-emerald-800 break-all">{{ $websiteSetting->email }}</a>
                    </div>
                @endif

                @if($websiteSetting->address)
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:shadow-xl transition-all">
                        <p class="text-sm font-semibold text-gray-500 mb-2">ADDRESS</p>
                        <p class="text-lg font-bold text-gray-900">{{ $websiteSetting->address }}</p>
                        @if($websiteSetting->city)
                            <p class="text-lg font-bold text-gray-900">{{ $websiteSetting->city }}@if($websiteSetting->state), {{ $websiteSetting->state }}@endif</p>
                        @endif
                    </div>
                @endif

                <!-- Social Media & WhatsApp -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <p class="text-sm font-semibold text-gray-500 mb-6">CONNECT WITH US</p>
                    <div class="flex flex-wrap gap-3">
                        @if($websiteSetting->facebook_url)
                            <a href="{{ $websiteSetting->facebook_url }}" target="_blank" class="px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition-colors">
                                Facebook
                            </a>
                        @endif
                        @if($websiteSetting->instagram_url)
                            <a href="{{ $websiteSetting->instagram_url }}" target="_blank" class="px-6 py-3 bg-pink-600 text-white rounded-xl font-bold hover:bg-pink-700 transition-colors">
                                Instagram
                            </a>
                        @endif
                        @if($websiteSetting->twitter_url)
                            <a href="{{ $websiteSetting->twitter_url }}" target="_blank" class="px-6 py-3 bg-blue-400 text-white rounded-xl font-bold hover:bg-blue-500 transition-colors">
                                Twitter
                            </a>
                        @endif
                        @if($websiteSetting->whatsapp_number)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $websiteSetting->whatsapp_number) }}" target="_blank" class="px-6 py-3 bg-green-500 text-white rounded-xl font-bold hover:bg-green-600 transition-colors">
                                💬 WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div>
                <h2 class="text-3xl font-black text-gray-900 mb-8">Send us a Message</h2>

                <form id="contactForm" class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Name</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Phone (Optional)</label>
                        <input type="tel" name="phone" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Subject</label>
                        <input type="text" name="subject" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Message</label>
                        <textarea name="message" required rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base"></textarea>
                    </div>

                    <button type="submit" class="w-full px-6 py-4 font-bold text-white bg-emerald-700 hover:bg-emerald-800 rounded-2xl transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
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
        } else {
            alert('Error: ' + (result.message || 'Failed to send message'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error sending message. Please try again.');
    }
});
</script>
@endpush
@endsection
