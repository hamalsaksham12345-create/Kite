<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OnboardingController extends Controller
{
    public function showRegistration()
    {
        return view('onboarding.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'restaurant_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'desired_slug' => 'required|string|max:255|unique:restaurants,slug|regex:/^[a-z0-9-]+$/',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Create the restaurant
        $restaurant = Restaurant::create([
            'name' => $validated['restaurant_name'],
            'slug' => $validated['desired_slug'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'is_active' => false,
            'is_verified' => false,
        ]);

        // Create the owner user
        $user = User::create([
            'name' => $validated['owner_name'],
            'email' => $validated['owner_email'],
            'password' => Hash::make($validated['password']),
            'restaurant_id' => $restaurant->id,
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Log in the user
        Auth::login($user);

        return redirect()->route('onboarding.subscription');
    }

    public function showSubscription()
    {
        if (!auth()->user()->restaurant) {
            return redirect()->route('onboarding.register');
        }

        return view('onboarding.subscription');
    }

    public function processSubscription(Request $request)
    {
        $validated = $request->validate([
            'plan' => 'required|in:monthly,semi_annual,annual',
        ]);

        $plans = [
            'monthly' => ['duration' => 1, 'price' => 29.99],
            'semi_annual' => ['duration' => 6, 'price' => 149.99],
            'annual' => ['duration' => 12, 'price' => 299.99],
        ];

        $selectedPlan = $plans[$validated['plan']];
        $restaurant = auth()->user()->restaurant;

        // Update restaurant with subscription details
        $restaurant->update([
            'subscription_plan' => $validated['plan'],
            'subscription_amount' => $selectedPlan['price'],
            'subscription_expires_at' => Carbon::now()->addMonths($selectedPlan['duration']),
        ]);

        // In a real app, you would process payment here
        // For now, we'll just redirect to pending verification

        return redirect()->route('onboarding.pending');
    }

    public function showPending()
    {
        $user = auth()->user();
        
        if (!$user->restaurant) {
            return redirect()->route('onboarding.register');
        }

        if ($user->restaurant->is_verified) {
            return redirect()->route('restaurant.admin.dashboard');
        }

        return view('onboarding.pending');
    }
}
