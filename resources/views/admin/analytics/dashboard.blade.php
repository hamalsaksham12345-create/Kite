@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Header -->
    <div class="bg-black text-white p-8 border-b-4 border-black">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-5xl font-black mb-2">Analytics Dashboard</h1>
            <p class="text-lg font-bold">{{ $restaurant->name }}</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto p-8">
        <!-- Date Range Filter -->
        <div class="mb-8 border-4 border-black bg-white p-6">
            <form method="GET" class="flex gap-4 items-end">
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="px-3 py-2 border-2 border-gray-300 font-bold">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="px-3 py-2 border-2 border-gray-300 font-bold">
                </div>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold border-2 border-blue-800 hover:bg-blue-700">
                    Filter
                </button>
            </form>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <!-- Today's Sales -->
            <div class="border-4 border-black bg-white p-6">
                <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-2">Today's Sales</p>
                <p class="text-4xl font-black text-black mb-2">Rs {{ number_format($quickStats['today']['sales'], 2) }}</p>
                <p class="text-xs font-bold {{ $quickStats['comparison']['sales_change_percent'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $quickStats['comparison']['sales_change_percent'] >= 0 ? '↑' : '↓' }} {{ abs($quickStats['comparison']['sales_change_percent']) }}% vs yesterday
                </p>
            </div>

            <!-- Today's Orders -->
            <div class="border-4 border-black bg-white p-6">
                <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-2">Today's Orders</p>
                <p class="text-4xl font-black text-black mb-2">{{ $quickStats['today']['orders'] }}</p>
                <p class="text-xs font-bold {{ $quickStats['comparison']['orders_change_percent'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $quickStats['comparison']['orders_change_percent'] >= 0 ? '↑' : '↓' }} {{ abs($quickStats['comparison']['orders_change_percent']) }}% vs yesterday
                </p>
            </div>

            <!-- Average Order Value -->
            <div class="border-4 border-black bg-white p-6">
                <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-2">Avg Order Value</p>
                <p class="text-4xl font-black text-black mb-2">Rs {{ number_format($data['sales']['average_order_value'], 2) }}</p>
                <p class="text-xs font-bold text-gray-600">Period average</p>
            </div>

            <!-- Total Period Sales -->
            <div class="border-4 border-black bg-white p-6">
                <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-2">Period Sales</p>
                <p class="text-4xl font-black text-black mb-2">Rs {{ number_format($data['sales']['total_sales'], 2) }}</p>
                <p class="text-xs font-bold text-gray-600">{{ $data['sales']['total_orders'] }} orders</p>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="mb-8 flex gap-4 border-b-4 border-black pb-4">
            <a href="{{ route('admin.path.analytics.dashboard', $restaurant->slug) }}" class="px-6 py-3 font-bold border-4 border-black bg-black text-white">
                Overview
            </a>
            <a href="{{ route('admin.path.analytics.sales', $restaurant->slug) }}" class="px-6 py-3 font-bold border-4 border-black bg-white text-black hover:bg-gray-100">
                Sales
            </a>
            <a href="{{ route('admin.path.analytics.menu', $restaurant->slug) }}" class="px-6 py-3 font-bold border-4 border-black bg-white text-black hover:bg-gray-100">
                Menu
            </a>
            <a href="{{ route('admin.path.analytics.orders', $restaurant->slug) }}" class="px-6 py-3 font-bold border-4 border-black bg-white text-black hover:bg-gray-100">
                Orders
            </a>
            <a href="{{ route('admin.path.analytics.staff', $restaurant->slug) }}" class="px-6 py-3 font-bold border-4 border-black bg-white text-black hover:bg-gray-100">
                Staff
            </a>
        </div>

        <!-- Top Selling Dishes -->
        <div class="mb-12 border-4 border-black bg-white">
            <div class="bg-black text-white p-6 border-b-4 border-black">
                <h2 class="text-3xl font-black">Top 5 Selling Dishes</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-4 border-black bg-gray-100">
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Dish Name</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Category</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Qty Sold</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['menu']['top_selling_dishes']->take(5) as $dish)
                            <tr class="border-b-2 border-gray-300 hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-black">{{ $dish['name'] }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $dish['category'] }}</td>
                                <td class="px-6 py-4 font-bold text-black">{{ $dish['quantity_sold'] }}</td>
                                <td class="px-6 py-4 font-bold text-black">Rs {{ number_format($dish['total_revenue'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-600">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Daily Sales Chart Data -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Daily Sales -->
            <div class="border-4 border-black bg-white">
                <div class="bg-black text-white p-6 border-b-4 border-black">
                    <h2 class="text-2xl font-black">Daily Sales Trend</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-2">
                        @forelse($data['sales']['daily_sales']->take(10) as $day)
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($day['date'])->format('M d') }}</span>
                                <div class="flex-1 mx-4 bg-gray-200 h-6 border-2 border-gray-300" style="width: 100px;">
                                    <div class="bg-blue-600 h-full" style="width: {{ min(100, ($day['total_sales'] / 10000) * 100) }}%"></div>
                                </div>
                                <span class="font-bold text-black">Rs {{ number_format($day['total_sales'], 0) }}</span>
                            </div>
                        @empty
                            <p class="text-gray-600">No data available</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Category Performance -->
            <div class="border-4 border-black bg-white">
                <div class="bg-black text-white p-6 border-b-4 border-black">
                    <h2 class="text-2xl font-black">Category Performance</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($data['menu']['category_performance'] as $category)
                            <div class="border-2 border-gray-300 p-4">
                                <div class="flex justify-between mb-2">
                                    <span class="font-bold text-black">{{ $category['category'] }}</span>
                                    <span class="font-bold text-black">Rs {{ number_format($category['total_revenue'], 0) }}</span>
                                </div>
                                <p class="text-sm text-gray-600">{{ $category['total_quantity'] }} items sold</p>
                            </div>
                        @empty
                            <p class="text-gray-600">No data available</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Peak Order Times -->
        <div class="border-4 border-black bg-white mb-12">
            <div class="bg-black text-white p-6 border-b-4 border-black">
                <h2 class="text-2xl font-black">Peak Order Times</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    @forelse($data['orders']['peak_times'] as $time)
                        <div class="border-4 border-black p-4 text-center">
                            <p class="text-2xl font-black text-black mb-2">{{ $time['hour'] }}</p>
                            <p class="text-sm font-bold text-gray-600">{{ $time['order_count'] }} orders</p>
                            <p class="text-sm font-bold text-black">Rs {{ number_format($time['total_sales'], 0) }}</p>
                        </div>
                    @empty
                        <p class="text-gray-600">No data available</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Staff Performance Summary -->
        <div class="border-4 border-black bg-white">
            <div class="bg-black text-white p-6 border-b-4 border-black">
                <h2 class="text-2xl font-black">Staff Performance</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="border-2 border-gray-300 p-4">
                        <p class="text-sm font-bold text-gray-600 mb-2">Total Staff</p>
                        <p class="text-3xl font-black text-black">{{ $data['staff']['activity_summary']['total_staff'] }}</p>
                    </div>
                    <div class="border-2 border-gray-300 p-4">
                        <p class="text-sm font-bold text-gray-600 mb-2">Waiters</p>
                        <p class="text-3xl font-black text-black">{{ $data['staff']['activity_summary']['waiters'] }}</p>
                    </div>
                    <div class="border-2 border-gray-300 p-4">
                        <p class="text-sm font-bold text-gray-600 mb-2">Chefs</p>
                        <p class="text-3xl font-black text-black">{{ $data['staff']['activity_summary']['chefs'] }}</p>
                    </div>
                    <div class="border-4 border-black bg-blue-50 p-4">
                        <p class="text-sm font-bold text-gray-600 mb-2">Efficiency Score</p>
                        <p class="text-3xl font-black text-blue-600">{{ $data['staff']['efficiency_score'] }}%</p>
                    </div>
                </div>

                <div class="border-t-4 border-black pt-6">
                    <h3 class="text-xl font-black text-black mb-4">Top Waiters</h3>
                    <div class="space-y-3">
                        @forelse($data['staff']['top_waiters']->take(3) as $waiter)
                            <div class="flex justify-between items-center border-2 border-gray-300 p-3">
                                <div>
                                    <p class="font-bold text-black">{{ $waiter['name'] }}</p>
                                    <p class="text-sm text-gray-600">{{ $waiter['total_orders'] }} orders</p>
                                </div>
                                <p class="font-bold text-black">Rs {{ number_format($waiter['total_revenue'], 0) }}</p>
                            </div>
                        @empty
                            <p class="text-gray-600">No data available</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
