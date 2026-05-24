@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-8 text-center">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Coming Soon</h1>
        
        @if($websiteSetting && $websiteSetting->maintenance_message)
            <p class="text-gray-600 mb-6">{{ $websiteSetting->maintenance_message }}</p>
        @else
            <p class="text-gray-600 mb-6">{{ $restaurant->name }}'s website is currently under construction. Please check back soon!</p>
        @endif

        <div class="border-t-4 border-black pt-6 mt-6">
            <p class="text-sm text-gray-500">Powered by Kite Restaurant Management</p>
        </div>
    </div>
</div>
@endsection
