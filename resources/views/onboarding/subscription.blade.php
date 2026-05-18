<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Choose Your Plan - Kite Restaurant SaaS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="max-w-4xl mx-auto text-center mb-12">
            <div class="mb-6">
                <a href="{{ route('home') }}" class="text-3xl font-black text-emerald-700">Kite</a>
            </div>
            <h1 class="text-4xl font-black text-gray-900 mb-4">
                Choose your plan
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Select the subscription plan that best fits your restaurant's needs. You can change or cancel anytime.
            </p>
        </div>

        <!-- Pricing Cards -->
        <div class="max-w-6xl mx-auto" x-data="{ selectedPlan: 'semi_annual' }">
            <form method="POST" action="{{ route('onboarding.subscription') }}">
                @csrf
                <input type="hidden" name="plan" x-model="selectedPlan">

                <div class="grid md:grid-cols-3 gap-8 mb-8">
                    <!-- Monthly Plan -->
                    <div class="relative bg-white rounded-3xl shadow-sm border border-gray-200 p-8 cursor-pointer transition-all hover:shadow-lg"
                         :class="selectedPlan === 'monthly' ? 'ring-2 ring-emerald-500 border-emerald-500' : ''"
                         @click="selectedPlan = 'monthly'">
                        <div class="text-center">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Monthly</h3>
                            <p class="text-gray-600 mb-6">Perfect for trying out Kite</p>
                            
                            <div class="mb-6">
                                <span class="text-4xl font-black text-gray-900">Rs 2,999</span>
                                <span class="text-gray-600">/month</span>
                            </div>

                            <ul class="text-left space-y-3 mb-8">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700">Complete POS System</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700">Kitchen Display System</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700">Custom Branding</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700">Email Support</span>
                                </li>
                            </ul>

                            <div class="absolute top-4 right-4">
                                <div class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center"
                                     :class="selectedPlan === 'monthly' ? 'border-emerald-500 bg-emerald-500' : ''">
                                    <div class="w-2 h-2 rounded-full bg-white" x-show="selectedPlan === 'monthly'"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Semi-Annual Plan (Most Popular) -->
                    <div class="relative bg-white rounded-3xl shadow-lg border-2 border-emerald-500 p-8 cursor-pointer transition-all hover:shadow-xl"
                         :class="selectedPlan === 'semi_annual' ? 'ring-2 ring-emerald-500' : ''"
                         @click="selectedPlan = 'semi_annual'">
                        <!-- Popular Badge -->
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                            <span class="bg-emerald-500 text-white px-4 py-1 rounded-full text-sm font-semibold">Most Popular</span>
                        </div>

                        <div class="text-center">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Semi-Annual</h3>
                            <p class="text-gray-600 mb-6">Best value for growing restaurants</p>
                            
                            <div class="mb-2">
                                <span class="text-4xl font-black text-gray-900">Rs 14,999</span>
                                <span class="text-gray-600">/6 months</span>
                            </div>
                            <p class="text-sm text-emerald-600 font-semibold mb-6">Save Rs 3,000 compared to monthly</p>

                            <ul class="text-left space-y-3 mb-8">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700">Everything in Monthly</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700">Priority Support</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700">Advanced Analytics</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700">Staff Management</span>
                                </li>
                            </ul>

                            <div class="absolute top-4 right-4">
                                <div class="w-6 h-6 rounded-full border-2 border-emerald-500 bg-emerald-500 flex items-center justify-center">
                                    <div class="w-2 h-2 rounded-full bg-white"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Annual Plan -->
                    <div class="relative bg-white rounded-3xl shadow-sm border border-gray-200 p-8 cursor-pointer transition-all hover:shadow-lg"
                         :class="selectedPlan === 'annual' ? 'ring-2 ring-emerald-500 border-emerald-500' : ''"
                         @click="selectedPlan = 'annual'">
                        <div class="text-center">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Annual</h3>
                            <p class="text-gray-600 mb-6">Maximum savings for established restaurants</p>
                            
                            <div class="mb-2">
                                <span class="text-4xl font-black text-gray-900">Rs 29,999</span>
                                <span class="text-gray-600">/year</span>
                            </div>
                            <p class="text-sm text-emerald-600 font-semibold mb-6">Save Rs 6,000 compared to monthly</p>

                            <ul class="text-left space-y-3 mb-8">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700">Everything in Semi-Annual</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700">Phone Support</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700">Custom Integrations</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-emerald-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-gray-700">Dedicated Account Manager</span>
                                </li>
                            </ul>

                            <div class="absolute top-4 right-4">
                                <div class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center"
                                     :class="selectedPlan === 'annual' ? 'border-emerald-500 bg-emerald-500' : ''">
                                    <div class="w-2 h-2 rounded-full bg-white" x-show="selectedPlan === 'annual'"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Section -->
                <div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-sm border border-gray-200 p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Payment Information</h3>
                    
                    <!-- Payment Placeholder -->
                    <div class="bg-gray-50 rounded-2xl p-8 text-center mb-6">
                        <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Secure Payment Processing</h4>
                        <p class="text-gray-600 mb-4">
                            Payment integration will be implemented here.<br>
                            For demo purposes, clicking "Complete Registration" will proceed to verification.
                        </p>
                        <div class="text-sm text-gray-500">
                            <p>✓ SSL Encrypted</p>
                            <p>✓ PCI Compliant</p>
                            <p>✓ 30-day Money Back Guarantee</p>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-gray-50 rounded-2xl p-6 mb-6">
                        <h4 class="font-semibold text-gray-900 mb-4">Order Summary</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Restaurant: {{ auth()->user()->restaurant->name ?? 'Your Restaurant' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Plan:</span>
                                <span class="font-medium" x-text="selectedPlan === 'monthly' ? 'Monthly Plan' : selectedPlan === 'semi_annual' ? 'Semi-Annual Plan' : 'Annual Plan'"></span>
                            </div>
                            <div class="flex justify-between text-lg font-semibold pt-2 border-t border-gray-200">
                                <span>Total:</span>
                                <span x-text="selectedPlan === 'monthly' ? 'Rs 2,999' : selectedPlan === 'semi_annual' ? 'Rs 14,999' : 'Rs 29,999'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full bg-emerald-700 hover:bg-emerald-800 text-white py-4 px-6 rounded-xl text-lg font-semibold transition-colors">
                        Complete Registration
                    </button>

                    <p class="text-center text-sm text-gray-500 mt-4">
                        By continuing, you agree to our Terms of Service and Privacy Policy
                    </p>
                </div>
            </form>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-8">
            <a href="{{ route('onboarding.register') }}" class="text-gray-600 hover:text-emerald-600 transition-colors">
                ← Back to registration
            </a>
        </div>
    </div>
</body>
</html>