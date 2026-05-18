@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-12">
        <h1 class="text-6xl font-black text-black mb-4">Pending Approvals</h1>
        <p class="text-xl text-gray-600 font-medium">Review and approve restaurant applications</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-8 p-6 bg-emerald-50 border-4 border-emerald-600">
            <p class="text-emerald-800 font-black text-lg">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Pending Restaurants -->
    @if($pendingRestaurants->count() > 0)
        <div class="space-y-8">
            @foreach($pendingRestaurants as $restaurant)
                <div class="bg-white border-4 border-black p-8 hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                    <!-- Restaurant Header -->
                    <div class="mb-8">
                        <h2 class="text-5xl font-black text-black mb-2">{{ $restaurant->name }}</h2>
                        <p class="text-2xl font-black text-gray-800">{{ $restaurant->slug }}</p>
                    </div>

                    <!-- Restaurant Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <!-- Creator Email -->
                        <div class="border-4 border-black p-6 bg-gray-50">
                            <p class="text-xs font-black text-black uppercase tracking-widest mb-2">Creator Email</p>
                            <p class="text-xl font-black text-black">{{ $restaurant->getOwner()?->email ?? 'N/A' }}</p>
                        </div>

                        <!-- Subscription Plan -->
                        <div class="border-4 border-black p-6 bg-gray-50">
                            <p class="text-xs font-black text-black uppercase tracking-widest mb-2">Subscription Plan</p>
                            <p class="text-xl font-black text-black uppercase">{{ $restaurant->subscription_plan ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Approve Button -->
                        <form method="POST" action="{{ route('super-admin.approve', $restaurant->id) }}" class="flex-1">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full px-4 py-2 bg-black text-white font-bold border-2 border-black hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                Approve
                            </button>
                        </form>

                        <!-- Reject Button -->
                        <form method="POST" action="{{ route('super-admin.reject', $restaurant->id) }}" class="flex-1" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-white text-black font-bold border-2 border-black hover:translate-x-1 hover:translate-y-1 hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                Reject
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($pendingRestaurants->hasPages())
            <div class="mt-12">
                {{ $pendingRestaurants->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white border-4 border-black p-12 text-center">
            <h2 class="text-4xl font-black text-black mb-4">No Pending Restaurants</h2>
            <p class="text-lg font-bold text-gray-700">All applications have been reviewed.</p>
        </div>
    @endif
</div>
@endsection
