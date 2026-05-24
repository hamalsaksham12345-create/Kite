<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DiscountCodeController;
use App\Http\Controllers\WebsiteBuilderController;
use App\Http\Controllers\PublicWebsiteController;
use App\Http\Controllers\AnalyticsController;
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
            
            // Billing & Payments
            Route::get('/admin/billing', [BillingController::class, 'dashboard'])->name('admin.path.billing.dashboard');
            Route::get('/admin/billing/summary', [BillingController::class, 'paymentSummary'])->name('admin.path.billing.summary');
            Route::get('/admin/billing/unpaid', [BillingController::class, 'unpaidOrders'])->name('admin.path.billing.unpaid');
            
            // Discount Codes
            Route::resource('admin/discount-codes', DiscountCodeController::class, ['as' => 'admin.path']);
            Route::patch('admin/discount-codes/{discountCode}/toggle-status', [DiscountCodeController::class, 'toggleStatus'])
                ->name('admin.path.discount-codes.toggle-status');
            Route::post('admin/discount-codes/validate', [DiscountCodeController::class, 'validate'])
                ->name('admin.path.discount-codes.validate');
            
            // Invoice Management
            Route::get('/admin/invoices/{invoice}', [BillingController::class, 'viewInvoice'])->name('admin.path.invoices.view');
            Route::get('/admin/invoices/{invoice}/download', [BillingController::class, 'downloadInvoice'])->name('admin.path.invoices.download');
            
            // Website Builder
            Route::get('/admin/website-builder', [WebsiteBuilderController::class, 'index'])->name('admin.path.website-builder.index');
            Route::get('/admin/website-builder/design', [WebsiteBuilderController::class, 'design'])->name('admin.path.website-builder.design');
            Route::patch('/admin/website-builder/design', [WebsiteBuilderController::class, 'updateDesign'])->name('admin.path.website-builder.update-design');
            Route::get('/admin/website-builder/content', [WebsiteBuilderController::class, 'content'])->name('admin.path.website-builder.content');
            Route::patch('/admin/website-builder/content', [WebsiteBuilderController::class, 'updateContent'])->name('admin.path.website-builder.update-content');
            Route::get('/admin/website-builder/contact', [WebsiteBuilderController::class, 'contact'])->name('admin.path.website-builder.contact');
            Route::patch('/admin/website-builder/contact', [WebsiteBuilderController::class, 'updateContact'])->name('admin.path.website-builder.update-contact');
            Route::get('/admin/website-builder/media', [WebsiteBuilderController::class, 'media'])->name('admin.path.website-builder.media');
            Route::post('/admin/website-builder/upload-logo', [WebsiteBuilderController::class, 'uploadLogo'])->name('admin.path.website-builder.upload-logo');
            Route::post('/admin/website-builder/upload-favicon', [WebsiteBuilderController::class, 'uploadFavicon'])->name('admin.path.website-builder.upload-favicon');
            Route::post('/admin/website-builder/upload-banner', [WebsiteBuilderController::class, 'uploadBanner'])->name('admin.path.website-builder.upload-banner');
            Route::get('/admin/website-builder/domain', [WebsiteBuilderController::class, 'domain'])->name('admin.path.website-builder.domain');
            Route::patch('/admin/website-builder/domain', [WebsiteBuilderController::class, 'updateDomain'])->name('admin.path.website-builder.update-domain');
            Route::post('/admin/website-builder/publish', [WebsiteBuilderController::class, 'publish'])->name('admin.path.website-builder.publish');
            Route::post('/admin/website-builder/unpublish', [WebsiteBuilderController::class, 'unpublish'])->name('admin.path.website-builder.unpublish');
            Route::get('/admin/website-builder/preview', [WebsiteBuilderController::class, 'preview'])->name('admin.path.website-builder.preview');
            
            // Analytics Dashboard
            Route::get('/admin/analytics', [AnalyticsController::class, 'index'])->name('admin.path.analytics.dashboard');
            Route::get('/admin/analytics/sales', [AnalyticsController::class, 'sales'])->name('admin.path.analytics.sales');
            Route::get('/admin/analytics/menu', [AnalyticsController::class, 'menu'])->name('admin.path.analytics.menu');
            Route::get('/admin/analytics/orders', [AnalyticsController::class, 'orders'])->name('admin.path.analytics.orders');
            Route::get('/admin/analytics/staff', [AnalyticsController::class, 'staff'])->name('admin.path.analytics.staff');
            Route::get('/admin/analytics/export', [AnalyticsController::class, 'export'])->name('admin.path.analytics.export');
        });
        
        // Billing API Routes (for POS/Waiter)
        Route::middleware(['auth', 'restaurant.verified'])->group(function () {
            Route::post('/orders/{order}/apply-discount', [BillingController::class, 'applyDiscount'])->name('orders.apply-discount.path');
            Route::post('/orders/{order}/generate-invoice', [BillingController::class, 'generateInvoice'])->name('orders.generate-invoice.path');
            Route::post('/orders/{order}/process-payment', [BillingController::class, 'processPayment'])->name('orders.process-payment.path');
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

// ============================================================================
// PUBLIC WEBSITE ROUTES (Custom Domain & Subdomain)
// ============================================================================

Route::middleware(['restaurant.context'])->group(function () {
    
    // Subdomain routing for public website
    Route::domain('{restaurant_slug}.kite.test')->group(function () {
        Route::get('/', [PublicWebsiteController::class, 'index'])->name('website.index');
        Route::get('/menu', [PublicWebsiteController::class, 'menu'])->name('website.menu');
        Route::get('/about', [PublicWebsiteController::class, 'about'])->name('website.about');
        Route::get('/contact', [PublicWebsiteController::class, 'contact'])->name('website.contact');
        Route::post('/contact', [PublicWebsiteController::class, 'submitContact'])->name('website.contact.submit');
    });
    
    // Path-based routing for public website
    Route::prefix('{restaurant_slug}')->group(function () {
        Route::get('/website', [PublicWebsiteController::class, 'index'])->name('website.index.path');
        Route::get('/website/menu', [PublicWebsiteController::class, 'menu'])->name('website.menu.path');
        Route::get('/website/about', [PublicWebsiteController::class, 'about'])->name('website.about.path');
        Route::get('/website/contact', [PublicWebsiteController::class, 'contact'])->name('website.contact.path');
        Route::post('/website/contact', [PublicWebsiteController::class, 'submitContact'])->name('website.contact.submit.path');
    });
});
