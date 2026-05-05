@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-4">Welcome to Kite Dashboard</h1>
                
                @auth
                    <div class="mb-4">
                        <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
                        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                        <p><strong>Role:</strong> {{ ucfirst(auth()->user()->role) }}</p>
                        @if(auth()->user()->restaurant)
                            <p><strong>Restaurant:</strong> {{ auth()->user()->restaurant->name }}</p>
                        @endif
                    </div>
                @endauth

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @auth
                        @if(auth()->user()->isSuperAdmin())
                            <div class="bg-blue-100 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-blue-800">Super Admin</h3>
                                <p class="text-blue-600">Manage all restaurants and users</p>
                                <a href="{{ route('super-admin.restaurants.index') }}" class="mt-2 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    Manage Restaurants
                                </a>
                            </div>
                        @elseif(auth()->user()->isAdmin())
                            <div class="bg-green-100 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-green-800">Restaurant Admin</h3>
                                <p class="text-green-600">Manage your restaurant settings and menu</p>
                            </div>
                        @elseif(auth()->user()->isWaiter())
                            <div class="bg-yellow-100 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-yellow-800">Waiter/POS</h3>
                                <p class="text-yellow-600">Take orders and manage tables</p>
                            </div>
                        @elseif(auth()->user()->isChef())
                            <div class="bg-red-100 p-6 rounded-lg">
                                <h3 class="text-lg font-semibold text-red-800">Chef/Kitchen</h3>
                                <p class="text-red-600">View and manage kitchen orders</p>
                            </div>
                        @endif
                    @else
                        <div class="bg-gray-100 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-800">Get Started</h3>
                            <p class="text-gray-600">Login to access your dashboard</p>
                            <a href="{{ route('login') }}" class="mt-2 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                Login
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection