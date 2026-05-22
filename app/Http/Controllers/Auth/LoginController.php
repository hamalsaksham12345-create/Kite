<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // If user is already logged in, redirect to appropriate dashboard
        if (Auth::check()) {
            return $this->redirectToDashboard(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
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
            
            // Check if user's restaurant is verified (for non-super admins)
            if (!$user->isSuperAdmin() && $user->restaurant && !$user->restaurant->is_verified) {
                return redirect()->route('onboarding.pending')->with('info', 'Your restaurant is still pending verification.');
            }

            // Check if user's restaurant is suspended
            if (!$user->isSuperAdmin() && $user->restaurant && !$user->restaurant->is_active) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Your restaurant account has been suspended. Please contact support.',
                ]);
            }

            return $this->redirectToDashboard($user);
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

    protected function redirectToDashboard($user)
    {
        // Redirect based on user role
        if ($user->isSuperAdmin()) {
            return redirect()->intended(route('super-admin.dashboard'));
        } elseif ($user->isAdmin()) {
            if ($user->restaurant && $user->restaurant->is_verified) {
                return redirect()->intended(route('restaurant.admin.dashboard.path', $user->restaurant->slug));
            } else {
                return redirect()->route('onboarding.pending');
            }
        } elseif ($user->isWaiter()) {
            if ($user->restaurant && $user->restaurant->is_verified) {
                return redirect()->intended(route('restaurant.pos.dashboard.path', $user->restaurant->slug));
            } else {
                return redirect()->route('home')->with('info', 'Please wait for your restaurant to be verified.');
            }
        } elseif ($user->isChef()) {
            if ($user->restaurant && $user->restaurant->is_verified) {
                return redirect()->intended(route('restaurant.kitchen.dashboard.path', $user->restaurant->slug));
            } else {
                return redirect()->route('home')->with('info', 'Please wait for your restaurant to be verified.');
            }
        }

        return redirect()->intended('/');
    }
}
