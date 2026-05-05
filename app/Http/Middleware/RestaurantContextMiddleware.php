<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestaurantContextMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('restaurant_slug') ?? $request->route('slug');
        
        if ($slug) {
            $restaurant = \App\Models\Restaurant::where('slug', $slug)
                ->where('is_active', true)
                ->first();
                
            if (!$restaurant) {
                abort(404, 'Restaurant not found');
            }
            
            // Share restaurant data with all views
            view()->share('currentRestaurant', $restaurant);
            
            // Store in request for controller access
            $request->merge(['current_restaurant' => $restaurant]);
        }

        return $next($request);
    }
}
