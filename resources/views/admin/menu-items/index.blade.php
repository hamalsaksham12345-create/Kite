<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Menu Items - {{ $currentRestaurant->name ?? 'Restaurant' }} Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Restaurant Header -->
    <x-restaurant-header />

    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b-2 border-black">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('restaurant.admin.dashboard.path', $currentRestaurant->slug) }}" 
                           class="text-lg font-bold text-gray-900 hover:text-emerald-600">
                            ← Dashboard
                        </a>
                        <h1 class="text-2xl font-black text-black">Menu Items</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.path.categories.index', $currentRestaurant->slug) }}" 
                           class="bg-gray-200 hover:bg-gray-300 text-black font-black py-2 px-4 border-2 border-black hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                            Manage Categories
                        </a>
                        <a href="{{ route('admin.path.menu-items.create', $currentRestaurant->slug) }}" 
                           class="bg-emerald-600 hover:bg-emerald-700 text-white font-black py-2 px-4 border-2 border-black hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                            + Add Menu Item
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-2 border-green-600 hover:shadow-[4px_4px_0px_0px_rgba(22,163,74,1)] transition-all duration-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($menuItems->count() > 0)
                <!-- Menu Items Table -->
                <div class="bg-white border-2 border-black overflow-hidden">
                    <table class="min-w-full divide-y-2 divide-black">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-lg font-black text-black border-r-2 border-black">Item</th>
                                <th class="px-6 py-4 text-left text-lg font-black text-black border-r-2 border-black">Category</th>
                                <th class="px-6 py-4 text-left text-lg font-black text-black border-r-2 border-black">Price</th>
                                <th class="px-6 py-4 text-left text-lg font-black text-black border-r-2 border-black">Status</th>
                                <th class="px-6 py-4 text-left text-lg font-black text-black">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y-2 divide-black">
                            @foreach($menuItems as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 border-r-2 border-black">
                                        <div class="flex items-center">
                                            @if($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}" 
                                                     alt="{{ $item->name }}" 
                                                     class="h-12 w-12 object-cover border-2 border-black mr-4">
                                            @else
                                                <div class="h-12 w-12 bg-gray-200 border-2 border-black mr-4 flex items-center justify-center">
                                                    <span class="text-xs font-bold text-gray-500">No Image</span>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-lg font-black text-black">{{ $item->name }}</div>
                                                @if($item->description)
                                                    <div class="text-sm text-gray-600 line-clamp-1">{{ $item->description }}</div>
                                                @endif
                                                @if($item->is_featured)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-black bg-yellow-100 text-yellow-800 border border-yellow-600 mt-1">
                                                        ⭐ Featured
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 border-r-2 border-black">
                                        <span class="text-lg font-bold text-black">{{ $item->category->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 border-r-2 border-black">
                                        <span class="text-lg font-black text-black">Rs {{ number_format($item->price, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4 border-r-2 border-black">
                                        <div class="flex flex-col space-y-2">
                                            @if($item->is_available)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-black bg-green-100 text-green-800 border border-green-600">
                                                    🟢 Available
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-black bg-red-100 text-red-800 border border-red-600">
                                                    🔴 Out of Stock
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('admin.path.menu-items.edit', [$currentRestaurant->slug, $item]) }}" 
                                               class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-black py-1 px-2 border border-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                                Edit
                                            </a>
                                            
                                            <form action="{{ route('admin.path.menu-items.toggle-availability', [$currentRestaurant->slug, $item]) }}" 
                                                  method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="{{ $item->is_available ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white text-xs font-black py-1 px-2 border border-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                                    {{ $item->is_available ? 'Mark Out of Stock' : 'Mark Available' }}
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('admin.path.menu-items.toggle-featured', [$currentRestaurant->slug, $item]) }}" 
                                                  method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="{{ $item->is_featured ? 'bg-gray-600 hover:bg-gray-700' : 'bg-purple-600 hover:bg-purple-700' }} text-white text-xs font-black py-1 px-2 border border-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                                    {{ $item->is_featured ? 'Unfeature' : 'Feature' }}
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('admin.path.menu-items.destroy', [$currentRestaurant->slug, $item]) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this menu item?')"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="bg-red-600 hover:bg-red-700 text-white text-xs font-black py-1 px-2 border border-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
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
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="bg-white border-2 border-black p-8 inline-block hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                        <div class="text-6xl mb-4">🍕</div>
                        <h3 class="text-xl font-black text-black mb-2">No Menu Items Yet</h3>
                        <p class="text-gray-600 mb-6">Start building your menu by adding your first item.</p>
                        @if($categories->count() > 0)
                            <a href="{{ route('admin.path.menu-items.create', $currentRestaurant->slug) }}" 
                               class="bg-emerald-600 hover:bg-emerald-700 text-white font-black py-3 px-6 border-2 border-black hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                Add First Menu Item
                            </a>
                        @else
                            <div class="space-y-4">
                                <p class="text-sm text-gray-500">You need to create categories first.</p>
                                <a href="{{ route('admin.path.categories.create', $currentRestaurant->slug) }}" 
                                   class="bg-emerald-600 hover:bg-emerald-700 text-white font-black py-3 px-6 border-2 border-black hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                    Create Categories First
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>