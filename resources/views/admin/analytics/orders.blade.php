@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Header -->
    <div class="bg-black text-white p-8 border-b-4 border-black">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-5xl font-black mb-2">Order Analytics</h1>
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
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Period</label>
                    <select name="period" class="px-3 py-2 border-2 border-gray-300 font-bold">
                        <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                </div>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold border-2 border-blue-800 hover:bg-blue-700">
                    Filter
                </button>
            </form>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            <div class="border-4 border-black bg-white p-6">
                <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-2">Avg Completion Time</p>
                <p class="text-4xl font-black text-black">{{ $data['average_completion_time'] }} min</p>
            </div>
            <div class="border-4 border-black bg-white p-6">
                <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-2">Peak Order Time</p>
                @if($data['peak_times']->first())
                    <p class="text-4xl font-black text-black">{{ $data['peak_times']->first()['hour'] }}</p>
                @else
                    <p class="text-4xl font-black text-black">N/A</p>
                @endif
            </div>
        </div>

        <!-- Order Trends -->
        <div class="border-4 border-black bg-white mb-12">
            <div class="bg-black text-white p-6 border-b-4 border-black">
                <h2 class="text-3xl font-black">Order Trends ({{ ucfirst($period) }})</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($data['order_trends'] as $trend)
                        <div class="flex items-center gap-4">
                            <span class="font-bold text-gray-700 w-32">{{ $trend['period'] }}</span>
                            <div class="flex-1 bg-gray-200 h-8 border-2 border-gray-300 relative">
                                <div class="bg-purple-600 h-full" style="width: {{ min(100, ($trend['total_sales'] / ($data['order_trends']->max('total_sales') ?? 1)) * 100) }}%"></div>
                            </div>
                            <div class="text-right w-40">
                                <p class="font-bold text-black">{{ $trend['order_count'] }} orders</p>
                                <p class="text-sm text-gray-600">Rs {{ number_format($trend['total_sales'], 0) }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-600">No data available</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Peak Times -->
        <div class="border-4 border-black bg-white mb-12">
            <div class="bg-black text-white p-6 border-b-4 border-black">
                <h2 class="text-3xl font-black">Peak Order Times</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    @forelse($data['peak_times'] as $time)
                        <div class="border-4 border-black p-4 text-center">
                            <p class="text-2xl font-black text-black mb-2">{{ $time['hour'] }}</p>
                            <div class="bg-gray-200 h-16 border-2 border-gray-300 mb-2 relative">
                                <div class="bg-orange-600 absolute bottom-0 w-full" style="height: {{ min(100, ($time['order_count'] / ($data['peak_times']->max('order_count') ?? 1)) * 100) }}%"></div>
                            </div>
                            <p class="text-sm font-bold text-gray-600">{{ $time['order_count'] }} orders</p>
                            <p class="text-sm font-bold text-black">Rs {{ number_format($time['total_sales'], 0) }}</p>
                        </div>
                    @empty
                        <p class="text-gray-600">No data available</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Order Value Distribution -->
        <div class="border-4 border-black bg-white mb-12">
            <div class="bg-black text-white p-6 border-b-4 border-black">
                <h2 class="text-3xl font-black">Order Value Distribution</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($data['value_distribution'] as $range)
                        <div class="flex items-center gap-4">
                            <span class="font-bold text-gray-700 w-32">{{ $range['range'] }}</span>
                            <div class="flex-1 bg-gray-200 h-8 border-2 border-gray-300 relative">
                                <div class="bg-indigo-600 h-full" style="width: {{ min(100, ($range['count'] / ($data['value_distribution']->max('count') ?? 1)) * 100) }}%"></div>
                            </div>
                            <span class="font-bold text-black w-16 text-right">{{ $range['count'] }}</span>
                        </div>
                    @empty
                        <p class="text-gray-600">No data available</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="border-4 border-black bg-white">
            <div class="bg-black text-white p-6 border-b-4 border-black">
                <h2 class="text-3xl font-black">Order Status Distribution</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    @forelse($data['status_distribution'] as $status)
                        <div class="border-4 border-black p-4 text-center">
                            <p class="text-lg font-black text-black mb-2 capitalize">{{ $status['status'] }}</p>
                            <p class="text-3xl font-black text-blue-600">{{ $status['count'] }}</p>
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
