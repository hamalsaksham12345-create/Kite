<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'restaurant_id' => 'nullable|exists:restaurants,id',
            'role' => 'nullable|in:waiter,chef',
        ]);

        // If restaurant_id is provided, this is a staff member signup
        if ($request->restaurant_id) {
            $restaurant = Restaurant::findOrFail($request->restaurant_id);
            
            // Check if restaurant is verified and active
            if (!$restaurant->is_verified || !$restaurant->is_active) {
                return back()->withErrors(['restaurant_id' => 'This restaurant is not currently accepting new staff members.']);
            }

            $role = $validated['role'] ?? 'waiter';
        } else {
            // This is a general user signup (default to waiter role)
            $role = 'waiter';
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'restaurant_id' => $request->restaurant_id,
            'role' => $role,
            'is_active' => true,
        ]);

        Auth::login($user);

        // Redirect based on role and restaurant status
        if ($user->restaurant && $user->restaurant->is_verified) {
            return redirect()->route('restaurant.admin.dashboard');
        } else {
            return redirect()->route('home')->with('success', 'Account created successfully! Please contact your restaurant admin for access.');
        }
    }

    public function showStaffRegistration(Restaurant $restaurant)
    {
        // Check if restaurant allows staff registration
        if (!$restaurant->is_verified || !$restaurant->is_active) {
            abort(404, 'Restaurant not found or not accepting registrations.');
        }

        return view('auth.staff-register', compact('restaurant'));
    }

    public function registerStaff(Request $request, Restaurant $restaurant)
    {
        // Check if restaurant allows staff registration
        if (!$restaurant->is_verified || !$restaurant->is_active) {
            return back()->withErrors(['restaurant' => 'This restaurant is not currently accepting new staff members.']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:waiter,chef',
            'invite_code' => 'nullable|string',
        ]);

        // TODO: Implement invite code validation if needed
        // For now, allow open registration for active restaurants

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'restaurant_id' => $restaurant->id,
            'role' => $validated['role'],
            'is_active' => true,
        ]);

        Auth::login($user);

        // Redirect based on role
        if ($user->isWaiter()) {
            return redirect()->route('restaurant.pos.dashboard');
        } elseif ($user->isChef()) {
            return redirect()->route('restaurant.kitchen.dashboard');
        }

        return redirect()->route('home');
    }
}
