<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Create Menu Item - {{ $currentRestaurant->name ?? 'Restaurant' }} Admin</title>

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
                        <a href="{{ route('admin.menu-items.index', ['restaurant_slug' => $currentRestaurant->slug]) }}" 
                           class="text-lg font-bold text-gray-900 hover:text-emerald-600">
                            ← Back to Menu Items
                        </a>
                        <h1 class="text-2xl font-black text-black">Create New Menu Item</h1>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            @if($categories->count() == 0)
                <div class="bg-yellow-50 border-2 border-yellow-600 p-6 mb-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-black text-yellow-800">No Categories Available</h3>
                            <p class="text-sm text-yellow-700 mt-1">You need to create at least one category before adding menu items.</p>
                            <div class="mt-4">
                                <a href="{{ route('admin.categories.create', ['restaurant_slug' => $currentRestaurant->slug]) }}" 
                                   class="bg-yellow-600 hover:bg-yellow-700 text-white font-black py-2 px-4 border-2 border-black hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                    Create Category First
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white border-2 border-black hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                    <div class="p-8">
                        <form action="{{ route('admin.menu-items.store', ['restaurant_slug' => $currentRestaurant->slug]) }}" 
                              method="POST" 
                              enctype="multipart/form-data" 
                              class="space-y-6">
                            @csrf

                            <!-- Category Selection -->
                            <div>
                                <label for="category_id" class="block text-lg font-black text-black mb-3">Category</label>
                                <select id="category_id" 
                                        name="category_id" 
                                        required
                                        class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('category_id') border-red-600 @enderror">
                                    <option value="">Select a category...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Item Name -->
                            <div>
                                <label for="name" class="block text-lg font-black text-black mb-3">Item Name</label>
                                <input id="name" 
                                       name="name" 
                                       type="text" 
                                       required 
                                       value="{{ old('name') }}"
                                       class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('name') border-red-600 @enderror"
                                       placeholder="e.g., Margherita Pizza, Caesar Salad">
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
                                          placeholder="Describe the dish, ingredients, or preparation...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-lg font-black text-black mb-3">Price ($)</label>
                                <input id="price" 
                                       name="price" 
                                       type="number" 
                                       step="0.01"
                                       min="0"
                                       max="999999.99"
                                       required 
                                       value="{{ old('price') }}"
                                       class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('price') border-red-600 @enderror"
                                       placeholder="0.00">
                                @error('price')
                                    <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Item Image -->
                            <div>
                                <label for="image" class="block text-lg font-black text-black mb-3">Item Image (Optional)</label>
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

                            <!-- Ingredients -->
                            <div>
                                <label for="ingredients" class="block text-lg font-black text-black mb-3">Ingredients (Optional)</label>
                                <input id="ingredients" 
                                       name="ingredients" 
                                       type="text" 
                                       value="{{ old('ingredients') }}"
                                       class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('ingredients') border-red-600 @enderror"
                                       placeholder="tomato, mozzarella, basil, olive oil">
                                <p class="mt-1 text-sm text-gray-600">Separate ingredients with commas</p>
                                @error('ingredients')
                                    <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Allergens -->
                            <div>
                                <label for="allergens" class="block text-lg font-black text-black mb-3">Allergens (Optional)</label>
                                <input id="allergens" 
                                       name="allergens" 
                                       type="text" 
                                       value="{{ old('allergens') }}"
                                       class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('allergens') border-red-600 @enderror"
                                       placeholder="gluten, dairy, nuts">
                                <p class="mt-1 text-sm text-gray-600">Separate allergens with commas</p>
                                @error('allergens')
                                    <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Preparation Time -->
                            <div>
                                <label for="preparation_time" class="block text-lg font-black text-black mb-3">Preparation Time (minutes, optional)</label>
                                <input id="preparation_time" 
                                       name="preparation_time" 
                                       type="number" 
                                       min="1"
                                       max="300"
                                       value="{{ old('preparation_time') }}"
                                       class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('preparation_time') border-red-600 @enderror"
                                       placeholder="15">
                                @error('preparation_time')
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

                            <!-- Status Checkboxes -->
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input id="is_available" 
                                           name="is_available" 
                                           type="checkbox" 
                                           value="1"
                                           {{ old('is_available', true) ? 'checked' : '' }}
                                           class="h-5 w-5 text-emerald-600 focus:ring-emerald-500 border-2 border-black">
                                    <label for="is_available" class="ml-3 block text-lg font-bold text-black">
                                        Available for ordering
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input id="is_featured" 
                                           name="is_featured" 
                                           type="checkbox" 
                                           value="1"
                                           {{ old('is_featured') ? 'checked' : '' }}
                                           class="h-5 w-5 text-emerald-600 focus:ring-emerald-500 border-2 border-black">
                                    <label for="is_featured" class="ml-3 block text-lg font-bold text-black">
                                        Featured item (highlight on menu)
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex gap-4 pt-6">
                                <button type="submit" 
                                        class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 px-6 border-2 border-black hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                                    Create Menu Item
                                </button>
                                <a href="{{ route('admin.menu-items.index', ['restaurant_slug' => $currentRestaurant->slug]) }}" 
                                   class="flex-1 bg-gray-200 hover:bg-gray-300 text-black font-black py-4 px-6 border-2 border-black hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200 text-center">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>