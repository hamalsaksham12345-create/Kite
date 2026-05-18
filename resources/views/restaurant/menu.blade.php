<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $currentRestaurant->name }} - Menu</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --restaurant-primary: {{ $currentRestaurant->primary_color ?? '#10b981' }};
            --restaurant-secondary: {{ $currentRestaurant->secondary_color ?? '#065f46' }};
        }
        
        .restaurant-primary {
            background-color: var(--restaurant-primary);
        }
        
        .restaurant-primary-text {
            color: var(--restaurant-primary);
        }
        
        .restaurant-primary-border {
            border-color: var(--restaurant-primary);
        }
        
        .restaurant-secondary {
            background-color: var(--restaurant-secondary);
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Hide scrollbar but keep functionality */
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        
        /* Custom animations */
        .bounce-in {
            animation: bounceIn 0.3s ease-out;
        }
        
        @keyframes bounceIn {
            0% { transform: scale(0.8); opacity: 0; }
            50% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .slide-up {
            animation: slideUp 0.3s ease-out;
        }
        
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50" 
      x-data="menuApp()" 
      x-init="init()">
    
    <!-- Restaurant Header -->
    <header class="bg-white shadow-sm sticky top-0 z-40">
        <div class="max-w-md mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Restaurant Info -->
                <div class="flex items-center space-x-3">
                    @if($currentRestaurant->logo)
                        <img src="{{ asset('storage/' . $currentRestaurant->logo) }}" 
                             alt="{{ $currentRestaurant->name }}" 
                             class="h-10 w-10 rounded-full object-cover">
                    @else
                        <div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-bold restaurant-primary">
                            {{ substr($currentRestaurant->name, 0, 1) }}
                        </div>
                    @endif
                    
                    <div>
                        <h1 class="text-lg font-black text-gray-900">{{ $currentRestaurant->name }}</h1>
                        <p class="text-xs text-gray-500">Tap to order</p>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div x-show="cartCount > 0" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="flex items-center space-x-2 bg-gray-100 rounded-full px-3 py-1">
                    <span class="text-sm font-bold text-gray-700" x-text="cartCount"></span>
                    <span class="text-xs text-gray-500">items</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Category Navigation -->
    <nav class="bg-white border-b sticky top-16 z-30">
        <div class="max-w-md mx-auto">
            <div class="flex overflow-x-auto hide-scrollbar px-4 py-3 space-x-4">
                <button @click="scrollToCategory('all')"
                        :class="activeCategory === 'all' ? 'restaurant-primary text-white' : 'bg-gray-100 text-gray-700'"
                        class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-bold transition-all duration-200 hover:shadow-md">
                    All Items
                </button>
                @foreach($categories as $category)
                    <button @click="scrollToCategory('{{ $category->slug }}')"
                            :class="activeCategory === '{{ $category->slug }}' ? 'restaurant-primary text-white' : 'bg-gray-100 text-gray-700'"
                            class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-bold transition-all duration-200 hover:shadow-md">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>
    </nav>

    <!-- Menu Content -->
    <main class="max-w-md mx-auto pb-24">
        <!-- All Items Section -->
        <section id="all" class="px-4 py-6">
            <h2 class="text-2xl font-black text-gray-900 mb-6">All Menu Items</h2>
            
            @if($menuItems->count() > 0)
                <div class="space-y-4">
                    @foreach($menuItems as $item)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden slide-up">
                            <!-- Item Image -->
                            <div class="relative h-48 bg-gray-200">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" 
                                         alt="{{ $item->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-300 to-gray-400">
                                        <span class="text-4xl font-black text-white">{{ substr($item->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                
                                <!-- Featured Badge -->
                                @if($item->is_featured)
                                    <div class="absolute top-3 left-3">
                                        <span class="bg-yellow-400 text-yellow-900 px-2 py-1 rounded-full text-xs font-black">
                                            Featured
                                        </span>
                                    </div>
                                @endif
                                
                                <!-- Availability Badge -->
                                @if(!$item->is_available)
                                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                        <span class="bg-red-500 text-white px-4 py-2 rounded-full text-sm font-black">
                                            Out of Stock
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Item Details -->
                            <div class="p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-black text-gray-900">{{ $item->name }}</h3>
                                    <span class="text-lg font-black restaurant-primary-text">Rs {{ number_format($item->price, 2) }}</span>
                                </div>
                                
                                @if($item->description)
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $item->description }}</p>
                                @endif
                                
                                <!-- Ingredients -->
                                @if($item->ingredients && count($item->ingredients) > 0)
                                    <div class="mb-3">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach(array_slice($item->ingredients, 0, 4) as $ingredient)
                                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">
                                                    {{ $ingredient }}
                                                </span>
                                            @endforeach
                                            @if(count($item->ingredients) > 4)
                                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">
                                                    +{{ count($item->ingredients) - 4 }} more
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Preparation Time -->
                                @if($item->preparation_time)
                                    <div class="mb-3">
                                        <span class="text-xs text-gray-500 font-medium">
                                            Ready in {{ $item->preparation_time }} min
                                        </span>
                                    </div>
                                @endif
                                
                                <!-- Add to Cart Button -->
                                @if($item->is_available)
                                    <div class="flex items-center justify-between">
                                        <!-- Quantity Controls -->
                                        <div x-show="getItemQuantity({{ $item->id }}) > 0" 
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 scale-90"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             class="flex items-center space-x-3">
                                            <button @click="removeFromCart({{ $item->id }})"
                                                    class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-700 font-bold hover:bg-gray-300 transition-colors">
                                                -
                                            </button>
                                            <span class="font-bold text-gray-900" x-text="getItemQuantity({{ $item->id }})"></span>
                                            <button @click="addToCart({{ $item->id }}, '{{ $item->name }}', {{ $item->price }})"
                                                    class="w-8 h-8 rounded-full restaurant-primary flex items-center justify-center text-white font-bold hover:opacity-90 transition-opacity">
                                                +
                                            </button>
                                        </div>
                                        
                                        <!-- Add to Cart Button -->
                                        <button x-show="getItemQuantity({{ $item->id }}) === 0"
                                                @click="addToCart({{ $item->id }}, '{{ $item->name }}', {{ $item->price }})"
                                                class="flex-1 restaurant-primary text-white py-3 px-6 rounded-xl font-bold hover:opacity-90 transition-all duration-200 transform hover:scale-105">
                                            Add to Cart
                                        </button>
                                    </div>
                                @else
                                    <button disabled 
                                            class="w-full bg-gray-300 text-gray-500 py-3 px-6 rounded-xl font-bold cursor-not-allowed">
                                        Out of Stock
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">🍽️</div>
                    <h3 class="text-xl font-black text-gray-900 mb-2">Menu Coming Soon</h3>
                    <p class="text-gray-600">We're working on our delicious menu. Check back soon!</p>
                </div>
            @endif
        </section>

        <!-- Category Sections -->
        @foreach($categories as $category)
            @if($category->menuItems->where('is_available', true)->count() > 0)
                <section id="{{ $category->slug }}" class="px-4 py-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-black text-gray-900">{{ $category->name }}</h2>
                        <span class="text-sm text-gray-500 font-medium">{{ $category->menuItems->where('is_available', true)->count() }} items</span>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($category->menuItems->where('is_available', true) as $item)
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                <!-- Compact Item Layout for Category Sections -->
                                <div class="flex">
                                    <!-- Item Image -->
                                    <div class="w-24 h-24 bg-gray-200 flex-shrink-0">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" 
                                                 alt="{{ $item->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-300 to-gray-400">
                                                <span class="text-lg font-black text-white">{{ substr($item->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Item Details -->
                                    <div class="flex-1 p-4">
                                        <div class="flex justify-between items-start mb-1">
                                            <h3 class="text-base font-black text-gray-900">{{ $item->name }}</h3>
                                            <span class="text-base font-black restaurant-primary-text">Rs {{ number_format($item->price, 2) }}</span>
                                        </div>
                                        
                                        @if($item->description)
                                            <p class="text-xs text-gray-600 mb-2 line-clamp-1">{{ $item->description }}</p>
                                        @endif
                                        
                                        <!-- Add to Cart Controls -->
                                        <div class="flex items-center justify-between">
                                            <div x-show="getItemQuantity({{ $item->id }}) > 0" 
                                                 class="flex items-center space-x-2">
                                                <button @click="removeFromCart({{ $item->id }})"
                                                        class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-gray-700 text-sm font-bold">
                                                    -
                                                </button>
                                                <span class="text-sm font-bold text-gray-900" x-text="getItemQuantity({{ $item->id }})"></span>
                                                <button @click="addToCart({{ $item->id }}, '{{ $item->name }}', {{ $item->price }})"
                                                        class="w-6 h-6 rounded-full restaurant-primary flex items-center justify-center text-white text-sm font-bold">
                                                    +
                                                </button>
                                            </div>
                                            
                                            <button x-show="getItemQuantity({{ $item->id }}) === 0"
                                                    @click="addToCart({{ $item->id }}, '{{ $item->name }}', {{ $item->price }})"
                                                    class="restaurant-primary text-white py-1 px-4 rounded-lg text-sm font-bold">
                                                Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        @endforeach
    </main>

    <!-- Floating View Order Button -->
    <div x-show="cartCount > 0" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-full"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-full"
         class="fixed bottom-4 left-4 right-4 z-50">
        <div class="max-w-md mx-auto">
            <button @click="viewOrder()" 
                    class="w-full restaurant-primary text-white py-4 px-6 rounded-2xl font-black text-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-between">
                <span>View Order</span>
                <div class="flex items-center space-x-2">
                    <span x-text="cartCount"></span>
                    <span>items</span>
                    <span>•</span>
                    <span x-text="'$' + cartTotal.toFixed(2)"></span>
                </div>
            </button>
        </div>
    </div>

    <script>
        function menuApp() {
            return {
                cart: [],
                activeCategory: 'all',
                
                init() {
                    // Load cart from localStorage
                    const savedCart = localStorage.getItem('kite_cart');
                    if (savedCart) {
                        this.cart = JSON.parse(savedCart);
                    }
                    
                    // Set up intersection observer for category navigation
                    this.setupCategoryObserver();
                },
                
                get cartCount() {
                    return this.cart.reduce((total, item) => total + item.quantity, 0);
                },
                
                get cartTotal() {
                    return this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
                },
                
                addToCart(id, name, price) {
                    const existingItem = this.cart.find(item => item.id === id);
                    
                    if (existingItem) {
                        existingItem.quantity++;
                    } else {
                        this.cart.push({
                            id: id,
                            name: name,
                            price: price,
                            quantity: 1
                        });
                    }
                    
                    this.saveCart();
                    this.showAddedToCartFeedback();
                },
                
                removeFromCart(id) {
                    const existingItem = this.cart.find(item => item.id === id);
                    
                    if (existingItem) {
                        if (existingItem.quantity > 1) {
                            existingItem.quantity--;
                        } else {
                            this.cart = this.cart.filter(item => item.id !== id);
                        }
                    }
                    
                    this.saveCart();
                },
                
                getItemQuantity(id) {
                    const item = this.cart.find(item => item.id === id);
                    return item ? item.quantity : 0;
                },
                
                saveCart() {
                    localStorage.setItem('kite_cart', JSON.stringify(this.cart));
                },
                
                scrollToCategory(categorySlug) {
                    this.activeCategory = categorySlug;
                    const element = document.getElementById(categorySlug);
                    if (element) {
                        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                },
                
                setupCategoryObserver() {
                    const sections = document.querySelectorAll('section[id]');
                    const options = {
                        rootMargin: '-20% 0px -70% 0px',
                        threshold: 0
                    };
                    
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                this.activeCategory = entry.target.id;
                            }
                        });
                    }, options);
                    
                    sections.forEach(section => observer.observe(section));
                },
                
                showAddedToCartFeedback() {
                    // Simple feedback - could be enhanced with toast notifications
                    const button = event.target;
                    const originalText = button.textContent;
                    button.textContent = 'Added!';
                    button.classList.add('bounce-in');
                    
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.classList.remove('bounce-in');
                    }, 1000);
                },
                
                viewOrder() {
                    // For now, just show an alert - this would typically navigate to checkout
                    alert(`Order Summary:\n\n${this.cart.map(item => `${item.name} x${item.quantity} - $${(item.price * item.quantity).toFixed(2)}`).join('\n')}\n\nTotal: $${this.cartTotal.toFixed(2)}\n\n(Checkout functionality coming soon!)`);
                }
            }
        }
    </script>
</body>
</html>