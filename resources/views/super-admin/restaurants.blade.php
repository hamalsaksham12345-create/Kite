@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-12">
        <h1 class="text-6xl font-black text-black mb-4">Platform Restaurants Master Directory</h1>
        <p class="text-xl text-gray-600 font-medium">Monitor and manage all active and suspended restaurants</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-8 p-6 bg-emerald-50 border-4 border-emerald-600">
            <p class="text-emerald-800 font-black text-lg">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Metrics Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <!-- Total Registered -->
        <div class="bg-white border-4 border-black p-8 hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
            <p class="text-xs font-black text-black uppercase tracking-widest mb-4">Total Registered</p>
            <p class="text-6xl font-black text-black">{{ $restaurants->total() }}</p>
        </div>

        <!-- Active Sites -->
        <div class="bg-white border-4 border-black p-8 hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
            <p class="text-xs font-black text-black uppercase tracking-widest mb-4">Active Sites</p>
            <p class="text-6xl font-black text-emerald-600">{{ $restaurants->where('is_active', true)->count() }}</p>
        </div>

        <!-- Suspended Access -->
        <div class="bg-white border-4 border-black p-8 hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
            <p class="text-xs font-black text-black uppercase tracking-widest mb-4">Suspended Access</p>
            <p class="text-6xl font-black text-red-600">{{ $restaurants->where('is_active', false)->count() }}</p>
        </div>
    </div>

    <!-- Restaurants Table -->
    <div class="bg-white border-4 border-black overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-4 border-black bg-gray-100">
                        <th class="px-6 py-4 text-left text-sm font-black text-black uppercase tracking-widest">Restaurant</th>
                        <th class="px-6 py-4 text-left text-sm font-black text-black uppercase tracking-widest">Slug</th>
                        <th class="px-6 py-4 text-left text-sm font-black text-black uppercase tracking-widest">Status Badge</th>
                        <th class="px-6 py-4 text-left text-sm font-black text-black uppercase tracking-widest">Subscription End Date</th>
                        <th class="px-6 py-4 text-left text-sm font-black text-black uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($restaurants as $restaurant)
                        <tr class="border-b-2 border-gray-300 hover:bg-gray-50 transition-colors">
                            <!-- Restaurant Name -->
                            <td class="px-6 py-4">
                                <p class="text-lg font-black text-black">{{ $restaurant->name }}</p>
                            </td>

                            <!-- Slug -->
                            <td class="px-6 py-4">
                                <p class="text-base font-bold text-gray-700">{{ $restaurant->slug }}</p>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-6 py-4">
                                @if($restaurant->is_active)
                                    <span class="inline-block px-4 py-2 bg-emerald-100 border-2 border-emerald-600 text-emerald-800 font-black text-sm uppercase">Active</span>
                                @else
                                    <span class="inline-block px-4 py-2 bg-red-100 border-2 border-red-600 text-red-800 font-black text-sm uppercase">Suspended</span>
                                @endif
                            </td>

                            <!-- Subscription End Date -->
                            <td class="px-6 py-4">
                                @if($restaurant->subscription_expires_at)
                                    <p class="text-base font-bold text-black">{{ $restaurant->subscription_expires_at->format('M d, Y') }}</p>
                                @else
                                    <p class="text-base font-bold text-gray-500">N/A</p>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                @if($restaurant->is_active)
                                    <form method="POST" action="{{ route('super-admin.suspend', $restaurant->id) }}" class="inline" onsubmit="return confirm('Suspend this restaurant?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white font-bold border-2 border-black hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                            Suspend
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('super-admin.reactivate', $restaurant->id) }}" class="inline" onsubmit="return confirm('Reactivate this restaurant?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white font-bold border-2 border-black hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                            Reactivate
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <p class="text-lg font-black text-gray-600">No restaurants found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($restaurants->hasPages())
        <div class="mt-12">
            {{ $restaurants->links() }}
        </div>
    @endif
</div>
@endsection
