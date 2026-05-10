<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super_admin');
    }

    public function index()
    {
        // Dashboard stats
        $stats = [
            'total_revenue' => $this->calculateTotalRevenue(),
            'active_tenants' => Restaurant::where('is_verified', true)->where('is_active', true)->count(),
            'pending_reviews' => Restaurant::where('is_verified', false)->count(),
        ];

        // Recent activity
        $recentRestaurants = Restaurant::with('users')
            ->where('is_verified', true)
            ->latest()
            ->take(5)
            ->get();

        $pendingRestaurants = Restaurant::with('users')
            ->where('is_verified', false)
            ->latest()
            ->take(3)
            ->get();

        return view('super-admin.dashboard', compact('stats', 'recentRestaurants', 'pendingRestaurants'));
    }

    public function pendingQueue()
    {
        $pendingRestaurants = Restaurant::with(['users' => function($query) {
            $query->where('role', 'admin');
        }])
        ->where('is_verified', false)
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        return view('super-admin.pending-queue', compact('pendingRestaurants'));
    }

    public function approve(Restaurant $restaurant)
    {
        DB::transaction(function () use ($restaurant) {
            // Calculate subscription expiry based on plan
            $expiryDate = $this->calculateSubscriptionExpiry($restaurant->subscription_plan);
            
            $restaurant->update([
                'is_verified' => true,
                'is_active' => true,
                'verified_at' => now(),
                'subscription_expires_at' => $expiryDate,
            ]);

            // Send welcome notification to restaurant owner
            $owner = $restaurant->getOwner();
            if ($owner) {
                // TODO: Implement notification system
                // $owner->notify(new RestaurantApprovedNotification($restaurant));
            }
        });

        return redirect()->back()->with('success', "Restaurant '{$restaurant->name}' has been approved successfully!");
    }

    public function reject(Restaurant $restaurant, Request $request)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        DB::transaction(function () use ($restaurant, $request) {
            $restaurant->update([
                'is_verified' => false,
                'is_active' => false,
                'rejection_reason' => $request->reason,
                'rejected_at' => now(),
            ]);

            // Send rejection notification to restaurant owner
            $owner = $restaurant->getOwner();
            if ($owner) {
                // TODO: Implement notification system
                // $owner->notify(new RestaurantRejectedNotification($restaurant, $request->reason));
            }
        });

        return redirect()->back()->with('success', "Restaurant '{$restaurant->name}' has been rejected.");
    }

    public function restaurants()
    {
        $restaurants = Restaurant::with(['users' => function($query) {
            $query->where('role', 'admin');
        }])
        ->where('is_verified', true)
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        return view('super-admin.restaurants', compact('restaurants'));
    }

    public function suspend(Restaurant $restaurant)
    {
        $restaurant->update([
            'is_active' => false,
            'suspended_at' => now(),
        ]);

        return redirect()->back()->with('success', "Restaurant '{$restaurant->name}' has been suspended.");
    }

    public function reactivate(Restaurant $restaurant)
    {
        $restaurant->update([
            'is_active' => true,
            'suspended_at' => null,
        ]);

        return redirect()->back()->with('success', "Restaurant '{$restaurant->name}' has been reactivated.");
    }

    private function calculateTotalRevenue()
    {
        return Restaurant::where('is_verified', true)
            ->whereNotNull('subscription_amount')
            ->sum('subscription_amount');
    }

    private function calculateSubscriptionExpiry($plan)
    {
        $now = Carbon::now();
        
        return match($plan) {
            'monthly' => $now->addMonth(),
            'semi_annual' => $now->addMonths(6),
            'annual' => $now->addYear(),
            default => $now->addMonth(),
        };
    }
}