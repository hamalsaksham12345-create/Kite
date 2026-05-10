<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Verification Pending - Kite Restaurant SaaS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-black text-emerald-700">Kite</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-gray-600 hover:text-emerald-600 transition-colors">
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl w-full">
                <!-- Status Card -->
                <div class="bg-white rounded-3xl shadow-lg border border-gray-200 p-8 text-center">
                    <!-- Icon -->
                    <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl font-black text-gray-900 mb-4">
                        Verification in Progress
                    </h1>

                    <!-- Message -->
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        Your application is being reviewed by the Kite Team. 
                        You will be notified once verified and your restaurant webapp will go live.
                    </p>

                    <!-- Restaurant Info -->
                    <div class="bg-gray-50 rounded-2xl p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Restaurant Details</h3>
                        <div class="space-y-3 text-left">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Restaurant Name:</span>
                                <span class="font-medium">{{ auth()->user()->restaurant->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">URL:</span>
                                <span class="font-medium text-emerald-600">kite.test/{{ auth()->user()->restaurant->slug }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Plan:</span>
                                <span class="font-medium capitalize">{{ str_replace('_', ' ', auth()->user()->restaurant->subscription_plan) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Submitted:</span>
                                <span class="font-medium">{{ auth()->user()->restaurant->created_at->format('M d, Y \a\t g:i A') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="text-left mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">Verification Process</h3>
                        <div class="space-y-4">
                            <!-- Step 1 - Completed -->
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Registration Completed</p>
                                    <p class="text-sm text-gray-600">Your restaurant profile has been submitted</p>
                                </div>
                            </div>

                            <!-- Step 2 - In Progress -->
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center mr-4">
                                    <div class="w-3 h-3 bg-white rounded-full animate-pulse"></div>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Under Review</p>
                                    <p class="text-sm text-gray-600">Our team is verifying your information</p>
                                </div>
                            </div>

                            <!-- Step 3 - Pending -->
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-white text-sm font-semibold">3</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-500">Activation</p>
                                    <p class="text-sm text-gray-400">Your restaurant webapp will go live</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expected Timeline -->
                    <div class="bg-emerald-50 rounded-2xl p-6 mb-8">
                        <div class="flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h4 class="font-semibold text-emerald-900">Expected Timeline</h4>
                        </div>
                        <p class="text-emerald-800">
                            Most applications are reviewed within <strong>24 hours</strong>. 
                            You'll receive an email notification once your restaurant is approved and activated.
                        </p>
                    </div>

                    <!-- Contact Support -->
                    <div class="text-center">
                        <p class="text-gray-600 mb-4">
                            Have questions about your application?
                        </p>
                        <a href="mailto:support@kite.test" 
                           class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Contact Support
                        </a>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        While you wait, you can check out our 
                        <a href="#" class="text-emerald-600 hover:text-emerald-700 font-medium">getting started guide</a> 
                        to learn more about Kite's features.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto-refresh script -->
    <script>
        // Auto-refresh the page every 30 seconds to check for approval
        setTimeout(function() {
            window.location.reload();
        }, 30000);
    </script>
</body>
</html>