<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\SuperAdmin\RestaurantController as SuperAdminRestaurantController;
use App\Http\Controllers\SuperAdmin\PendingController as SuperAdminPendingController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            return redirect()->route('super-admin.dashboard');
        } elseif ($user->restaurant && $user->restaurant->is_verified) {
            return redirect()->route('restaurant.admin.dashboard');
        } elseif ($user->restaurant && !$user->restaurant->is_verified) {
            return redirect()->route('onboarding.pending');
        } else {
            return redirect()->route('onboarding.register');
        }
    }
    
    return view('landing');
})->name('home');

// Authentication Routes (placed early to avoid conflicts)
Route::middleware('guest')->group(function () {
    Route::get('/login', [App\Http\Controllers\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\LoginController::class, 'login']);
    
    Route::get('/register', [App\Http\Controllers\RegistrationController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\RegistrationController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\LoginController::class, 'logout'])->name('logout');
    
    // Role-specific Dashboard Routes
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/pos', function () {
        return view('pos');
    })->name('pos');
    
    Route::get('/kitchen', function () {
        return view('kitchen');
    })->name('kitchen');
});

// Onboarding Routes
Route::middleware('guest')->group(function () {
    Route::get('/get-started', [OnboardingController::class, 'showRegistration'])->name('onboarding.register');
    Route::post('/get-started', [OnboardingController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/onboarding/subscription', [OnboardingController::class, 'showSubscription'])->name('onboarding.subscription');
    Route::post('/onboarding/subscription', [OnboardingController::class, 'processSubscription']);
    Route::get('/onboarding/pending', [OnboardingController::class, 'showPending'])->name('onboarding.pending');
});

// Super Admin Routes
Route::middleware(['auth', 'super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    // Dashboard
    Route::get('/', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'index'])->name('dashboard');
    
    // Pending Queue
    Route::get('/pending-queue', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'pendingQueue'])->name('pending-queue');
    Route::patch('/pending/{restaurant}/approve', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'approve'])->name('approve');
    Route::delete('/pending/{restaurant}/reject', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'reject'])->name('reject');
    
    // Restaurant Management
    Route::get('/restaurants', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'restaurants'])->name('restaurants');
    Route::patch('/restaurants/{restaurant}/suspend', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'suspend'])->name('suspend');
    Route::patch('/restaurants/{restaurant}/reactivate', [App\Http\Controllers\SuperAdmin\SuperAdminController::class, 'reactivate'])->name('reactivate');
    
    // Legacy routes for backward compatibility
    Route::resource('restaurants', SuperAdminRestaurantController::class)->except(['index']);
    Route::patch('restaurants/{restaurant}/toggle-status', [SuperAdminRestaurantController::class, 'toggleStatus'])
        ->name('restaurants.toggle-status');
    
    // Legacy pending routes
    Route::get('pending', [SuperAdminPendingController::class, 'index'])->name('pending.index');
    Route::patch('pending/{restaurant}/approve', [SuperAdminPendingController::class, 'approve'])->name('pending.approve');
    Route::delete('pending/{restaurant}/reject', [SuperAdminPendingController::class, 'reject'])->name('pending.reject');
});

// Restaurant-specific routes (dynamic routing)
Route::middleware(['restaurant.context'])->group(function () {
    // Subdomain routing: {slug}.kite.test
    Route::domain('{restaurant_slug}.kite.test')->group(function () {
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
        
        // Public checkout route
        Route::post('/checkout', [App\Http\Controllers\OrderController::class, 'store'])->name('restaurant.checkout');
        
        Route::middleware(['auth', 'role:admin', 'restaurant.verified'])->group(function () {
            Route::get('/admin', function () {
                return view('restaurant.admin.dashboard');
            })->name('restaurant.admin.dashboard');
            
            // Category Management Routes
            Route::resource('admin/categories', CategoryController::class, ['as' => 'admin']);
            Route::patch('admin/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
                ->name('admin.categories.toggle-status');
            
            // Menu Item Management Routes
            Route::resource('admin/menu-items', MenuItemController::class, ['as' => 'admin']);
            Route::patch('admin/menu-items/{menuItem}/toggle-availability', [MenuItemController::class, 'toggleAvailability'])
                ->name('admin.menu-items.toggle-availability');
            Route::patch('admin/menu-items/{menuItem}/toggle-featured', [MenuItemController::class, 'toggleFeatured'])
                ->name('admin.menu-items.toggle-featured');
        });
        
        Route::middleware(['auth', 'restaurant.verified'])->group(function () {
            Route::get('/pos', function () {
                return view('restaurant.pos.dashboard');
            })->middleware(['role:waiter'])->name('restaurant.pos.dashboard');
            
            Route::get('/kitchen', function () {
                return view('restaurant.kitchen.dashboard');
            })->middleware(['role:chef'])->name('restaurant.kitchen.dashboard');
        });
    });
    
    // Path-based routing: kite.test/{slug}
    Route::prefix('{restaurant_slug}')->group(function () {
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
        
        // Public checkout route
        Route::post('/checkout', [App\Http\Controllers\OrderController::class, 'store'])->name('restaurant.checkout.path');
        
        Route::middleware(['auth', 'role:admin', 'restaurant.verified'])->group(function () {
            Route::get('/admin', function () {
                return view('restaurant.admin.dashboard');
            })->name('restaurant.admin.dashboard.path');
            
            // Category Management Routes
            Route::resource('admin/categories', CategoryController::class, ['as' => 'admin.path']);
            Route::patch('admin/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
                ->name('admin.path.categories.toggle-status');
            
            // Menu Item Management Routes
            Route::resource('admin/menu-items', MenuItemController::class, ['as' => 'admin.path']);
            Route::patch('admin/menu-items/{menuItem}/toggle-availability', [MenuItemController::class, 'toggleAvailability'])
                ->name('admin.path.menu-items.toggle-availability');
            Route::patch('admin/menu-items/{menuItem}/toggle-featured', [MenuItemController::class, 'toggleFeatured'])
                ->name('admin.path.menu-items.toggle-featured');
        });
        
        Route::middleware(['auth', 'restaurant.verified'])->group(function () {
            Route::get('/pos', function () {
                return view('restaurant.pos.dashboard');
            })->middleware(['role:waiter'])->name('restaurant.pos.dashboard.path');
            
            Route::get('/kitchen', function () {
                return view('restaurant.kitchen.dashboard');
            })->middleware(['role:chef'])->name('restaurant.kitchen.dashboard.path');
        });
    });
});

// Staff Registration Routes
Route::get('/join/{restaurant:slug}', [App\Http\Controllers\Auth\RegisterController::class, 'showStaffRegistration'])->name('staff.register.form');
Route::post('/join/{restaurant:slug}', [App\Http\Controllers\Auth\RegisterController::class, 'registerStaff'])->name('staff.register');

// Restaurant Lookup Routes
Route::get('/find-restaurant', [App\Http\Controllers\RestaurantLookupController::class, 'lookup'])->name('restaurant.lookup');
Route::get('/restaurant/{restaurant:slug}/info', [App\Http\Controllers\RestaurantLookupController::class, 'show'])->name('restaurant.info');
