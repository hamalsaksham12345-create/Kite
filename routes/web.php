<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\RestaurantLookupController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\SuperAdmin\RestaurantController as SuperAdminRestaurantController;
use App\Http\Controllers\SuperAdmin\PendingController as SuperAdminPendingController;
use Illuminate\Support\Facades\Route;

// ============================================================================
// PUBLIC ROUTES
// ============================================================================

// Landing Page
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            return redirect()->route('super-admin.dashboard');
        } elseif ($user->isAdmin() && $user->restaurant && $user->restaurant->is_verified) {
            return redirect()->route('restaurant.admin.dashboard.path', $user->restaurant->slug);
        } elseif ($user->isWaiter() && $user->restaurant && $user->restaurant->is_verified) {
            return redirect()->route('restaurant.pos.dashboard.path', $user->restaurant->slug);
        } elseif ($user->isChef() && $user->restaurant && $user->restaurant->is_verified) {
            return redirect()->route('restaurant.kitchen.dashboard.path', $user->restaurant->slug);
        } elseif ($user->restaurant && !$user->restaurant->is_verified) {
            return redirect()->route('onboarding.pending');
        } else {
            return redirect()->route('onboarding.register');
        }
    }
    
    return view('landing');
})->name('home');

// ============================================================================
// AUTHENTICATION ROUTES
// ============================================================================

Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Registration Routes
    Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegistrationController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// ============================================================================
// ONBOARDING ROUTES
// ============================================================================

