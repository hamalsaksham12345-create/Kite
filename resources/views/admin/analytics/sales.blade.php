@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Header -->
    <div class="bg-black text-white p-8 border-b-4 border-black">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-5xl font-black mb-2">Sales Analytics</h1>
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

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="border-4 border-black bg-white p-6">
                <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-2">Total Sales</p>
                <p class="text-4xl font-black text-black">Rs {{ number_format($data['total_sales'], 2) }}</p>
            </div>
            <div class="border-4 border-black bg-white p-6">
                <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-2">Total Orders</p>
                <p class="text-4xl font-black text-black">{{ $data['total_orders'] }}</p>
            </div>
            <div class="border-4 border-black bg-white p-6">
                <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-2">Average Order Value</p>
                <p class="text-4xl font-black text-black">Rs {{ number_format($data['average_order_value'], 2) }}</p>
            </div>
        </div>

        <!-- Daily Sales Chart -->
        <div class="border-4 border-black bg-white mb-12">
            <div class="bg-black text-white p-6 border-b-4 border-black">
                <h2 class="text-3xl font-black">Daily Sales Trend</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($data['daily_sales'] as $day)
                        <div class="flex items-center gap-4">
                            <span class="font-bold text-gray-700 w-20">{{ \Carbon\Carbon::parse($day['date'])->format('M d') }}</span>
                            <div class="flex-1 bg-gray-200 h-8 border-2 border-gray-300 relative">
                                <div class="bg-blue-600 h-full" style="width: {{ min(100, ($day['total_sales'] / ($data['daily_sales']->max('total_sales') ?? 1)) * 100) }}%"></div>
                            </div>
                            <div class="text-right w-32">
                                <p class="font-bold text-black">Rs {{ number_format($day['total_sales'], 0) }}</p>
                                <p class="text-sm text-gray-600">{{ $day['order_count'] }} orders</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600">No data available</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Hourly Distribution -->
        <div class="border-4 border-black bg-white">
            <div class="bg-black text-white p-6 border-b-4 border-black">
                <h2 class="text-3xl font-black">Hourly Sales Distribution (Today)</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                    @forelse($data['hourly_distribution'] as $hour)
                        <div class="border-4 border-black p-4 text-center">
                            <p class="text-lg font-black text-black mb-2">{{ $hour['hour'] }}</p>
                            <div class="bg-gray-200 h-20 border-2 border-gray-300 mb-2 relative">
                                <div class="bg-green-600 absolute bottom-0 w-full" style="height: {{ min(100, ($hour['total_sales'] / ($data['hourly_distribution']->max('total_sales') ?? 1)) * 100) }}%"></div>
                            </div>
                            <p class="text-sm font-bold text-gray-600">{{ $hour['order_count'] }} orders</p>
                            <p class="text-sm font-bold text-black">Rs {{ number_format($hour['total_sales'], 0) }}</p>
                        </div>
                    @empty
                        <p class="text-gray-600">No data available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
