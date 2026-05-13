{{-- Restaurant Header Component - Uses shared currentRestaurant variable --}}
@if(isset($currentRestaurant))
<header class="bg-white shadow-sm border-b" style="border-color: {{ $currentRestaurant->primary_color ?? '#10b981' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            {{-- Restaurant Logo and Name --}}
            <div class="flex items-center space-x-4">
                @if($currentRestaurant->logo)
                    <img src="{{ asset('storage/' . $currentRestaurant->logo) }}" 
                         alt="{{ $currentRestaurant->name }}" 
                         class="h-10 w-10 rounded-full object-cover">
                @else
                    <div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-bold"
                         style="background-color: {{ $currentRestaurant->primary_color ?? '#10b981' }}">
                        {{ substr($currentRestaurant->name, 0, 1) }}
                    </div>
                @endif
                
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $currentRestaurant->name }}</h1>
                    <p class="text-sm text-gray-500">{{ $currentRestaurant->slug }}.kite.com</p>
                </div>
            </div>

            {{-- Restaurant Status --}}
            <div class="flex items-center space-x-2">
                @if($currentRestaurant->is_verified)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        ✓ Verified
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        ⏳ Pending
                    </span>
                @endif

                @if($currentRestaurant->is_active)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        🟢 Active
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        🔴 Inactive
                    </span>
                @endif
            </div>
        </div>
    </div>
</header>

{{-- Custom CSS for restaurant branding --}}
<style>
    :root {
        --restaurant-primary: {{ $currentRestaurant->primary_color ?? '#10b981' }};
        --restaurant-secondary: {{ $currentRestaurant->secondary_color ?? '#065f46' }};
    }
    
    .btn-restaurant-primary {
        background-color: var(--restaurant-primary);
        border-color: var(--restaurant-primary);
    }
    
    .btn-restaurant-primary:hover {
        background-color: var(--restaurant-secondary);
        border-color: var(--restaurant-secondary);
    }
    
    .text-restaurant-primary {
        color: var(--restaurant-primary);
    }
    
    .border-restaurant-primary {
        border-color: var(--restaurant-primary);
    }
</style>
@endif