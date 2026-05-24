@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Header -->
    <div class="bg-black text-white p-8 border-b-4 border-black">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-5xl font-black mb-2">Menu Analytics</h1>
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

        <!-- Category Performance -->
        <div class="border-4 border-black bg-white mb-12">
            <div class="bg-black text-white p-6 border-b-4 border-black">
                <h2 class="text-3xl font-black">Category Performance</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-4 border-black bg-gray-100">
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Category</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Items</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Qty Sold</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['category_performance'] as $category)
                            <tr class="border-b-2 border-gray-300 hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-black">{{ $category['category'] }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $category['item_count'] }}</td>
                                <td class="px-6 py-4 font-bold text-black">{{ $category['total_quantity'] }}</td>
                                <td class="px-6 py-4 font-bold text-black">Rs {{ number_format($category['total_revenue'], 2) }}</td>
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

        <!-- Top Selling Dishes -->
        <div class="border-4 border-black bg-white mb-12">
            <div class="bg-black text-white p-6 border-b-4 border-black">
                <h2 class="text-3xl font-black">Top Selling Dishes</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-4 border-black bg-gray-100">
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Dish Name</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Category</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Qty Sold</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Revenue</th>
                            <th class="px-6 py-4 text-left text-sm font-black text-black uppercase">Avg Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['top_selling_dishes'] as $dish)
                            <tr class="border-b-2 border-gray-300 hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-black">{{ $dish['name'] }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $dish['category'] }}</td>
                                <td class="px-6 py-4 font-bold text-black">{{ $dish['quantity_sold'] }}</td>
                                <td class="px-6 py-4 font-bold text-black">Rs {{ number_format($dish['total_revenue'], 2) }}</td>
                                <td class="px-6 py-4 text-gray-600">Rs {{ number_format($dish['average_price'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-600">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bottom Performing Dishes -->
        <div class="border-4 border-black bg-white mb-12">
            <div class="bg-black text-white p-6 border-b-4 border-black">
                <h2 class="text-3xl font-black">Bottom Performing Dishes</h2>
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
                        @forelse($data['bottom_performing_dishes'] as $dish)
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

        <!-- Menu Trends -->
        <div class="border-4 border-black bg-white">
            <div class="bg-black text-white p-6 border-b-4 border-black">
                <h2 class="text-3xl font-black">Menu Trends (Top Items)</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($data['menu_trends'] as $item)
                        <div class="border-4 border-black p-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-bold text-black text-lg">{{ $item['name'] }}</p>
                                    <p class="text-sm text-gray-600">{{ $item['category'] }}</p>
                                </div>
                                <span class="px-3 py-1 {{ $item['trend'] === 'up' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} font-bold border-2 {{ $item['trend'] === 'up' ? 'border-green-800' : 'border-red-800' }}">
                                    {{ $item['trend'] === 'up' ? '↑' : '↓' }} {{ abs($item['percentage_change']) }}%
                                </span>
                            </div>
                            <div class="grid grid-cols-4 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-600">Current</p>
                                    <p class="font-bold text-black">{{ $item['quantity_sold'] }} units</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Previous</p>
                                    <p class="font-bold text-black">{{ $item['previous_quantity'] }} units</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Change</p>
                                    <p class="font-bold text-black">{{ $item['quantity_change'] > 0 ? '+' : '' }}{{ $item['quantity_change'] }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Revenue</p>
                                    <p class="font-bold text-black">Rs {{ number_format($item['total_revenue'], 0) }}</p>
                                </div>
                            </div>
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
