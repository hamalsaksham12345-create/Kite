<?php

namespace App\Services\Analytics;

use App\Models\Restaurant;
use Carbon\Carbon;

class AnalyticsService
{
    protected SalesAnalyticsService $salesService;
    protected MenuAnalyticsService $menuService;
    protected OrderAnalyticsService $orderService;
    protected StaffPerformanceService $staffService;

    public function __construct(
        SalesAnalyticsService $salesService,
        MenuAnalyticsService $menuService,
        OrderAnalyticsService $orderService,
        StaffPerformanceService $staffService
    ) {
        $this->salesService = $salesService;
        $this->menuService = $menuService;
        $this->orderService = $orderService;
        $this->staffService = $staffService;
    }

    /**
     * Get comprehensive dashboard data
     */
    public function getDashboardData(Restaurant $restaurant, ?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        return [
            'period' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
            ],
            'sales' => [
                'total_sales' => $this->salesService->getTotalSales($restaurant, $startDate, $endDate),
                'total_orders' => $this->salesService->getTotalOrders($restaurant, $startDate, $endDate),
                'average_order_value' => $this->salesService->getAverageOrderValue($restaurant, $startDate, $endDate),
                'daily_sales' => $this->salesService->getDailySales($restaurant, $startDate, $endDate),
                'hourly_distribution' => $this->salesService->getHourlySalesDistribution($restaurant, $endDate),
            ],
            'menu' => [
                'top_selling_dishes' => $this->menuService->getTopSellingDishes($restaurant, 10, $startDate, $endDate),
                'bottom_performing_dishes' => $this->menuService->getBottomPerformingDishes($restaurant, 5, $startDate, $endDate),
                'category_performance' => $this->menuService->getCategoryPerformance($restaurant, $startDate, $endDate),
                'menu_trends' => $this->menuService->getMenuItemTrends($restaurant, 5, $startDate, $endDate),
            ],
            'orders' => [
                'status_distribution' => $this->orderService->getOrderStatusDistribution($restaurant, $startDate, $endDate),
                'order_trends' => $this->orderService->getOrderTrends($restaurant, 'daily', $startDate, $endDate),
                'peak_times' => $this->orderService->getPeakOrderTimes($restaurant, 5, $startDate, $endDate),
                'average_completion_time' => $this->orderService->getAverageCompletionTime($restaurant, $startDate, $endDate),
                'value_distribution' => $this->orderService->getOrderValueDistribution($restaurant, $startDate, $endDate),
            ],
            'staff' => [
                'activity_summary' => $this->staffService->getStaffActivitySummary($restaurant, $startDate, $endDate),
                'waiter_performance' => $this->staffService->getWaiterPerformance($restaurant, $startDate, $endDate),
                'top_waiters' => $this->staffService->getTopWaiters($restaurant, 5, $startDate, $endDate),
                'chef_performance' => $this->staffService->getChefPerformance($restaurant, $startDate, $endDate),
                'efficiency_score' => $this->staffService->getStaffEfficiencyScore($restaurant, $startDate, $endDate),
            ],
        ];
    }

    /**
     * Get quick stats for dashboard header
     */
    public function getQuickStats(Restaurant $restaurant): array
    {
        $today = now();
        $yesterday = now()->subDay();

        $todaySales = $this->salesService->getTotalSales($restaurant, $today->startOfDay(), $today->endOfDay());
        $yesterdaySales = $this->salesService->getTotalSales($restaurant, $yesterday->startOfDay(), $yesterday->endOfDay());

        $todayOrders = $this->salesService->getTotalOrders($restaurant, $today->startOfDay(), $today->endOfDay());
        $yesterdayOrders = $this->salesService->getTotalOrders($restaurant, $yesterday->startOfDay(), $yesterday->endOfDay());

        return [
            'today' => [
                'sales' => round($todaySales, 2),
                'orders' => $todayOrders,
                'average_order_value' => $todayOrders > 0 ? round($todaySales / $todayOrders, 2) : 0,
            ],
            'yesterday' => [
                'sales' => round($yesterdaySales, 2),
                'orders' => $yesterdayOrders,
                'average_order_value' => $yesterdayOrders > 0 ? round($yesterdaySales / $yesterdayOrders, 2) : 0,
            ],
            'comparison' => [
                'sales_change' => round($todaySales - $yesterdaySales, 2),
                'sales_change_percent' => $yesterdaySales > 0 ? round((($todaySales - $yesterdaySales) / $yesterdaySales) * 100, 2) : 0,
                'orders_change' => $todayOrders - $yesterdayOrders,
                'orders_change_percent' => $yesterdayOrders > 0 ? round((($todayOrders - $yesterdayOrders) / $yesterdayOrders) * 100, 2) : 0,
            ],
        ];
    }

    /**
     * Get sales service
     */
    public function sales(): SalesAnalyticsService
    {
        return $this->salesService;
    }

    /**
     * Get menu service
     */
    public function menu(): MenuAnalyticsService
    {
        return $this->menuService;
    }

    /**
     * Get order service
     */
    public function orders(): OrderAnalyticsService
    {
        return $this->orderService;
    }

    /**
     * Get staff service
     */
    public function staff(): StaffPerformanceService
    {
        return $this->staffService;
    }
}
