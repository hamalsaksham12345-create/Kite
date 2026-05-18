<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with analytics
     */
    public function index()
    {
        // Get the current user's restaurant
        $restaurantId = auth()->user()->restaurant_id;

        // Total Revenue: Sum of all completed orders
        $totalRevenue = Order::where('restaurant_id', $restaurantId)
            ->where('status', 'completed')
            ->sum('total_price');

        // Total Orders Count: All orders for this restaurant
        $totalOrders = Order::where('restaurant_id', $restaurantId)
            ->count();

        // Active Orders Count: Orders in pending, preparing, or ready status
        $activeOrders = Order::where('restaurant_id', $restaurantId)
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->count();

        // Top 5 Selling Items
        $topSellingItems = OrderItem::select(
            'menu_item_id',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('SUM(quantity * price) as total_revenue')
        )
        ->whereHas('order', function ($query) use ($restaurantId) {
            $query->where('restaurant_id', $restaurantId);
        })
        ->groupBy('menu_item_id')
        ->orderByDesc('total_quantity')
        ->limit(5)
        ->with('menuItem')
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->menu_item_id,
                'name' => $item->menuItem->name,
                'total_quantity' => $item->total_quantity,
                'total_revenue' => $item->total_revenue,
            ];
        });

        return view('restaurant.admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'activeOrders',
            'topSellingItems'
        ));
    }
}