Route::middleware('guest')->group(function () {
    Route::get('/get-started', [OnboardingController::class, 'showRegistration'])->name('onboarding.register');
    Route::post('/get-started', [OnboardingController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/onboarding/subscription', [OnboardingController::class, 'showSubscription'])->name('onboarding.subscription');
    Route::post('/onboarding/subscription', [OnboardingController::class, 'processSubscription']);
    Route::get('/onboarding/pending', [OnboardingController::class, 'showPending'])->name('onboarding.pending');
});

// ============================================================================
// SUPER ADMIN ROUTES
// ============================================================================

Route::middleware(['auth', 'super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    // Dashboard
    Route::get('/', [SuperAdminController::class, 'index'])->name('dashboard');
    
    // Pending Queue
    Route::get('/pending-queue', [SuperAdminController::class, 'pendingQueue'])->name('pending-queue');
    Route::patch('/pending/{restaurant}/approve', [SuperAdminController::class, 'approve'])->name('approve');
    Route::delete('/pending/{restaurant}/reject', [SuperAdminController::class, 'reject'])->name('reject');
    
    // Restaurant Management
    Route::get('/restaurants', [SuperAdminController::class, 'restaurants'])->name('restaurants');
    Route::patch('/restaurants/{restaurant}/suspend', [SuperAdminController::class, 'suspend'])->name('suspend');
    Route::patch('/restaurants/{restaurant}/reactivate', [SuperAdminController::class, 'reactivate'])->name('reactivate');
    
    // Legacy routes for backward compatibility
    Route::resource('restaurants', SuperAdminRestaurantController::class)->except(['index']);
    Route::patch('restaurants/{restaurant}/toggle-status', [SuperAdminRestaurantController::class, 'toggleStatus'])
        ->name('restaurants.toggle-status');
    
    // Legacy pending routes
    Route::get('pending', [SuperAdminPendingController::class, 'index'])->name('pending.index');
    Route::patch('pending/{restaurant}/approve', [SuperAdminPendingController::class, 'approve'])->name('pending.approve');
    Route::delete('pending/{restaurant}/reject', [SuperAdminPendingController::class, 'reject'])->name('pending.reject');
});

// ============================================================================
// STAFF REGISTRATION ROUTES (Public)
// ============================================================================

Route::get('/join/{restaurant:slug}', [RegisterController::class, 'showStaffRegistration'])->name('staff.register.form');
Route::post('/join/{restaurant:slug}', [RegisterController::class, 'registerStaff'])->name('staff.register');

// ============================================================================
// RESTAURANT LOOKUP ROUTES (Public)
// ============================================================================

Route::get('/find-restaurant', [RestaurantLookupController::class, 'lookup'])->name('restaurant.lookup');
Route::get('/restaurant/{restaurant:slug}/info', [RestaurantLookupController::class, 'show'])->name('restaurant.info');

// ============================================================================
// RESTAURANT-SPECIFIC ROUTES (Dynamic Routing)
// ============================================================================

Route::middleware(['restaurant.context'])->group(function () {
    
    // ========================================================================
    // SUBDOMAIN ROUTING: {slug}.kite.test
    // ========================================================================
    
    Route::domain('{restaurant_slug}.kite.test')->group(function () {
        
        // PUBLIC MENU ROUTES
        Route::get('/', function () {
            $categories = \App\Models\Category::with(['menuItems' => function($query) {
                $query->orderBy('sort_order')->orderBy('name');
            }])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
            
            $menuItems = \App\Models\MenuItem::with('category')
                ->where('is_available', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
            
            return view('restaurant.menu', compact('categories', 'menuItems'));
        })->name('restaurant.menu');
        
        // PUBLIC CHECKOUT ROUTE
        Route::post('/checkout', [OrderController::class, 'store'])->name('restaurant.checkout');
        
        // ====================================================================
        // ADMIN ROUTES (Subdomain)
        // ====================================================================
        
        Route::middleware(['auth', 'role:admin', 'restaurant.verified'])->group(function () {
            // Admin Dashboard
            Route::get('/admin', [AdminDashboardController::class, 'index'])->name('restaurant.admin.dashboard');
            
            // Category Management
            Route::resource('admin/categories', CategoryController::class, ['as' => 'admin']);
            Route::patch('admin/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
                ->name('admin.categories.toggle-status');
            
            // Menu Item Management
            Route::resource('admin/menu-items', MenuItemController::class, ['as' => 'admin']);
            Route::patch('admin/menu-items/{menuItem}/toggle-availability', [MenuItemController::class, 'toggleAvailability'])
                ->name('admin.menu-items.toggle-availability');
            Route::patch('admin/menu-items/{menuItem}/toggle-featured', [MenuItemController::class, 'toggleFeatured'])
                ->name('admin.menu-items.toggle-featured');
        });
        
        // ====================================================================
        // POS & KITCHEN ROUTES (Subdomain)
        // ====================================================================
        
        Route::middleware(['auth', 'restaurant.verified'])->group(function () {
            // POS Dashboard (Waiter)
            Route::get('/pos', function () {
                return view('restaurant.pos.dashboard');
            })->middleware(['role:waiter'])->name('restaurant.pos.dashboard');
            
            // Kitchen Dashboard (Chef)
            Route::get('/kitchen', function () {
                return view('restaurant.kitchen.dashboard');
            })->middleware(['role:chef'])->name('restaurant.kitchen.dashboard');
            
            // Order Status Update Routes
            Route::patch('/orders/{order}/preparing', [OrderController::class, 'updateToPreparing'])->name('order.preparing');
            Route::patch('/orders/{order}/ready', [OrderController::class, 'updateToReady'])->name('order.ready');
            Route::patch('/orders/{order}/completed', [OrderController::class, 'updateToCompleted'])->name('order.completed');
            
            // Orders List API
            Route::get('/orders', [OrderController::class, 'getRestaurantOrders'])->name('orders.list');
        });
    });
    
    // ========================================================================
    // PATH-BASED ROUTING: kite.test/{slug}
    // ========================================================================
    
    Route::prefix('{restaurant_slug}')->group(function () {
        
        // PUBLIC MENU ROUTES
        Route::get('/', function () {
            $categories = \App\Models\Category::with(['menuItems' => function($query) {
                $query->orderBy('sort_order')->orderBy('name');
            }])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
            
            $menuItems = \App\Models\MenuItem::with('category')
                ->where('is_available', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
            
            return view('restaurant.menu', compact('categories', 'menuItems'));
        })->name('restaurant.menu.path');
        
        // PUBLIC CHECKOUT ROUTE
        Route::post('/checkout', [OrderController::class, 'store'])->name('restaurant.checkout.path');
        
        // ====================================================================
        // ADMIN ROUTES (Path-based)
        // ====================================================================
        
        Route::middleware(['auth', 'role:admin', 'restaurant.verified'])->group(function () {
            // Admin Dashboard
            Route::get('/admin', [AdminDashboardController::class, 'index'])->name('restaurant.admin.dashboard.path');
            
            // Category Management
            Route::resource('admin/categories', CategoryController::class, ['as' => 'admin.path']);
            Route::patch('admin/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
                ->name('admin.path.categories.toggle-status');
            
            // Menu Item Management
            Route::resource('admin/menu-items', MenuItemController::class, ['as' => 'admin.path']);
            Route::patch('admin/menu-items/{menuItem}/toggle-availability', [MenuItemController::class, 'toggleAvailability'])
                ->name('admin.path.menu-items.toggle-availability');
            Route::patch('admin/menu-items/{menuItem}/toggle-featured', [MenuItemController::class, 'toggleFeatured'])
                ->name('admin.path.menu-items.toggle-featured');
            
            // Table Management
            Route::resource('admin/tables', \App\Http\Controllers\TableController::class, ['as' => 'admin.path']);
            Route::patch('admin/tables/{table}/change-status', [\App\Http\Controllers\TableController::class, 'changeStatus'])
                ->name('admin.path.tables.change-status');
            Route::get('admin/tables/{table}/qr', [\App\Http\Controllers\TableController::class, 'generateQR'])
                ->name('admin.path.tables.qr');
        });
        
        // ====================================================================
        // POS & KITCHEN ROUTES (Path-based)
        // ====================================================================
        
        Route::middleware(['auth', 'restaurant.verified'])->group(function () {
            // POS Dashboard (Waiter)
            Route::get('/pos', function () {
                return view('restaurant.pos.dashboard');
            })->middleware(['role:waiter'])->name('restaurant.pos.dashboard.path');
            
            // Kitchen Dashboard (Chef)
            Route::get('/kitchen', function () {
                return view('restaurant.kitchen.dashboard');
            })->middleware(['role:chef'])->name('restaurant.kitchen.dashboard.path');
            
            // Order Status Update Routes
            Route::patch('/orders/{order}/preparing', [OrderController::class, 'updateToPreparing'])->name('order.preparing.path');
            Route::patch('/orders/{order}/ready', [OrderController::class, 'updateToReady'])->name('order.ready.path');
            Route::patch('/orders/{order}/completed', [OrderController::class, 'updateToCompleted'])->name('order.completed.path');
            
            // Orders List API
            Route::get('/orders', [OrderController::class, 'getRestaurantOrders'])->name('orders.list.path');
        });
    });
});
