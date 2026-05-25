<nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-xl border-b border-gray-100/50 shadow-sm" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex-shrink-0">
                    <h1 class="text-3xl font-black text-emerald-800">Kite</h1>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-8">
                    @if(auth()->check())
                        @if(auth()->user()->isSuperAdmin())
                            <a href="{{ route('super-admin.dashboard') }}" class="text-gray-700 hover:text-emerald-700 px-4 py-2 text-sm font-semibold transition-colors rounded-xl hover:bg-emerald-50">Dashboard</a>
                        @elseif(auth()->user()->isAdmin() && auth()->user()->restaurant)
                            <a href="{{ route('restaurant.admin.dashboard.path', auth()->user()->restaurant->slug) }}" class="text-gray-700 hover:text-emerald-700 px-4 py-2 text-sm font-semibold transition-colors rounded-xl hover:bg-emerald-50">Dashboard</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-emerald-700 px-4 py-2 text-sm font-semibold transition-colors rounded-xl hover:bg-emerald-50">Sign Out</button>
                        </form>
                    @else
                        <a href="#features" class="text-gray-700 hover:text-emerald-700 px-4 py-2 text-sm font-semibold transition-colors rounded-xl hover:bg-emerald-50">Features</a>
                        <a href="#process" class="text-gray-700 hover:text-emerald-700 px-4 py-2 text-sm font-semibold transition-colors rounded-xl hover:bg-emerald-50">How it Works</a>
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-emerald-700 px-4 py-2 text-sm font-semibold transition-colors rounded-xl hover:bg-emerald-50">Sign In</a>
                        <a href="{{ route('register') }}" class="bg-emerald-700 hover:bg-emerald-800 text-white px-8 py-3 rounded-2xl text-sm font-bold transition-all hover:scale-105 shadow-lg">Get Started</a>
                    @endif
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700 hover:text-emerald-700 p-2 rounded-xl hover:bg-emerald-50 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white/95 backdrop-blur-xl border-t border-gray-100/50">
        <div class="px-4 pt-4 pb-6 space-y-2">
            @if(auth()->check())
                @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('super-admin.dashboard') }}" class="block px-4 py-3 text-base font-semibold text-gray-700 hover:text-emerald-700 rounded-xl hover:bg-emerald-50 transition-colors">Dashboard</a>
                @elseif(auth()->user()->isAdmin() && auth()->user()->restaurant)
                    <a href="{{ route('restaurant.admin.dashboard.path', auth()->user()->restaurant->slug) }}" class="block px-4 py-3 text-base font-semibold text-gray-700 hover:text-emerald-700 rounded-xl hover:bg-emerald-50 transition-colors">Dashboard</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-3 text-base font-semibold text-gray-700 hover:text-emerald-700 rounded-xl hover:bg-emerald-50 transition-colors">Sign Out</button>
                </form>
            @else
                <a href="#features" class="block px-4 py-3 text-base font-semibold text-gray-700 hover:text-emerald-700 rounded-xl hover:bg-emerald-50 transition-colors">Features</a>
                <a href="#process" class="block px-4 py-3 text-base font-semibold text-gray-700 hover:text-emerald-700 rounded-xl hover:bg-emerald-50 transition-colors">How it Works</a>
                <a href="{{ route('login') }}" class="block px-4 py-3 text-base font-semibold text-gray-700 hover:text-emerald-700 rounded-xl hover:bg-emerald-50 transition-colors">Sign In</a>
                <a href="{{ route('register') }}" class="block mx-4 mt-4 bg-emerald-700 hover:bg-emerald-800 text-white px-8 py-4 rounded-2xl text-center font-bold transition-colors">Get Started</a>
            @endif
        </div>
    </div>
</nav>
