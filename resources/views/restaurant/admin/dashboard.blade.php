@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Header -->
    <div class="bg-black text-white p-8 border-b-4 border-black">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-5xl font-black mb-2">Admin Dashboard</h1>
            <p class="text-lg font-bold">{{ $currentRestaurant->name }}</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto p-8">
        <!-- Metrics Bento Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Total Revenue Card -->
            <div class="bg-white border-4 border-black p-8 hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-4">Total Revenue</p>
                <p class="text-5xl font-black text-black mb-2">Rs {{ number_format($totalRevenue, 2) }}</p>
                <p class="text-xs font-bold text-gray-600">From completed orders</p>
            </div>

            <!-- Total Orders Card -->
            <div class="bg-white border-4 border-black p-8 hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-4">Lifetime Orders</p>
                <p class="text-5xl font-black text-black mb-2">{{ $totalOrders }}</p>
                <p class="text-xs font-bold text-gray-600">Total orders placed</p>
            </div>

            <!-- Active Orders Card -->
            <div class="bg-white border-4 border-black p-8 hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-4">Active Orders</p>
                <p class="text-5xl font-black text-black mb-2">{{ $activeOrders }}</p>
                <p class="text-xs font-bold text-gray-600">In kitchen or POS pipeline</p>
            </div>
        </div>

        <!-- Data Presentation Blocks -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Top 5 Popular Dishes Block -->
            <div class="lg:col-span-2">
                <div class="bg-white border-4 border-black">
                    <!-- Header -->
                    <div class="bg-black text-white p-6 border-b-4 border-black">
                        <h2 class="text-3xl font-black">Top 5 Popular Dishes</h2>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-4 border-black bg-gray-100">
                                    <th class="px-6 py-4 text-left text-sm font-black text-black uppercase tracking-widest">Dish Name</th>
                                    <th class="px-6 py-4 text-left text-sm font-black text-black uppercase tracking-widest">Units Sold</th>
                                    <th class="px-6 py-4 text-left text-sm font-black text-black uppercase tracking-widest">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topSellingItems as $item)
                                    <tr class="border-b-2 border-gray-300 hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="text-lg font-black text-black">{{ $item['name'] }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-2xl font-black text-black">{{ $item['total_quantity'] }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-lg font-black text-black">Rs {{ number_format($item['total_revenue'], 2) }}</p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center">
                                            <p class="text-lg font-bold text-gray-600">No order data available yet</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Links Navigation Panel -->
            <div>
                <div class="bg-white border-4 border-black">
                    <!-- Header -->
                    <div class="bg-black text-white p-6 border-b-4 border-black">
                        <h2 class="text-3xl font-black">Quick Links</h2>
                    </div>

                    <!-- Links -->
                    <div class="p-6 space-y-4">
                        <!-- Categories Link -->
                        <a href="{{ route('admin.path.categories.index', $currentRestaurant->slug) }}" class="block w-full px-6 py-4 bg-blue-600 border-4 border-black text-white font-black text-center uppercase hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                            Manage Categories
                        </a>

                        <!-- Menu Items Link -->
                        <a href="{{ route('admin.path.menu-items.index', $currentRestaurant->slug) }}" class="block w-full px-6 py-4 bg-emerald-600 border-4 border-black text-white font-black text-center uppercase hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                            Manage Menu Items
                        </a>

                        <!-- Staff Registration Link -->
                        <a href="{{ route('staff.register.form', $currentRestaurant->slug) }}" class="block w-full px-6 py-4 bg-purple-600 border-4 border-black text-white font-black text-center uppercase hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                            Invite Staff
                        </a>
                    </div>
                </div>

                <!-- Additional Info Block -->
                <div class="bg-gray-50 border-4 border-black p-6 mt-8">
                    <p class="text-xs font-black text-black uppercase tracking-widest mb-3">Restaurant Status</p>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <p class="text-sm font-bold text-gray-700">Verification</p>
                            <span class="inline-block px-3 py-1 bg-emerald-100 border-2 border-emerald-600 text-emerald-800 font-black text-xs uppercase">Verified</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-sm font-bold text-gray-700">Status</p>
                            @if($currentRestaurant->is_active)
                                <span class="inline-block px-3 py-1 bg-emerald-100 border-2 border-emerald-600 text-emerald-800 font-black text-xs uppercase">Active</span>
                            @else
                                <span class="inline-block px-3 py-1 bg-red-100 border-2 border-red-600 text-red-800 font-black text-xs uppercase">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
