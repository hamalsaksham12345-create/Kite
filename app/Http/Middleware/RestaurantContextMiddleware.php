<?php

namespace App\Http\Middleware;

use App\Models\Restaurant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestaurantContextMiddleware
{
    /**
     * Handle an incoming request.
     *
     * This middleware sets the restaurant context for the entire request based on the slug in the URL.
     * It also enforces tenant security by ensuring users can only access their own restaurant's data.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Extract restaurant slug from route parameters
        $slug = $request->route('restaurant_slug') ?? $request->route('slug');
        
        if ($slug) {
            // Find the restaurant by slug
            $restaurant = Restaurant::where('slug', $slug)
                ->where('is_active', true)
                ->first();
                
            // If restaurant doesn't exist, return 404
            if (!$restaurant) {
                abort(404, 'Restaurant not found');
            }
            
            // Tenant Security: Ensure logged-in users can only access their own restaurant
            if (Auth::check()) {
                $user = Auth::user();
                
                // Super admins can access any restaurant
                if (!$user->isSuperAdmin()) {
                    // Regular users must belong to the restaurant they're trying to access
                    if ($user->restaurant_id !== $restaurant->id) {
                        // Redirect to their own restaurant dashboard if they have one
                        if ($user->restaurant && $user->restaurant->is_verified) {
                            return redirect()->route('restaurant.admin.dashboard', [
                                'restaurant_slug' => $user->restaurant->slug
                            ]);
                        }
                        
                        // If they don't have a restaurant, redirect to onboarding
                        return redirect()->route('onboarding.register');
                    }
                }
            }
            
            // Share restaurant data with all views for logo, colors, etc.
            view()->share('currentRestaurant', $restaurant);
            
            // Set CSS variables for restaurant branding
            view()->share('restaurantBrandingCSS', $this->generateBrandingCSS($restaurant));
            
            // Store in request for controller access
            $request->merge(['current_restaurant' => $restaurant]);
            
            // Set restaurant context in config for global access
            config(['app.current_restaurant' => $restaurant]);
        }

        return $next($request);
    }

    /**
     * Generate CSS variables for restaurant branding
     */
    private function generateBrandingCSS(Restaurant $restaurant): string
    {
        $primaryColor = $restaurant->primary_color ?? '#10b981';
        $secondaryColor = $restaurant->secondary_color ?? '#065f46';
        
        return "
            <style>
                :root {
                    --brand-primary: {$primaryColor};
                    --brand-secondary: {$secondaryColor};
                    --restaurant-primary: {$primaryColor};
                    --restaurant-secondary: {$secondaryColor};
                }
                
                .brand-primary { background-color: var(--brand-primary); }
                .brand-secondary { background-color: var(--brand-secondary); }
                .text-brand-primary { color: var(--brand-primary); }
                .text-brand-secondary { color: var(--brand-secondary); }
                .border-brand-primary { border-color: var(--brand-primary); }
                .border-brand-secondary { border-color: var(--brand-secondary); }
            </style>
        ";
    }
}
