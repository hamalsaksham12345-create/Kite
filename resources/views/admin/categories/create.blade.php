<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Create Category - {{ $currentRestaurant->name ?? 'Restaurant' }} Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                        <a href="{{ route('admin.categories.index', ['restaurant_slug' => $currentRestaurant->slug]) }}" 
                           class="text-lg font-bold text-gray-900 hover:text-emerald-600">
                            ← Back to Categories
                        </a>
                        <h1 class="text-2xl font-black text-black">Create New Category</h1>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="bg-white border-2 border-black hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <div class="p-8">
                    <form action="{{ route('admin.categories.store', ['restaurant_slug' => $currentRestaurant->slug]) }}" 
                          method="POST" 
                          enctype="multipart/form-data" 
                          class="space-y-6">
                        @csrf

                        <!-- Category Name -->
                        <div>
                            <label for="name" class="block text-lg font-black text-black mb-3">Category Name</label>
                            <input id="name" 
                                   name="name" 
                                   type="text" 
                                   required 
                                   value="{{ old('name') }}"
                                   class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('name') border-red-600 @enderror"
                                   placeholder="e.g., Appetizers, Main Courses, Desserts">
                            @error('name')
                                <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-lg font-black text-black mb-3">Description (Optional)</label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="3"
                                      class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('description') border-red-600 @enderror"
                                      placeholder="Brief description of this category...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category Image -->
                        <div>
                            <label for="image" class="block text-lg font-black text-black mb-3">Category Image (Optional)</label>
                            <input id="image" 
                                   name="image" 
                                   type="file" 
                                   accept="image/*"
                                   class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('image') border-red-600 @enderror">
                            <p class="mt-1 text-sm text-gray-600">Maximum file size: 5MB. Supported formats: JPEG, PNG, JPG, GIF</p>
                            @error('image')
                                <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <label for="sort_order" class="block text-lg font-black text-black mb-3">Sort Order (Optional)</label>
                            <input id="sort_order" 
                                   name="sort_order" 
                                   type="number" 
                                   min="0"
                                   value="{{ old('sort_order') }}"
                                   class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('sort_order') border-red-600 @enderror"
                                   placeholder="0">
                            <p class="mt-1 text-sm text-gray-600">Lower numbers appear first. Leave blank to add at the end.</p>
                            @error('sort_order')
                                <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input id="is_active" 
                                   name="is_active" 
                                   type="checkbox" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="h-5 w-5 text-emerald-600 focus:ring-emerald-500 border-2 border-black">
                            <label for="is_active" class="ml-3 block text-lg font-bold text-black">
                                Active Category
                            </label>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex gap-4 pt-6">
                            <button type="submit" 
                                    class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 px-6 border-2 border-black hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                Create Category
                            </button>
                            <a href="{{ route('admin.categories.index', ['restaurant_slug' => $currentRestaurant->slug]) }}" 
                               class="flex-1 bg-gray-200 hover:bg-gray-300 text-black font-black py-4 px-6 border-2 border-black hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200 text-center">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>