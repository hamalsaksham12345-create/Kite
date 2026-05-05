<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Kite - Multi-Tenant Restaurant SaaS</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="bg-gray-50 text-black/50 min-h-screen">
            <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-blue-500 selection:text-white">
                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                    <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                        <div class="flex lg:justify-center lg:col-start-2">
                            <h1 class="text-6xl font-bold text-blue-600">Kite</h1>
                        </div>
                        @auth
                            <nav class="-mx-3 flex flex-1 justify-end">
                                <a href="{{ url('/') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-blue-500">
                                    Dashboard
                                </a>
                            </nav>
                        @else
                            <nav class="-mx-3 flex flex-1 justify-end">
                                <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-blue-500">
                                    Log in
                                </a>
                                <a href="{{ route('register') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-blue-500">
                                    Register
                                </a>
                            </nav>
                        @endauth
                    </header>

                    <main class="mt-6">
                        <div class="text-center">
                            <h2 class="text-3xl font-bold text-gray-900 mb-4">Multi-Tenant Restaurant SaaS Platform</h2>
                            <p class="text-lg text-gray-600 mb-8">Manage multiple restaurants with role-based access control, dynamic theming, and comprehensive POS system.</p>
                        </div>

                        <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                            <!-- System Status -->
                            <div class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-md ring-1 ring-white/10">
                                <div class="relative flex items-center gap-6 lg:items-end">
                                    <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-blue-50">
                                        <svg class="size-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="pt-3 sm:pt-5">
                                        <h2 class="text-xl font-semibold text-gray-900">System Status</h2>
                                        <p class="mt-4 text-sm/relaxed text-gray-600">
                                            @php
                                                $restaurantCount = \App\Models\Restaurant::count();
                                                $userCount = \App\Models\User::count();
                                                $categoryCount = \App\Models\Category::count();
                                                $menuItemCount = \App\Models\MenuItem::count();
                                            @endphp
                                            
                                            <strong>{{ $restaurantCount }}</strong> restaurants registered<br>
                                            <strong>{{ $userCount }}</strong> total users<br>
                                            <strong>{{ $categoryCount }}</strong> menu categories<br>
                                            <strong>{{ $menuItemCount }}</strong> menu items
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Features -->
                            <div class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-md ring-1 ring-white/10">
                                <div class="relative flex items-center gap-6 lg:items-end">
                                    <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-green-50">
                                        <svg class="size-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="pt-3 sm:pt-5">
                                        <h2 class="text-xl font-semibold text-gray-900">Key Features</h2>
                                        <ul class="mt-4 text-sm/relaxed text-gray-600 space-y-1">
                                            <li>✓ Multi-tenant architecture</li>
                                            <li>✓ Role-based access control</li>
                                            <li>✓ Dynamic restaurant theming</li>
                                            <li>✓ POS system for waiters</li>
                                            <li>✓ Kitchen display system</li>
                                            <li>✓ Admin CMS dashboard</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Demo Credentials -->
                            <div class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-md ring-1 ring-white/10 lg:col-span-2">
                                <div class="relative flex items-center gap-6 lg:items-end">
                                    <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-yellow-50">
                                        <svg class="size-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                        </svg>
                                    </div>
                                    <div class="pt-3 sm:pt-5 flex-1">
                                        <h2 class="text-xl font-semibold text-gray-900">Demo Credentials</h2>
                                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="bg-gray-50 p-4 rounded">
                                                <h3 class="font-semibold text-gray-800">Super Admin</h3>
                                                <p class="text-sm text-gray-600">
                                                    Email: <code class="bg-gray-200 px-1 rounded">admin@kite.test</code><br>
                                                    Password: <code class="bg-gray-200 px-1 rounded">password</code>
                                                </p>
                                            </div>
                                            <div class="bg-gray-50 p-4 rounded">
                                                <h3 class="font-semibold text-gray-800">Restaurant Admin</h3>
                                                <p class="text-sm text-gray-600">
                                                    Email: <code class="bg-gray-200 px-1 rounded">admin@demopizza.com</code><br>
                                                    Password: <code class="bg-gray-200 px-1 rounded">password</code>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 text-center">
                            @guest
                                <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Get Started
                                </a>
                            @else
                                <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Go to Dashboard
                                </a>
                            @endguest
                        </div>
                    </main>

                    <footer class="py-16 text-center text-sm text-black">
                        Kite Restaurant SaaS Platform - Built with Laravel {{ app()->version() }}
                    </footer>
                </div>
            </div>
        </div>
    </body>
</html>