@extends('layouts.app')

@section('title', 'Restaurants Management')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Restaurants Management</h1>
                    <a href="{{ route('super-admin.restaurants.create') }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Create New Restaurant
                    </a>
                </div>

                @if($restaurants->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Restaurant
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Contact
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Users
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($restaurants as $restaurant)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($restaurant->logo)
                                                <img class="h-10 w-10 rounded-full mr-4" src="{{ asset('storage/' . $restaurant->logo) }}" alt="{{ $restaurant->name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center mr-4">
                                                    <span class="text-gray-600 font-medium">{{ substr($restaurant->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $restaurant->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $restaurant->slug }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $restaurant->email }}</div>
                                        <div class="text-sm text-gray-500">{{ $restaurant->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $restaurant->users->count() }} users
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $restaurant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $restaurant->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('super-admin.restaurants.show', $restaurant) }}" 
                                               class="text-indigo-600 hover:text-indigo-900">View</a>
                                            <a href="{{ route('super-admin.restaurants.edit', $restaurant) }}" 
                                               class="text-blue-600 hover:text-blue-900">Edit</a>
                                            <form method="POST" action="{{ route('super-admin.restaurants.toggle-status', $restaurant) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="text-yellow-600 hover:text-yellow-900"
                                                        onclick="return confirm('Are you sure you want to {{ $restaurant->is_active ? 'deactivate' : 'activate' }} this restaurant?')">
                                                    {{ $restaurant->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('super-admin.restaurants.destroy', $restaurant) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Are you sure you want to delete this restaurant? This action cannot be undone.')">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $restaurants->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-500 text-lg">No restaurants found.</div>
                        <a href="{{ route('super-admin.restaurants.create') }}" 
                           class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create Your First Restaurant
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection