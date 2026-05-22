<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Edit {{ $menuItem->name }} - {{ $currentRestaurant->name ?? 'Restaurant' }} Admin</title>

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
                        <a href="{{ route('admin.path.menu-items.index', $currentRestaurant->slug) }}" 
                           class="text-lg font-bold text-gray-900 hover:text-emerald-600">
                            ← Back to Menu Items
                        </a>
                        <h1 class="text-2xl font-black text-black">Edit {{ $menuItem->name }}</h1>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="bg-white border-2 border-black hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                <div class="p-8">
                    <form action="{{ route('admin.path.menu-items.update', [$currentRestaurant->slug, $menuItem]) }}" 
                          method="POST" 
                          enctype="multipart/form-data" 
                          class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Category Selection -->
                        <div>
                            <label for="category_id" class="block text-lg font-black text-black mb-3">Category</label>
                            <select id="category_id" 
                                    name="category_id" 
                                    required
                                    class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('category_id') border-red-600 @enderror">
                                <option value="">Select a category...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $menuItem->category_id) == $category->id ? 'selected' : '' }}>
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
                                   value="{{ old('name', $menuItem->name) }}"
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
                                      placeholder="Describe the dish, ingredients, or preparation...">{{ old('description', $menuItem->description) }}</textarea>
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
                                   value="{{ old('price', $menuItem->price) }}"
                                   class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('price') border-red-600 @enderror"
                                   placeholder="0.00">
                            @error('price')
                                <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Image -->
                        @if($menuItem->image)
                            <div>
                                <label class="block text-lg font-black text-black mb-3">Current Image</label>
                                <div class="border-2 border-black p-4 bg-gray-50">
                                    <img src="{{ asset('storage/' . $menuItem->image) }}" 
                                         alt="{{ $menuItem->name }}" 
                                         class="h-32 w-32 object-cover border-2 border-black">
                                </div>
                            </div>
                        @endif

                        <!-- Item Image -->
                        <div>
                            <label for="image" class="block text-lg font-black text-black mb-3">
                                {{ $menuItem->image ? 'Replace Image (Optional)' : 'Item Image (Optional)' }}
                            </label>
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
                                   value="{{ old('ingredients', is_array($menuItem->ingredients) ? implode(', ', $menuItem->ingredients) : '') }}"
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
                                   value="{{ old('allergens', is_array($menuItem->allergens) ? implode(', ', $menuItem->allergens) : '') }}"
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
                                   value="{{ old('preparation_time', $menuItem->preparation_time) }}"
                                   class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('preparation_time') border-red-600 @enderror"
                                   placeholder="15">
                            @error('preparation_time')
                                <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sort Order -->
                        <div>
                            <label for="sort_order" class="block text-lg font-black text-black mb-3">Sort Order</label>
                            <input id="sort_order" 
                                   name="sort_order" 
                                   type="number" 
                                   min="0"
                                   value="{{ old('sort_order', $menuItem->sort_order) }}"
                                   class="appearance-none block w-full px-4 py-4 border-2 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('sort_order') border-red-600 @enderror">
                            <p class="mt-1 text-sm text-gray-600">Lower numbers appear first.</p>
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
                                       {{ old('is_available', $menuItem->is_available) ? 'checked' : '' }}
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
                                       {{ old('is_featured', $menuItem->is_featured) ? 'checked' : '' }}
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
                                Update Menu Item
                            </button>
                            <a href="{{ route('admin.path.menu-items.index', $currentRestaurant->slug) }}" 
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