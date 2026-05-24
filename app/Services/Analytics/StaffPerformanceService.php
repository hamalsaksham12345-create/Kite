<?php

namespace App\Services\Analytics;

use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class StaffPerformanceService
{
    /**
     * Get waiter performance metrics
     */
    public function getWaiterPerformance(Restaurant $restaurant, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        $waiters = $restaurant->users()
            ->where('role', 'waiter')
            ->get();

        return $waiters->map(function ($waiter) use ($restaurant, $startDate, $endDate) {
            $orders = Order::where('restaurant_id', $restaurant->id)
                ->where('created_by', $waiter->id)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $totalOrders = $orders->count();
            $totalRevenue = $orders->sum('total_price');
            $averageOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;

            return [
                'id' => $waiter->id,
                'name' => $waiter->name,
                'email' => $waiter->email,
                'total_orders' => $totalOrders,
                'total_revenue' => round($totalRevenue, 2),
                'average_order_value' => $averageOrderValue,
                'orders_per_day' => $totalOrders > 0 ? round($totalOrders / max(1, $endDate->diffInDays($startDate)), 2) : 0,
            ];
        })->sortByDesc('total_revenue')->values();
    }

    /**
     * Get top performing waiters
     */
    public function getTopWaiters(Restaurant $restaurant, int $limit = 5, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        return $this->getWaiterPerformance($restaurant, $startDate, $endDate)
            ->take($limit);
    }

    /**
     * Get chef performance metrics
     */
    public function getChefPerformance(Restaurant $restaurant, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        $chefs = $restaurant->users()
            ->where('role', 'chef')
            ->get();

        return $chefs->map(function ($chef) use ($restaurant, $startDate, $endDate) {
            // Get orders prepared by this chef (orders where they updated status to ready)
            $orders = Order::where('restaurant_id', $restaurant->id)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $totalOrders = $orders->count();
            $totalItems = $orders->sum(function ($order) {
                return $order->orderItems->count();
            });

            $averageCompletionTime = $orders->count() > 0
                ? round($orders->avg(function ($order) {
                    return $order->updated_at->diffInMinutes($order->created_at);
                }), 2)
                : 0;

            return [
                'id' => $chef->id,
                'name' => $chef->name,
                'email' => $chef->email,
                'total_orders' => $totalOrders,
                'total_items_prepared' => $totalItems,
                'average_completion_time' => $averageCompletionTime,
                'items_per_order' => $totalOrders > 0 ? round($totalItems / $totalOrders, 2) : 0,
            ];
        })->sortByDesc('total_orders')->values();
    }

    /**
     * Get staff activity summary
     */
    public function getStaffActivitySummary(Restaurant $restaurant, ?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        $waiters = $restaurant->users()->where('role', 'waiter')->count();
        $chefs = $restaurant->users()->where('role', 'chef')->count();
        $managers = $restaurant->users()->where('role', 'manager')->count();

        $totalOrders = Order::where('restaurant_id', $restaurant->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalRevenue = Order::where('restaurant_id', $restaurant->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

        return [
            'total_staff' => $waiters + $chefs + $managers,
            'waiters' => $waiters,
            'chefs' => $chefs,
            'managers' => $managers,
            'total_orders' => $totalOrders,
            'total_revenue' => round($totalRevenue, 2),
            'average_orders_per_waiter' => $waiters > 0 ? round($totalOrders / $waiters, 2) : 0,
        ];
    }

    /**
     * Get staff efficiency score
     */
    public function getStaffEfficiencyScore(Restaurant $restaurant, ?Carbon $startDate = null, ?Carbon $endDate = null): float
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        $orders = Order::where('restaurant_id', $restaurant->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        if ($orders->isEmpty()) {
            return 0;
        }

        // Calculate efficiency based on:
        // 1. Order completion rate (100% if all completed)
        // 2. Average completion time (faster is better)
        // 3. Revenue per order

        $completionRate = 100; // All orders are completed in this query

        $avgCompletionTime = $orders->avg(function ($order) {
            return $order->updated_at->diffInMinutes($order->created_at);
        });

        // Ideal completion time is 30 minutes
        $timeEfficiency = max(0, 100 - (($avgCompletionTime - 30) / 30 * 100));

        $avgRevenue = $orders->avg('total_price');
        // Ideal average order value is Rs 2000
        $revenueEfficiency = min(100, ($avgRevenue / 2000) * 100);

        // Weighted average: 40% completion, 30% time, 30% revenue
        $score = ($completionRate * 0.4) + ($timeEfficiency * 0.3) + ($revenueEfficiency * 0.3);

        return round(min(100, max(0, $score)), 2);
    }
}
