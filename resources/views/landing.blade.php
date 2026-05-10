<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kite - Take Your Restaurant to the Skies</title>
    <meta name="description" content="Complete restaurant management platform with POS, Kitchen Display System, and branded webapp. Launch your restaurant's digital presence in minutes.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-white text-gray-900">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-xl border-b border-gray-100/50 shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-3xl font-black text-emerald-800">Kite</h1>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="#features" class="text-gray-700 hover:text-emerald-700 px-4 py-2 text-sm font-semibold transition-colors rounded-xl hover:bg-emerald-50">Features</a>
                        <a href="#process" class="text-gray-700 hover:text-emerald-700 px-4 py-2 text-sm font-semibold transition-colors rounded-xl hover:bg-emerald-50">How it Works</a>
                        <a href="#pricing" class="text-gray-700 hover:text-emerald-700 px-4 py-2 text-sm font-semibold transition-colors rounded-xl hover:bg-emerald-50">Pricing</a>
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-emerald-700 px-4 py-2 text-sm font-semibold transition-colors rounded-xl hover:bg-emerald-50">Sign In</a>
                        <a href="{{ route('register') }}" class="bg-emerald-700 hover:bg-emerald-800 text-white px-8 py-3 rounded-2xl text-sm font-bold transition-all hover:scale-105 shadow-lg">Get Started</a>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-emerald-700 p-2 rounded-xl hover:bg-emerald-50 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white/95 backdrop-blur-xl border-t border-gray-100/50">
            <div class="px-4 pt-4 pb-6 space-y-2">
                <a href="#features" class="block px-4 py-3 text-base font-semibold text-gray-700 hover:text-emerald-700 rounded-xl hover:bg-emerald-50 transition-colors">Features</a>
                <a href="#process" class="block px-4 py-3 text-base font-semibold text-gray-700 hover:text-emerald-700 rounded-xl hover:bg-emerald-50 transition-colors">How it Works</a>
                <a href="#pricing" class="block px-4 py-3 text-base font-semibold text-gray-700 hover:text-emerald-700 rounded-xl hover:bg-emerald-50 transition-colors">Pricing</a>
                <a href="{{ route('login') }}" class="block px-4 py-3 text-base font-semibold text-gray-700 hover:text-emerald-700 rounded-xl hover:bg-emerald-50 transition-colors">Sign In</a>
                <a href="{{ route('register') }}" class="block mx-4 mt-4 bg-emerald-700 hover:bg-emerald-800 text-white px-8 py-4 rounded-2xl text-center font-bold transition-colors">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center">
                <h1 class="text-6xl md:text-8xl font-black text-gray-900 leading-[0.9] mb-8 tracking-tight">
                    Take your restaurant<br>
                    <span class="bg-gradient-to-r from-emerald-700 to-emerald-600 bg-clip-text text-transparent">to the skies.</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 max-w-4xl mx-auto mb-16 leading-relaxed font-medium">
                    Complete restaurant management ecosystem with POS system, Kitchen Display, and branded webapp. 
                    Launch your digital restaurant experience in minutes, not months.
                </p>
                <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                    <a href="{{ route('register') }}" class="bg-emerald-700 hover:bg-emerald-800 text-white px-10 py-5 rounded-2xl text-xl font-bold transition-all transform hover:scale-105 shadow-xl hover:shadow-2xl">
                        Start Free Trial
                    </a>
                    <a href="#demo" class="text-emerald-700 hover:text-emerald-800 px-10 py-5 rounded-2xl text-xl font-bold border-2 border-emerald-700 hover:border-emerald-800 transition-colors bg-white hover:bg-gray-50">
                        Watch Demo
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section id="process" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-5xl md:text-6xl font-black text-gray-900 mb-8 tracking-tight">
                    Launch in <span class="bg-gradient-to-r from-emerald-700 to-emerald-600 bg-clip-text text-transparent">3 simple steps</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto font-medium">
                    From registration to live restaurant webapp in under 24 hours
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                <!-- Step 1 -->
                <div class="text-center group">
                    <div class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 group-hover:shadow-xl transition-all duration-300 group-hover:-translate-y-2">
                        <div class="w-20 h-20 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-3xl flex items-center justify-center mx-auto mb-8">
                            <span class="text-3xl font-black text-emerald-700">1</span>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 mb-6">Register & Subscribe</h3>
                        <p class="text-gray-600 leading-relaxed text-lg">
                            Create your restaurant profile, choose your custom URL, and select a subscription plan that fits your needs.
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="text-center group">
                    <div class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 group-hover:shadow-xl transition-all duration-300 group-hover:-translate-y-2">
                        <div class="w-20 h-20 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-3xl flex items-center justify-center mx-auto mb-8">
                            <span class="text-3xl font-black text-emerald-700">2</span>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 mb-6">Get Verified</h3>
                        <p class="text-gray-600 leading-relaxed text-lg">
                            Our team reviews your application to ensure quality and compliance. Most approvals happen within 24 hours.
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="text-center group">
                    <div class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 group-hover:shadow-xl transition-all duration-300 group-hover:-translate-y-2">
                        <div class="w-20 h-20 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-3xl flex items-center justify-center mx-auto mb-8">
                            <span class="text-3xl font-black text-emerald-700">3</span>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 mb-6">Launch Your Webapp</h3>
                        <p class="text-gray-600 leading-relaxed text-lg">
                            Your branded restaurant webapp goes live with POS system, kitchen display, and customer ordering interface.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Grid -->
    <section id="features" class="py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-5xl md:text-6xl font-black text-gray-900 mb-8 tracking-tight">
                    Everything you need to <span class="bg-gradient-to-r from-emerald-700 to-emerald-600 bg-clip-text text-transparent">succeed</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto font-medium">
                    Powerful features designed specifically for modern restaurants
                </p>
            </div>

            <!-- Bento Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Individual Branding - Large Card -->
                <div class="lg:col-span-2 bg-white rounded-3xl p-10 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                    <div class="flex items-start justify-between mb-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                            </svg>
                        </div>
                        <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center">
                            <span class="text-gray-400 text-sm font-medium">Logo</span>
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 mb-4">Individual Branding</h3>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        Custom colors, logos, and domain names. Your restaurant, your brand, your way. Stand out with a unique digital presence that reflects your identity.
                    </p>
                </div>

                <!-- POS Interface -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-4">POS Interface</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Intuitive point-of-sale system for waiters. Take orders, manage tables, and process payments with ease.
                    </p>
                </div>

                <!-- Live Kitchen Display -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-4">Kitchen Display</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Real-time kitchen display system for chefs. Track order status and manage preparation times.
                    </p>
                </div>

                <!-- Multi-Location Ready -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-4">Multi-Location</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Perfect for restaurant chains. Manage multiple locations with centralized control.
                    </p>
                </div>

                <!-- Role-Based Access - Large Card -->
                <div class="lg:col-span-2 bg-white rounded-3xl p-10 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                    <div class="flex items-start justify-between mb-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <div class="flex space-x-2">
                            <div class="w-8 h-8 bg-emerald-100 rounded-full"></div>
                            <div class="w-8 h-8 bg-emerald-200 rounded-full"></div>
                            <div class="w-8 h-8 bg-emerald-300 rounded-full"></div>
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 mb-4">Role-Based Access Control</h3>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        Secure access control for owners, managers, waiters, and kitchen staff. Everyone gets exactly what they need, nothing more. Complete permission management system.
                    </p>
                </div>

                <!-- Real-Time Updates -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-emerald-50 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 mb-4">Real-Time Sync</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Instant synchronization across all devices. Orders and updates happen in real-time.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-br from-emerald-700 to-emerald-800">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-5xl md:text-6xl font-black text-white mb-8 tracking-tight">
                Ready to take flight?
            </h2>
            <p class="text-xl text-emerald-100 mb-12 leading-relaxed font-medium">
                Join hundreds of restaurants already using Kite to streamline their operations and delight their customers.
            </p>
            <a href="{{ route('register') }}" class="bg-white hover:bg-gray-100 text-emerald-700 px-10 py-5 rounded-2xl text-xl font-black transition-all transform hover:scale-105 shadow-xl hover:shadow-2xl inline-block">
                Start Your Free Trial
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <h3 class="text-2xl font-black text-emerald-400 mb-4">Kite</h3>
                    <p class="text-gray-400 leading-relaxed mb-4">
                        The complete restaurant management platform that helps you soar above the competition.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Product</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#pricing" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Documentation</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Company</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">About</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Support</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Kite Restaurant SaaS. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>