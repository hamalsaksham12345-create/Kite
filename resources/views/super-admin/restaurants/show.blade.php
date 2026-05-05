@extends('layouts.app')

@section('title', $restaurant->name . ' - Restaurant Details')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">{{ $restaurant->name }}</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('super-admin.restaurants.edit', $restaurant) }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Restaurant
                        </a>
                        <a href="{{ route('super-admin.restaurants.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to List
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Restaurant Information -->
                    <div class="lg:col-span-2">
                        <div class="bg-gray-50 p-6 rounded-lg mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Restaurant Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Name</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $restaurant->name }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Slug</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $restaurant->slug }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $restaurant->email ?: 'Not provided' }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $restaurant->phone ?: 'Not provided' }}</p>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Address</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $restaurant->address ?: 'Not provided' }}</p>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Description</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $restaurant->description ?: 'No description provided' }}</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Primary Color</label>
                                    <div class="mt-1 flex items-center">
                                        <div class="w-6 h-6 rounded border mr-2" style="background-color: {{ $restaurant->primary_color }}"></div>
                                        <span class="text-sm text-gray-900">{{ $restaurant->primary_color }}</span>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Secondary Color</label>
                                    <div class="mt-1 flex items-center">
                                        <div class="w-6 h-6 rounded border mr-2" style="background-color: {{ $restaurant->secondary_color }}"></div>
                                        <span class="text-sm text-gray-900">{{ $restaurant->secondary_color }}</span>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $restaurant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $restaurant->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Created</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $restaurant->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Users -->
                        <div class="bg-gray-50 p-6 rounded-lg mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Users ({{ $restaurant->users->count() }})</h3>
                            
                            @if($restaurant->users->count() > 0)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            @foreach($restaurant->users as $user)
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-900">{{ $user->name }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-900">{{ $user->email }}</td>
                                                <td class="px-4 py-2 text-sm">
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                        @if($user->role === 'admin') bg-blue-100 text-blue-800
                                                        @elseif($user->role === 'waiter') bg-yellow-100 text-yellow-800
                                                        @elseif($user->role === 'chef') bg-red-100 text-red-800
                                                        @else bg-gray-100 text-gray-800 @endif">
                                                        {{ ucfirst($user->role) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2 text-sm">
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500">No users assigned to this restaurant.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="space-y-6">
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-blue-900 mb-4">Statistics</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-blue-700">Categories</span>
                                        <span class="text-sm font-semibold text-blue-900">{{ $restaurant->categories->count() }}</span>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-blue-700">Menu Items</span>
                                        <span class="text-sm font-semibold text-blue-900">{{ $restaurant->menuItems->count() }}</span>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-blue-700">Total Orders</span>
                                        <span class="text-sm font-semibold text-blue-900">{{ $restaurant->orders->count() }}</span>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-blue-700">Users</span>
                                        <span class="text-sm font-semibold text-blue-900">{{ $restaurant->users->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                            
                            <div class="space-y-2">
                                <form method="POST" action="{{ route('super-admin.restaurants.toggle-status', $restaurant) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="w-full text-left px-3 py-2 text-sm {{ $restaurant->is_active ? 'text-red-600 hover:bg-red-50' : 'text-green-600 hover:bg-green-50' }} rounded"
                                            onclick="return confirm('Are you sure you want to {{ $restaurant->is_active ? 'deactivate' : 'activate' }} this restaurant?')">
                                        {{ $restaurant->is_active ? 'Deactivate Restaurant' : 'Activate Restaurant' }}
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{ route('super-admin.restaurants.destroy', $restaurant) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded"
                                            onclick="return confirm('Are you sure you want to delete this restaurant? This action cannot be undone.')">
                                        Delete Restaurant
                                    </button>
                                </form>
                            </div>
                        </div>

                        @if($restaurant->logo)
                        <!-- Logo -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Logo</h3>
                            <img src="{{ asset('storage/' . $restaurant->logo) }}" alt="{{ $restaurant->name }}" class="max-w-full h-auto rounded">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection