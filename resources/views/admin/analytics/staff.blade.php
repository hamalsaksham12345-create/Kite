@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Header -->
    <div class="bg-black text-white p-8 border-b-4 border-black">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-5xl font-black mb-2">Staff Performance Analytics</h1>
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

        <!-- Activity Summary -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-12">
            <div class="border-4 border-black bg-white p-6">
                <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-2">Total Staff</p>
                <p class="text-4xl font-black text-black">{{ $data['activity_summary']['total_staff'] }}</p>
            </div>
            <div class="border-4 border-black bg-white p-6">
                <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-2">Waiters</p>
                <p class="text-4xl font-black text-black">{{ $data['activity_summary']['waiters'] }}</p>
            </div>
            <div class="border-4 border-black bg-white p-6">
                <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-2">Chefs</p>
                <p class="text-4xl font-black text-black">{{ $data['activity_summary']['chefs'] }}</p>
            </div>
            <div class="border-4 border-black bg-white p-6">
                <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-2">Managers</p>
                <p class="text-4xl font-black text-black">{{ $data['activity_summary']['managers'] }}</p>
            </div>
            <div class="border-4 border-black bg-blue-50 p-6">
                <p class="text-sm font-black text-gray-600 uppercase tracking-widest mb-2">Efficiency Score</p>
                <p class="text-4xl font-black text-blue-600">{{ $data['efficiency_score'] }}%</p>
            </div>
        </div>

        <!-- Waiter Performance -->
        <div class="border-4 border-black bg-white mb-12">
            <div class="bg-black text-white p-6 border-b-4 border-black">
                <h2 class="text-3xl font-black">Waiter Performance</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-4 border-black bg-gray-100">
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Name</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Total Orders</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Total Revenue</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Avg Order Value</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Orders/Day</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['waiter_performance'] as $waiter)
                            <tr class="border-b-2 border-gray-300 hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-black">{{ $waiter['name'] }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $waiter['total_orders'] }}</td>
                                <td class="px-6 py-4 font-bold text-black">Rs {{ number_format($waiter['total_revenue'], 2) }}</td>
                                <td class="px-6 py-4 text-gray-600">Rs {{ number_format($waiter['average_order_value'], 2) }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $waiter['orders_per_day'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-600">No waiters available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Chef Performance -->
        <div class="border-4 border-black bg-white mb-12">
            <div class="bg-black text-white p-6 border-b-4 border-black">
                <h2 class="text-3xl font-black">Chef Performance</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-4 border-black bg-gray-100">
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Name</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Total Orders</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Items Prepared</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Avg Completion Time</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Items/Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['chef_performance'] as $chef)
                            <tr class="border-b-2 border-gray-300 hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-black">{{ $chef['name'] }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $chef['total_orders'] }}</td>
                                <td class="px-6 py-4 font-bold text-black">{{ $chef['total_items_prepared'] }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $chef['average_completion_time'] }} min</td>
                                <td class="px-6 py-4 text-gray-600">{{ $chef['items_per_order'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-600">No chefs available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Activity Summary Stats -->
        <div class="border-4 border-black bg-white">
            <div class="bg-black text-white p-6 border-b-4 border-black">
                <h2 class="text-3xl font-black">Activity Summary</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="border-2 border-gray-300 p-4">
                        <p class="text-sm font-bold text-gray-600 mb-2">Total Orders</p>
                        <p class="text-3xl font-black text-black">{{ $data['activity_summary']['total_orders'] }}</p>
                    </div>
                    <div class="border-2 border-gray-300 p-4">
                        <p class="text-sm font-bold text-gray-600 mb-2">Total Revenue</p>
                        <p class="text-3xl font-black text-black">Rs {{ number_format($data['activity_summary']['total_revenue'], 0) }}</p>
                    </div>
                    <div class="border-2 border-gray-300 p-4">
                        <p class="text-sm font-bold text-gray-600 mb-2">Avg Orders/Waiter</p>
                        <p class="text-3xl font-black text-black">{{ $data['activity_summary']['average_orders_per_waiter'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
