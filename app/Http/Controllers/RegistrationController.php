<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
            'password' => 'required|min:8|confirmed',
        ]);

        // Execute inside DB Transaction
        DB::transaction(function () use ($validated, &$user) {
            // First, create the Restaurant with status => 'pending' and is_verified => false
            $restaurant = Restaurant::create([
                'name' => $validated['restaurant_name'],
                'slug' => $validated['slug'],
                'email' => $validated['email'],
                'status' => 'pending',
                'is_verified' => false,
                'is_active' => false,
            ]);

            // Second, create the User linked to that restaurant_id with the role => 'admin'
            $user = User::create([
                'name' => $this->extractNameFromEmail($validated['email']),
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'restaurant_id' => $restaurant->id,
                'role' => 'admin',
                'is_active' => true,
            ]);
        });

        // Manual Redirect: After registration, log the user in manually and redirect
        Auth::login($user);
        
        return redirect()->route('onboarding.subscription');
    }

    private function extractNameFromEmail($email)
    {
        $name = explode('@', $email)[0];
        return ucfirst(str_replace(['.', '_', '-'], ' ', $name));
    }
}