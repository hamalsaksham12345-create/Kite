@extends('layouts.app')

@section('title', 'Pending Restaurant Approvals')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold">Pending Restaurant Approvals</h1>
                        <p class="text-gray-600 mt-1">Review and approve new restaurant registrations</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $pendingRestaurants->count() }} Pending
                        </span>
                        <a href="{{ route('super-admin.restaurants.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            All Restaurants
                        </a>
                    </div>
                </div>

                @if($pendingRestaurants->count() > 0)
                    <div class="space-y-6">
                        @foreach($pendingRestaurants as $restaurant)
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                <!-- Restaurant Info -->
                                <div class="flex-1">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h3 class="text-xl font-semibold text-gray-900">{{ $restaurant->name }}</h3>
                                            <p class="text-sky-600 font-medium">kite.test/{{ $restaurant->slug }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium">
                                                Pending Review
                                            </span>
                                            <p class="text-sm text-gray-500 mt-1">
                                                Submitted {{ $restaurant->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                        <!-- Owner Info -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-700 mb-2">Owner Information</h4>
                                            @if($restaurant->users->first())
                                                <p class="text-sm text-gray-900">{{ $restaurant->users->first()->name }}</p>
                                                <p class="text-sm text-gray-600">{{ $restaurant->users->first()->email }}</p>
                                            @endif
                                        </div>

                                        <!-- Contact Info -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-700 mb-2">Contact Details</h4>
                                            <p class="text-sm text-gray-900">{{ $restaurant->phone ?: 'Not provided' }}</p>
                                            <p class="text-sm text-gray-600">{{ $restaurant->email ?: 'Not provided' }}</p>
                                        </div>

                                        <!-- Subscription -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-700 mb-2">Subscription Plan</h4>
                                            <p class="text-sm text-gray-900 capitalize">{{ str_replace('_', ' ', $restaurant->subscription_plan) }}</p>
                                            <p class="text-sm text-gray-600">${{ $restaurant->subscription_amount }}</p>
                                        </div>

                                        <!-- Address -->
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-700 mb-2">Address</h4>
                                            <p class="text-sm text-gray-900">{{ $restaurant->address ?: 'Not provided' }}</p>
                                        </div>
                                    </div>

                                    @if($restaurant->description)
                                    <div class="mb-4">
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Description</h4>
                                        <p class="text-sm text-gray-900">{{ $restaurant->description }}</p>
                                    </div>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-col sm:flex-row lg:flex-col space-y-2 sm:space-y-0 sm:space-x-2 lg:space-x-0 lg:space-y-2 lg:ml-6">
                                    <!-- Approve Button -->
                                    <form method="POST" action="{{ route('super-admin.pending.approve', $restaurant) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="w-full sm:w-auto lg:w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors"
                                                onclick="return confirm('Are you sure you want to approve {{ $restaurant->name }}? This will activate their restaurant and make it live.')">
                                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Approve
                                        </button>
                                    </form>

                                    <!-- View Details Button -->
                                    <button type="button" 
                                            onclick="toggleDetails('{{ $restaurant->id }}')"
                                            class="w-full sm:w-auto lg:w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View Details
                                    </button>

                                    <!-- Reject Button -->
                                    <button type="button" 
                                            onclick="showRejectModal('{{ $restaurant->id }}', '{{ $restaurant->name }}')"
                                            class="w-full sm:w-auto lg:w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Reject
                                    </button>
                                </div>
                            </div>

                            <!-- Expandable Details -->
                            <div id="details-{{ $restaurant->id }}" class="hidden mt-6 pt-6 border-t border-gray-200">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-3">Technical Details</h4>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Restaurant ID:</span>
                                                <span class="font-mono">{{ $restaurant->id }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Slug:</span>
                                                <span class="font-mono">{{ $restaurant->slug }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Created:</span>
                                                <span>{{ $restaurant->created_at->format('M d, Y g:i A') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Subscription Expires:</span>
                                                <span>{{ $restaurant->subscription_expires_at ? $restaurant->subscription_expires_at->format('M d, Y') : 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-3">Branding</h4>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-600">Primary Color:</span>
                                                <div class="flex items-center">
                                                    <div class="w-4 h-4 rounded border mr-2" style="background-color: {{ $restaurant->primary_color }}"></div>
                                                    <span class="font-mono">{{ $restaurant->primary_color }}</span>
                                                </div>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-600">Secondary Color:</span>
                                                <div class="flex items-center">
                                                    <div class="w-4 h-4 rounded border mr-2" style="background-color: {{ $restaurant->secondary_color }}"></div>
                                                    <span class="font-mono">{{ $restaurant->secondary_color }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No pending approvals</h3>
                        <p class="text-gray-500">All restaurant registrations have been processed.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Restaurant Application</h3>
            <p class="text-sm text-gray-600 mb-4">
                Are you sure you want to reject <span id="rejectRestaurantName" class="font-medium"></span>? 
                This action cannot be undone and will permanently delete the restaurant registration.
            </p>
            
            <form id="rejectForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="mb-4">
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for rejection</label>
                    <textarea id="reason" name="reason" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-sky-500 focus:border-sky-500"
                              placeholder="Please provide a reason for rejection..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideRejectModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        Reject Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleDetails(restaurantId) {
    const details = document.getElementById('details-' + restaurantId);
    details.classList.toggle('hidden');
}

function showRejectModal(restaurantId, restaurantName) {
    document.getElementById('rejectRestaurantName').textContent = restaurantName;
    document.getElementById('rejectForm').action = `/super-admin/pending/${restaurantId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('reason').value = '';
}

// Close modal when clicking outside
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideRejectModal();
    }
});
</script>
@endsection