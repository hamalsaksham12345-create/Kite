<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RestaurantController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->isSuperAdmin()) {
                abort(403, 'Access denied. Super Admin privileges required.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the restaurants.
     */
    public function index()
    {
        $restaurants = Restaurant::with('users')->paginate(15);
        
        return view('super-admin.restaurants.index', compact('restaurants'));
    }

    /**
     * Show the form for creating a new restaurant.
     */
    public function create()
    {
        return view('super-admin.restaurants.create');
    }

    /**
     * Store a newly created restaurant in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:restaurants,slug',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            
            // Master Admin user details
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:users,email',
            'admin_password' => 'required|string|min:8|confirmed',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Create the restaurant
        $restaurant = Restaurant::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'description' => $validated['description'],
            'primary_color' => $validated['primary_color'] ?? '#3B82F6',
            'secondary_color' => $validated['secondary_color'] ?? '#1F2937',
            'is_active' => true,
        ]);

        // Create the master admin user for this restaurant
        User::create([
            'name' => $validated['admin_name'],
            'email' => $validated['admin_email'],
            'password' => Hash::make($validated['admin_password']),
            'restaurant_id' => $restaurant->id,
            'role' => 'admin',
            'is_active' => true,
        ]);

        return redirect()
            ->route('super-admin.restaurants.index')
            ->with('success', "Restaurant '{$restaurant->name}' created successfully with master admin user.");
    }

    /**
     * Display the specified restaurant.
     */
    public function show(Restaurant $restaurant)
    {
        $restaurant->load(['users', 'categories', 'menuItems', 'orders']);
        
        return view('super-admin.restaurants.show', compact('restaurant'));
    }

    /**
     * Show the form for editing the specified restaurant.
     */
    public function edit(Restaurant $restaurant)
    {
        return view('super-admin.restaurants.edit', compact('restaurant'));
    }

    /**
     * Update the specified restaurant in storage.
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('restaurants', 'slug')->ignore($restaurant->id)
            ],
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $restaurant->update($validated);

        return redirect()
            ->route('super-admin.restaurants.show', $restaurant)
            ->with('success', 'Restaurant updated successfully.');
    }

    /**
     * Remove the specified restaurant from storage.
     */
    public function destroy(Restaurant $restaurant)
    {
        $restaurantName = $restaurant->name;
        $restaurant->delete();

        return redirect()
            ->route('super-admin.restaurants.index')
            ->with('success', "Restaurant '{$restaurantName}' deleted successfully.");
    }

    /**
     * Toggle the active status of a restaurant.
     */
    public function toggleStatus(Restaurant $restaurant)
    {
        $restaurant->update([
            'is_active' => !$restaurant->is_active
        ]);

        $status = $restaurant->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', "Restaurant '{$restaurant->name}' has been {$status}.");
    }
}
