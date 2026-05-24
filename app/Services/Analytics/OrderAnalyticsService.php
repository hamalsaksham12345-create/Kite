<?php

namespace App\Services\Analytics;

use App\Models\Order;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class OrderAnalyticsService
{
    /**
     * Get order status distribution
     */
    public function getOrderStatusDistribution(Restaurant $restaurant, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        return Order::where('restaurant_id', $restaurant->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => $item->status,
                    'count' => $item->count,
                    'percentage' => 0, // Will be calculated after
                ];
            });
    }

    /**
     * Get order trends over time
     */
    public function getOrderTrends(Restaurant $restaurant, string $period = 'daily', ?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        $query = Order::where('restaurant_id', $restaurant->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($period === 'daily') {
            $data = $query->selectRaw('DATE(created_at) as period, COUNT(*) as order_count, SUM(total_price) as total_sales')
                ->groupBy('period')
                ->orderBy('period')
                ->get();

            return $data->map(function ($item) {
                return [
                    'period' => $item->period,
                    'order_count' => $item->order_count,
                    'total_sales' => round($item->total_sales, 2),
                ];
            });
        } elseif ($period === 'weekly') {
            $data = $query->selectRaw('WEEK(created_at) as week, YEAR(created_at) as year, COUNT(*) as order_count, SUM(total_price) as total_sales')
                ->groupBy('year', 'week')
                ->orderBy('year')
                ->orderBy('week')
                ->get();

            return $data->map(function ($item) {
                return [
                    'period' => 'Week ' . $item->week . ' ' . $item->year,
                    'order_count' => $item->order_count,
                    'total_sales' => round($item->total_sales, 2),
                ];
            });
        } elseif ($period === 'monthly') {
            $data = $query->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as order_count, SUM(total_price) as total_sales')
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            return $data->map(function ($item) {
                $monthName = Carbon::createFromDate($item->year, $item->month, 1)->format('M Y');
                return [
                    'period' => $monthName,
                    'order_count' => $item->order_count,
                    'total_sales' => round($item->total_sales, 2),
                ];
            });
        }

        return collect();
    }

    /**
     * Get average order completion time
     */
    public function getAverageCompletionTime(Restaurant $restaurant, ?Carbon $startDate = null, ?Carbon $endDate = null): float
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

        $totalTime = $orders->sum(function ($order) {
            return $order->updated_at->diffInMinutes($order->created_at);
        });

        return round($totalTime / $orders->count(), 2);
    }

    /**
     * Get peak order times
     */
    public function getPeakOrderTimes(Restaurant $restaurant, int $limit = 5, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        return Order::where('restaurant_id', $restaurant->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as order_count, SUM(total_price) as total_sales')
            ->groupBy('hour')
            ->orderByDesc('order_count')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'hour' => str_pad($item->hour, 2, '0', STR_PAD_LEFT) . ':00',
                    'order_count' => $item->order_count,
                    'total_sales' => round($item->total_sales, 2),
                ];
            });
    }

    /**
     * Get order value distribution
     */
    public function getOrderValueDistribution(Restaurant $restaurant, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        $ranges = [
            ['min' => 0, 'max' => 500, 'label' => 'Rs 0-500'],
            ['min' => 500, 'max' => 1000, 'label' => 'Rs 500-1000'],
            ['min' => 1000, 'max' => 2000, 'label' => 'Rs 1000-2000'],
            ['min' => 2000, 'max' => 5000, 'label' => 'Rs 2000-5000'],
            ['min' => 5000, 'max' => PHP_INT_MAX, 'label' => 'Rs 5000+'],
        ];

        return collect($ranges)->map(function ($range) use ($restaurant, $startDate, $endDate) {
            $count = Order::where('restaurant_id', $restaurant->id)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereBetween('total_price', [$range['min'], $range['max']])
                ->count();

            return [
                'range' => $range['label'],
                'count' => $count,
            ];
        });
    }
}
