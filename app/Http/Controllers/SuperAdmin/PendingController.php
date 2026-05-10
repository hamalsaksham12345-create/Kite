<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PendingController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->isSuperAdmin()) {
                abort(403, 'Access denied. Super Admin privileges required.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $pendingRestaurants = Restaurant::with('users')
            ->where('is_verified', false)
            ->whereNotNull('subscription_plan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('super-admin.pending.index', compact('pendingRestaurants'));
    }

    public function approve(Restaurant $restaurant)
    {
        $restaurant->update([
            'is_verified' => true,
            'is_active' => true,
            'verified_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', "Restaurant '{$restaurant->name}' has been approved and activated.");
    }

    public function reject(Restaurant $restaurant, Request $request)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        // In a real app, you might want to store the rejection reason
        // and send an email to the restaurant owner

        $restaurant->delete();

        return redirect()->back()->with('success', "Restaurant '{$restaurant->name}' has been rejected and removed.");
    }
}
