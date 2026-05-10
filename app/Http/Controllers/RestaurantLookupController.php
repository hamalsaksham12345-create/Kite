<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantLookupController extends Controller
{
    public function lookup(Request $request)
    {
        $search = $request->get('search');
        
        if (!$search) {
            return redirect()->route('register')->with('error', 'Please enter a restaurant name or slug to search.');
        }

        $restaurants = Restaurant::where('is_verified', true)
            ->where('is_active', true)
            ->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('slug', 'LIKE', "%{$search}%");
            })
            ->limit(10)
            ->get();

        return view('auth.restaurant-lookup', compact('restaurants', 'search'));
    }

    public function show(Restaurant $restaurant)
    {
        if (!$restaurant->is_verified || !$restaurant->is_active) {
            abort(404, 'Restaurant not found or not accepting registrations.');
        }

        return view('auth.restaurant-info', compact('restaurant'));
    }
}