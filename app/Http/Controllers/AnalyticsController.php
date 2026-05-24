<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Services\Analytics\AnalyticsService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Show analytics dashboard
     */
    public function index(Request $request, Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $startDate = $request->query('start_date')
            ? Carbon::createFromFormat('Y-m-d', $request->query('start_date'))
            : now()->subDays(30);

        $endDate = $request->query('end_date')
            ? Carbon::createFromFormat('Y-m-d', $request->query('end_date'))
            : now();

        $data = $this->analyticsService->getDashboardData($restaurant, $startDate, $endDate);
        $quickStats = $this->analyticsService->getQuickStats($restaurant);

        return view('admin.analytics.dashboard', compact('restaurant', 'data', 'quickStats', 'startDate', 'endDate'));
    }

    /**
     * Get sales analytics
     */
    public function sales(Request $request, Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $startDate = $request->query('start_date')
            ? Carbon::createFromFormat('Y-m-d', $request->query('start_date'))
            : now()->subDays(30);

        $endDate = $request->query('end_date')
            ? Carbon::createFromFormat('Y-m-d', $request->query('end_date'))
            : now();

        $data = [
            'daily_sales' => $this->analyticsService->sales()->getDailySales($restaurant, $startDate, $endDate),
            'hourly_distribution' => $this->analyticsService->sales()->getHourlySalesDistribution($restaurant, $endDate),
            'total_sales' => $this->analyticsService->sales()->getTotalSales($restaurant, $startDate, $endDate),
            'total_orders' => $this->analyticsService->sales()->getTotalOrders($restaurant, $startDate, $endDate),
            'average_order_value' => $this->analyticsService->sales()->getAverageOrderValue($restaurant, $startDate, $endDate),
        ];

        return view('admin.analytics.sales', compact('restaurant', 'data', 'startDate', 'endDate'));
    }

    /**
     * Get menu analytics
     */
    public function menu(Request $request, Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $startDate = $request->query('start_date')
            ? Carbon::createFromFormat('Y-m-d', $request->query('start_date'))
            : now()->subDays(30);

        $endDate = $request->query('end_date')
            ? Carbon::createFromFormat('Y-m-d', $request->query('end_date'))
            : now();

        $data = [
            'top_selling_dishes' => $this->analyticsService->menu()->getTopSellingDishes($restaurant, 15, $startDate, $endDate),
            'bottom_performing_dishes' => $this->analyticsService->menu()->getBottomPerformingDishes($restaurant, 10, $startDate, $endDate),
            'category_performance' => $this->analyticsService->menu()->getCategoryPerformance($restaurant, $startDate, $endDate),
            'menu_trends' => $this->analyticsService->menu()->getMenuItemTrends($restaurant, 10, $startDate, $endDate),
        ];

        return view('admin.analytics.menu', compact('restaurant', 'data', 'startDate', 'endDate'));
    }

    /**
     * Get order analytics
     */
    public function orders(Request $request, Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $startDate = $request->query('start_date')
            ? Carbon::createFromFormat('Y-m-d', $request->query('start_date'))
            : now()->subDays(30);

        $endDate = $request->query('end_date')
            ? Carbon::createFromFormat('Y-m-d', $request->query('end_date'))
            : now();

        $period = $request->query('period', 'daily');

        $data = [
            'order_trends' => $this->analyticsService->orders()->getOrderTrends($restaurant, $period, $startDate, $endDate),
            'status_distribution' => $this->analyticsService->orders()->getOrderStatusDistribution($restaurant, $startDate, $endDate),
            'peak_times' => $this->analyticsService->orders()->getPeakOrderTimes($restaurant, 10, $startDate, $endDate),
            'average_completion_time' => $this->analyticsService->orders()->getAverageCompletionTime($restaurant, $startDate, $endDate),
            'value_distribution' => $this->analyticsService->orders()->getOrderValueDistribution($restaurant, $startDate, $endDate),
        ];

        return view('admin.analytics.orders', compact('restaurant', 'data', 'startDate', 'endDate', 'period'));
    }

    /**
     * Get staff performance analytics
     */
    public function staff(Request $request, Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $startDate = $request->query('start_date')
            ? Carbon::createFromFormat('Y-m-d', $request->query('start_date'))
            : now()->subDays(30);

        $endDate = $request->query('end_date')
            ? Carbon::createFromFormat('Y-m-d', $request->query('end_date'))
            : now();

        $data = [
            'activity_summary' => $this->analyticsService->staff()->getStaffActivitySummary($restaurant, $startDate, $endDate),
            'waiter_performance' => $this->analyticsService->staff()->getWaiterPerformance($restaurant, $startDate, $endDate),
            'chef_performance' => $this->analyticsService->staff()->getChefPerformance($restaurant, $startDate, $endDate),
            'efficiency_score' => $this->analyticsService->staff()->getStaffEfficiencyScore($restaurant, $startDate, $endDate),
        ];

        return view('admin.analytics.staff', compact('restaurant', 'data', 'startDate', 'endDate'));
    }

    /**
     * Export analytics data as JSON
     */
    public function export(Request $request, Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $startDate = $request->query('start_date')
            ? Carbon::createFromFormat('Y-m-d', $request->query('start_date'))
            : now()->subDays(30);

        $endDate = $request->query('end_date')
            ? Carbon::createFromFormat('Y-m-d', $request->query('end_date'))
            : now();

        $data = $this->analyticsService->getDashboardData($restaurant, $startDate, $endDate);

        return response()->json($data);
    }
}
