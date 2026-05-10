<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // If user is already logged in, redirect to appropriate dashboard
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
        ]);

        // Check if user exists but is inactive
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        if ($user && !$user->is_active) {
            throw ValidationException::withMessages([
                'email' => 'Your account has been deactivated. Please contact your administrator.',
            ]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Multi-tenant check for admin users
            if ($user->role === 'admin' && $user->restaurant) {
                if (!$user->restaurant->is_verified) {
                    return redirect()->route('onboarding.pending')->with('info', 'Your account is awaiting Super Admin verification.');
                }
                
                if (!$user->restaurant->is_active) {
                    Auth::logout();
                    throw ValidationException::withMessages([
                        'email' => 'Your restaurant account has been suspended. Please contact support.',
                    ]);
                }
            }

            // Check if non-admin user's restaurant is verified and active
            if ($user->role !== 'super_admin' && $user->restaurant && (!$user->restaurant->is_verified || !$user->restaurant->is_active)) {
                if (!$user->restaurant->is_verified) {
                    return redirect()->route('onboarding.pending')->with('info', 'Your restaurant is still pending verification.');
                } else {
                    Auth::logout();
                    throw ValidationException::withMessages([
                        'email' => 'Your restaurant account has been suspended. Please contact support.',
                    ]);
                }
            }

            return $this->redirectBasedOnRole($user);
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out successfully.');
    }

    protected function redirectBasedOnRole($user)
    {
        // Role redirection using match statement
        return match($user->role) {
            'super_admin' => redirect()->intended('/super-admin'),
            'admin' => redirect()->intended('/dashboard'),
            'waiter' => redirect()->intended('/pos'),
            'chef' => redirect()->intended('/kitchen'),
            default => redirect()->intended('/'),
        };
    }
}