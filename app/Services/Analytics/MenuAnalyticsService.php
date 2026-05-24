<?php

namespace App\Services\Analytics;

use App\Models\MenuItem;
use App\Models\OrderItem;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MenuAnalyticsService
{
    /**
     * Get top selling dishes
     */
    public function getTopSellingDishes(Restaurant $restaurant, int $limit = 10, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        return OrderItem::whereHas('order', function ($query) use ($restaurant, $startDate, $endDate) {
            $query->where('restaurant_id', $restaurant->id)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->with('menuItem')
            ->selectRaw('menu_item_id, COUNT(*) as total_quantity, SUM(price * quantity) as total_revenue')
            ->groupBy('menu_item_id')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->menuItem->id,
                    'name' => $item->menuItem->name,
                    'category' => $item->menuItem->category->name,
                    'quantity_sold' => $item->total_quantity,
                    'total_revenue' => round($item->total_revenue, 2),
                    'average_price' => round($item->menuItem->price, 2),
                ];
            });
    }

    /**
     * Get bottom performing dishes
     */
    public function getBottomPerformingDishes(Restaurant $restaurant, int $limit = 5, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        return OrderItem::whereHas('order', function ($query) use ($restaurant, $startDate, $endDate) {
            $query->where('restaurant_id', $restaurant->id)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->with('menuItem')
            ->selectRaw('menu_item_id, COUNT(*) as total_quantity, SUM(price * quantity) as total_revenue')
            ->groupBy('menu_item_id')
            ->orderBy('total_quantity')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->menuItem->id,
                    'name' => $item->menuItem->name,
                    'category' => $item->menuItem->category->name,
                    'quantity_sold' => $item->total_quantity,
                    'total_revenue' => round($item->total_revenue, 2),
                    'average_price' => round($item->menuItem->price, 2),
                ];
            });
    }

    /**
     * Get category performance
     */
    public function getCategoryPerformance(Restaurant $restaurant, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        return OrderItem::whereHas('order', function ($query) use ($restaurant, $startDate, $endDate) {
            $query->where('restaurant_id', $restaurant->id)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->with('menuItem.category')
            ->selectRaw('menu_item_id, COUNT(*) as total_quantity, SUM(price * quantity) as total_revenue')
            ->groupBy('menu_item_id')
            ->get()
            ->groupBy(function ($item) {
                return $item->menuItem->category->name;
            })
            ->map(function ($items, $category) {
                return [
                    'category' => $category,
                    'total_quantity' => $items->sum('total_quantity'),
                    'total_revenue' => round($items->sum('total_revenue'), 2),
                    'item_count' => $items->count(),
                ];
            })
            ->sortByDesc('total_revenue')
            ->values();
    }

    /**
     * Get menu item trends
     */
    public function getMenuItemTrends(Restaurant $restaurant, int $limit = 5, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        $topItems = $this->getTopSellingDishes($restaurant, $limit, $startDate, $endDate);

        return $topItems->map(function ($item) use ($restaurant, $startDate, $endDate) {
            // Get previous period data
            $periodLength = $endDate->diffInDays($startDate);
            $prevStartDate = $startDate->copy()->subDays($periodLength);
            $prevEndDate = $startDate->copy();

            $prevQuantity = OrderItem::whereHas('order', function ($query) use ($restaurant, $prevStartDate, $prevEndDate) {
                $query->where('restaurant_id', $restaurant->id)
                    ->where('status', 'completed')
                    ->whereBetween('created_at', [$prevStartDate, $prevEndDate]);
            })
                ->where('menu_item_id', $item['id'])
                ->sum('quantity');

            $currentQuantity = $item['quantity_sold'];
            $difference = $currentQuantity - $prevQuantity;
            $percentageChange = $prevQuantity > 0 ? round(($difference / $prevQuantity) * 100, 2) : 0;

            return array_merge($item, [
                'previous_quantity' => $prevQuantity,
                'quantity_change' => $difference,
                'percentage_change' => $percentageChange,
                'trend' => $percentageChange >= 0 ? 'up' : 'down',
            ]);
        });
    }

    /**
     * Get items by price range
     */
    public function getItemsByPriceRange(Restaurant $restaurant, float $minPrice, float $maxPrice, ?Carbon $startDate = null, ?Carbon $endDate = null): Collection
    {
        $startDate = $startDate ?? now()->subDays(30);
        $endDate = $endDate ?? now();

        return OrderItem::whereHas('order', function ($query) use ($restaurant, $startDate, $endDate) {
            $query->where('restaurant_id', $restaurant->id)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->whereBetween('price', [$minPrice, $maxPrice])
            ->with('menuItem')
            ->selectRaw('menu_item_id, COUNT(*) as total_quantity, SUM(price * quantity) as total_revenue')
            ->groupBy('menu_item_id')
            ->orderByDesc('total_quantity')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->menuItem->id,
                    'name' => $item->menuItem->name,
                    'price' => round($item->menuItem->price, 2),
                    'quantity_sold' => $item->total_quantity,
                    'total_revenue' => round($item->total_revenue, 2),
                ];
            });
    }
}
