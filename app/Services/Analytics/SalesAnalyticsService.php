<?php

namespace App\Services\Analytics;

use App\Models\Order;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SalesAnalyticsService
{
    /**
     * Get daily sales data for a date range
     */
    public function getDailySales(Restaurant $restaurant, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        return Order::where('restaurant_id', $restaurant->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as order_count, SUM(total_price) as total_sales')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'order_count' => $item->order_count,
                    'total_sales' => round($item->total_sales, 2),
                ];
            });
    }

    /**
     * Get total sales for a period
     */
    public function getTotalSales(Restaurant $restaurant, ?Carbon $startDate = null, ?Carbon $endDate = null): float
    {
        $startDate = $startDate ?? now()->startOfDay();
        $endDate = $endDate ?? now()->endOfDay();

        return (float) Order::where('restaurant_id', $restaurant->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');
    }

    /**
     * Get total orders for a period
     */
    public function getTotalOrders(Restaurant $restaurant, ?Carbon $startDate = null, ?Carbon $endDate = null): int
    {
        $startDate = $startDate ?? now()->startOfDay();
        $endDate = $endDate ?? now()->endOfDay();

        return Order::where('restaurant_id', $restaurant->id)
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    /**
     * Get average order value for a period
     */
    public function getAverageOrderValue(Restaurant $restaurant, ?Carbon $startDate = null, ?Carbon $endDate = null): float
    {
        $startDate = $startDate ?? now()->startOfDay();
        $endDate = $endDate ?? now()->endOfDay();

        $totalSales = $this->getTotalSales($restaurant, $startDate, $endDate);
        $totalOrders = $this->getTotalOrders($restaurant, $startDate, $endDate);

        return $totalOrders > 0 ? round($totalSales / $totalOrders, 2) : 0;
    }

    /**
     * Get sales comparison between two periods
     */
    public function getSalesComparison(Restaurant $restaurant, Carbon $period1Start, Carbon $period1End, Carbon $period2Start, Carbon $period2End): array
    {
        $sales1 = $this->getTotalSales($restaurant, $period1Start, $period1End);
        $sales2 = $this->getTotalSales($restaurant, $period2Start, $period2End);

        $difference = $sales2 - $sales1;
        $percentageChange = $sales1 > 0 ? round(($difference / $sales1) * 100, 2) : 0;

        return [
            'period1_sales' => round($sales1, 2),
            'period2_sales' => round($sales2, 2),
            'difference' => round($difference, 2),
            'percentage_change' => $percentageChange,
            'trend' => $percentageChange >= 0 ? 'up' : 'down',
        ];
    }

    /**
     * Get hourly sales distribution
     */
    public function getHourlySalesDistribution(Restaurant $restaurant, ?Carbon $date = null): Collection
    {
        $date = $date ?? now();

        return Order::where('restaurant_id', $restaurant->id)
            ->where('status', 'completed')
            ->whereDate('created_at', $date)
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as order_count, SUM(total_price) as total_sales')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->map(function ($item) {
                return [
                    'hour' => str_pad($item->hour, 2, '0', STR_PAD_LEFT) . ':00',
                    'order_count' => $item->order_count,
                    'total_sales' => round($item->total_sales, 2),
                ];
            });
    }
}
