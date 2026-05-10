<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'restaurant_name' => 'required|string|max:255',
            'slug' => 'required|alpha_dash|unique:restaurants,slug',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', 'min:8'],
            'plan' => 'required|in:1 month,6 months,12 months',
        ], [
            'restaurant_name.required' => 'Restaurant name is required.',
            'restaurant_name.string' => 'Restaurant name must be a valid string.',
            'restaurant_name.max' => 'Restaurant name cannot exceed 255 characters.',
            'slug.required' => 'Restaurant slug is required.',
            'slug.alpha_dash' => 'Slug can only contain letters, numbers, dashes, and underscores.',
            'slug.unique' => 'This slug is already taken. Please choose another.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters long.',
            'plan.required' => 'Please select a subscription plan.',
            'plan.in' => 'Please select a valid subscription plan.',
        ]);

        // Create restaurant
        $restaurant = Restaurant::create([
            'name' => $validated['restaurant_name'],
            'slug' => $validated['slug'],
            'email' => $validated['email'],
            'is_active' => false,
            'is_verified' => false,
            'subscription_plan' => str_replace(' ', '_', strtolower($validated['plan'])),
            'subscription_amount' => $this->getPlanAmount($validated['plan']),
        ]);

        // Create admin user
        $user = User::create([
            'name' => $this->extractNameFromEmail($validated['email']),
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'restaurant_id' => $restaurant->id,
            'role' => 'admin',
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('onboarding.pending')->with('success', 'Registration successful! Your account is awaiting Super Admin verification.');
    }

    private function getPlanAmount($plan)
    {
        return match($plan) {
            '1 month' => 29.99,
            '6 months' => 149.99,
            '12 months' => 299.99,
            default => 29.99,
        };
    }

    private function extractNameFromEmail($email)
    {
        $name = explode('@', $email)[0];
        return ucfirst(str_replace(['.', '_', '-'], ' ', $name));
    }
}