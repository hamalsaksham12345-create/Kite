<?php

use App\Http\Controllers\SuperAdmin\RestaurantController as SuperAdminRestaurantController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            return redirect()->route('super-admin.restaurants.index');
        } elseif ($user->isAdmin()) {
            return redirect()->route('restaurant.admin.dashboard');
        } elseif ($user->isWaiter()) {
            return redirect()->route('restaurant.pos.dashboard');
        } elseif ($user->isChef()) {
            return redirect()->route('restaurant.kitchen.dashboard');
        }
    }
    
    return view('welcome');
});

// Super Admin Routes
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::resource('restaurants', SuperAdminRestaurantController::class);
    Route::patch('restaurants/{restaurant}/toggle-status', [SuperAdminRestaurantController::class, 'toggleStatus'])
        ->name('restaurants.toggle-status');
});

// Restaurant-specific routes (dynamic routing)
Route::middleware(['restaurant.context'])->group(function () {
    // Subdomain routing: {slug}.kite.test
    Route::domain('{restaurant_slug}.kite.test')->group(function () {
        Route::get('/', function () {
            return view('restaurant.menu');
        })->name('restaurant.menu');
        
        Route::get('/admin', function () {
            return view('restaurant.admin.dashboard');
        })->middleware(['auth', 'role:admin'])->name('restaurant.admin.dashboard');
        
        Route::get('/pos', function () {
            return view('restaurant.pos.dashboard');
        })->middleware(['auth', 'role:waiter'])->name('restaurant.pos.dashboard');
        
        Route::get('/kitchen', function () {
            return view('restaurant.kitchen.dashboard');
        })->middleware(['auth', 'role:chef'])->name('restaurant.kitchen.dashboard');
    });
    
    // Path-based routing: kite.test/{slug}
    Route::prefix('{restaurant_slug}')->group(function () {
        Route::get('/', function () {
            return view('restaurant.menu');
        })->name('restaurant.menu.path');
        
        Route::get('/admin', function () {
            return view('restaurant.admin.dashboard');
        })->middleware(['auth', 'role:admin'])->name('restaurant.admin.dashboard.path');
        
        Route::get('/pos', function () {
            return view('restaurant.pos.dashboard');
        })->middleware(['auth', 'role:waiter'])->name('restaurant.pos.dashboard.path');
        
        Route::get('/kitchen', function () {
            return view('restaurant.kitchen.dashboard');
        })->middleware(['auth', 'role:chef'])->name('restaurant.kitchen.dashboard.path');
    });
});

// Authentication Routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
