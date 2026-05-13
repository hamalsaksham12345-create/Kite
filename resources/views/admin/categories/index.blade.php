<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Categories - {{ $currentRestaurant->name ?? 'Restaurant' }} Admin</title>

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
                        <a href="{{ route('restaurant.admin.dashboard', ['restaurant_slug' => $currentRestaurant->slug]) }}" 
                           class="text-lg font-bold text-gray-900 hover:text-emerald-600">
                            ← Dashboard
                        </a>
                        <h1 class="text-2xl font-black text-black">Menu Categories</h1>
                    </div>
                    <div class="flex items-center">
                        <a href="{{ route('admin.categories.create', ['restaurant_slug' => $currentRestaurant->slug]) }}" 
                           class="bg-emerald-600 hover:bg-emerald-700 text-white font-black py-2 px-4 border-2 border-black hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                            + Add Category
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

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-2 border-red-600 hover:shadow-[4px_4px_0px_0px_rgba(220,38,38,1)] transition-all duration-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($categories->count() > 0)
                <!-- Bento Grid Layout for Categories -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($categories as $category)
                        <div class="bg-white border-2 border-black hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200 overflow-hidden">
                            <!-- Category Image -->
                            <div class="h-48 bg-gray-200 relative">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" 
                                         alt="{{ $category->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-emerald-400 to-emerald-600">
                                        <span class="text-4xl font-black text-white">{{ substr($category->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                
                                <!-- Status Badge -->
                                <div class="absolute top-3 right-3">
                                    @if($category->is_active)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-black bg-green-100 text-green-800 border border-green-600">
                                            🟢 Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-black bg-red-100 text-red-800 border border-red-600">
                                            🔴 Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Category Info -->
                            <div class="p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-black text-black">{{ $category->name }}</h3>
                                    <span class="text-sm font-bold text-gray-600">{{ $category->menuItems->count() }} items</span>
                                </div>
                                
                                @if($category->description)
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $category->description }}</p>
                                @endif

                                <!-- Action Buttons -->
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.categories.edit', ['restaurant_slug' => $currentRestaurant->slug, 'category' => $category]) }}" 
                                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-black py-2 px-3 border border-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all duration-200 text-center">
                                        Edit
                                    </a>
                                    
                                    <form action="{{ route('admin.categories.toggle-status', ['restaurant_slug' => $currentRestaurant->slug, 'category' => $category]) }}" 
                                          method="POST" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="w-full {{ $category->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white text-xs font-black py-2 px-3 border border-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                            {{ $category->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.categories.destroy', ['restaurant_slug' => $currentRestaurant->slug, 'category' => $category]) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this category?')"
                                          class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full bg-red-600 hover:bg-red-700 text-white text-xs font-black py-2 px-3 border border-black hover:shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="bg-white border-2 border-black p-8 inline-block hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                        <div class="text-6xl mb-4">🍽️</div>
                        <h3 class="text-xl font-black text-black mb-2">No Categories Yet</h3>
                        <p class="text-gray-600 mb-6">Start organizing your menu by creating your first category.</p>
                        <a href="{{ route('admin.categories.create', ['restaurant_slug' => $currentRestaurant->slug]) }}" 
                           class="bg-emerald-600 hover:bg-emerald-700 text-white font-black py-3 px-6 border-2 border-black hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                            Create First Category
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>