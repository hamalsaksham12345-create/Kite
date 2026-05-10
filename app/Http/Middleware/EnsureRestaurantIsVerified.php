<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRestaurantIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Super admin can access everything
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user has a restaurant
        if (!$user->restaurant) {
            return redirect()->route('onboarding.register')->with('error', 'Please complete your restaurant registration.');
        }

        // Check if restaurant is verified
        if (!$user->restaurant->is_verified) {
            return redirect()->route('onboarding.pending')->with('info', 'Your restaurant is pending verification.');
        }

        // Check if subscription is active
        if ($user->restaurant->subscription_expires_at && $user->restaurant->subscription_expires_at->isPast()) {
            return redirect()->route('onboarding.subscription')->with('error', 'Your subscription has expired. Please renew to continue.');
        }

        return $next($request);
    }
}
