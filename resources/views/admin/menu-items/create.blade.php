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

        <div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
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
                <!-- Two Column Layout: Form + Live Preview -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Form Column -->
                    <div class="bg-white border-4 border-black hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                        <div class="p-8">
                            <h2 class="text-2xl font-black text-black mb-6">Add New Menu Item</h2>
                            <form action="{{ route('admin.menu-items.store', ['restaurant_slug' => $currentRestaurant->slug]) }}" 
                                  method="POST" 
                                  enctype="multipart/form-data" 
                                  class="space-y-6"
                                  x-data="menuItemForm()"
                                  x-init="init()">
                                @csrf

                                <!-- Category Selection -->
                                <div>
                                    <label for="category_id" class="block text-lg font-black text-black mb-3">Category</label>
                                    <select id="category_id" 
                                            name="category_id" 
                                            required
                                            x-model="formData.category_id"
                                            class="appearance-none block w-full px-4 py-4 border-4 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('category_id') border-red-600 @enderror">
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
                                           x-model="formData.name"
                                           value="{{ old('name') }}"
                                           class="appearance-none block w-full px-4 py-4 border-4 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('name') border-red-600 @enderror"
                                           placeholder="e.g., Margherita Pizza, Caesar Salad">
                                    @error('name')
                                        <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-lg font-black text-black mb-3">Description</label>
                                    <textarea id="description" 
                                              name="description" 
                                              rows="3"
                                              x-model="formData.description"
                                              class="appearance-none block w-full px-4 py-4 border-4 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('description') border-red-600 @enderror"
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
                                           x-model="formData.price"
                                           value="{{ old('price') }}"
                                           class="appearance-none block w-full px-4 py-4 border-4 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('price') border-red-600 @enderror"
                                           placeholder="0.00">
                                    @error('price')
                                        <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Item Image -->
                                <div>
                                    <label for="image" class="block text-lg font-black text-black mb-3">Item Image</label>
                                    <input id="image" 
                                           name="image" 
                                           type="file" 
                                           accept="image/*"
                                           @change="handleImageUpload($event)"
                                           class="appearance-none block w-full px-4 py-4 border-4 border-black focus:outline-none focus:border-emerald-600 text-lg font-medium @error('image') border-red-600 @enderror">
                                    <p class="mt-1 text-sm text-gray-600">Maximum file size: 2MB. Supported formats: JPEG, PNG, JPG, GIF</p>
                                    @error('image')
                                        <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-6">
                                    <button type="submit" 
                                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 px-6 border-4 border-black hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200 text-xl">
                                        CREATE MENU ITEM
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Live Preview Column -->
                    <div class="bg-white border-4 border-black hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                        <div class="p-8">
                            <h2 class="text-2xl font-black text-black mb-6">Live Preview</h2>
                            <p class="text-gray-600 mb-6">See how your menu item will appear to customers</p>
                            
                            <!-- Preview Card -->
                            <div class="bg-gray-50 rounded-2xl shadow-sm border-2 border-gray-200 overflow-hidden" x-data="menuItemForm()">
                                <!-- Preview Image -->
                                <div class="relative h-48 bg-gray-200">
                                    <div x-show="!previewImage" class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-300 to-gray-400">
                                        <span class="text-4xl font-black text-white" x-text="formData.name ? formData.name.charAt(0).toUpperCase() : '?'"></span>
                                    </div>
                                    <img x-show="previewImage" 
                                         :src="previewImage" 
                                         alt="Preview" 
                                         class="w-full h-full object-cover">
                                </div>

                                <!-- Preview Details -->
                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-lg font-black text-gray-900" x-text="formData.name || 'Menu Item Name'"></h3>
                                        <span class="text-lg font-black text-emerald-600" x-text="formData.price ? 'Rs ' + parseFloat(formData.price).toFixed(2) : 'Rs 0.00'"></span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mb-3" x-text="formData.description || 'Item description will appear here...'"></p>
                                    
                                    <!-- Preview Add to Cart Button -->
                                    <button class="w-full bg-emerald-600 text-white py-3 px-6 rounded-xl font-bold hover:opacity-90 transition-all duration-200 transform hover:scale-105">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>

                            <!-- Preview Tips -->
                            <div class="mt-6 p-4 bg-blue-50 border-2 border-blue-200 rounded-lg">
                                <h4 class="font-black text-blue-900 mb-2">Preview Tips:</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Fill out the form to see live updates</li>
                                    <li>• Upload an image to see how it looks</li>
                                    <li>• This is exactly how customers will see it</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="mt-8 text-center">
                    <a href="{{ route('admin.menu-items.index', ['restaurant_slug' => $currentRestaurant->slug]) }}" 
                       class="bg-gray-200 hover:bg-gray-300 text-black font-black py-3 px-6 border-4 border-black hover:shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all duration-200">
                        ← Back to Menu Items
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Alpine.js Script for Live Preview -->
    <script>
        function menuItemForm() {
            return {
                formData: {
                    name: '',
                    description: '',
                    price: '',
                    category_id: ''
                },
                previewImage: null,
                
                init() {
                    // Watch for form changes
                    this.$watch('formData', () => {
                        // Update preview in real-time
                    });
                },
                
                handleImageUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        // Check file size (2MB = 2 * 1024 * 1024 bytes)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('Image must be less than 2MB');
                            event.target.value = '';
                            this.previewImage = null;
                            return;
                        }
                        
                        // Create preview
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previewImage = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        this.previewImage = null;
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>