# Analytics Dashboard - Kite Restaurant Management

## Overview

The Analytics Dashboard provides restaurant owners with comprehensive insights into their business performance. It features a clean, organized architecture with separate services for different analytics domains, following best practices for maintainability and scalability.

## Architecture

### Service Layer (4 Specialized Services)

The analytics system is built on a service-oriented architecture with clear separation of concerns:

#### 1. **SalesAnalyticsService**
Handles all sales-related metrics and trends.

**Methods:**
- `getDailySales()` - Daily sales data for a date range
- `getTotalSales()` - Total sales for a period
- `getTotalOrders()` - Total order count
- `getAverageOrderValue()` - Average order value calculation
- `getSalesComparison()` - Compare two periods
- `getHourlySalesDistribution()` - Hourly breakdown

**Example Usage:**
```php
$salesService = app(SalesAnalyticsService::class);
$dailySales = $salesService->getDailySales($restaurant, $startDate, $endDate);
$totalSales = $salesService->getTotalSales($restaurant);
```

#### 2. **MenuAnalyticsService**
Analyzes menu item performance and trends.

**Methods:**
- `getTopSellingDishes()` - Top performing items
- `getBottomPerformingDishes()` - Underperforming items
- `getCategoryPerformance()` - Category-level analysis
- `getMenuItemTrends()` - Item trends with comparisons
- `getItemsByPriceRange()` - Filter items by price

**Example Usage:**
```php
$menuService = app(MenuAnalyticsService::class);
$topDishes = $menuService->getTopSellingDishes($restaurant, 10);
$trends = $menuService->getMenuItemTrends($restaurant, 5);
```

#### 3. **OrderAnalyticsService**
Provides order-level insights and patterns.

**Methods:**
- `getOrderStatusDistribution()` - Status breakdown
- `getOrderTrends()` - Trends over time (daily/weekly/monthly)
- `getAverageCompletionTime()` - Order completion metrics
- `getPeakOrderTimes()` - Busiest hours
- `getOrderValueDistribution()` - Order value ranges

**Example Usage:**
```php
$orderService = app(OrderAnalyticsService::class);
$trends = $orderService->getOrderTrends($restaurant, 'daily');
$peakTimes = $orderService->getPeakOrderTimes($restaurant);
```

#### 4. **StaffPerformanceService**
Tracks staff metrics and efficiency.

**Methods:**
- `getWaiterPerformance()` - Waiter metrics
- `getTopWaiters()` - Top performing waiters
- `getChefPerformance()` - Chef metrics
- `getStaffActivitySummary()` - Overall staff stats
- `getStaffEfficiencyScore()` - Efficiency calculation

**Example Usage:**
```php
$staffService = app(StaffPerformanceService::class);
$waiters = $staffService->getWaiterPerformance($restaurant);
$score = $staffService->getStaffEfficiencyScore($restaurant);
```

#### 5. **AnalyticsService** (Coordinator)
Coordinates all analytics services and provides unified access.

**Methods:**
- `getDashboardData()` - Complete dashboard data
- `getQuickStats()` - Today vs yesterday comparison
- `sales()` - Access sales service
- `menu()` - Access menu service
- `orders()` - Access order service
- `staff()` - Access staff service

**Example Usage:**
```php
$analytics = app(AnalyticsService::class);
$data = $analytics->getDashboardData($restaurant, $startDate, $endDate);
$stats = $analytics->getQuickStats($restaurant);
```

## Controller

### AnalyticsController

A lean controller with 6 methods, each delegating to services:

```php
public function index()          // Dashboard overview
public function sales()          // Sales analytics
public function menu()           // Menu analytics
public function orders()         // Order analytics
public function staff()          // Staff performance
public function export()         // JSON export
```

**Key Principle:** Controller only handles HTTP concerns (routing, authorization, view rendering). All business logic is in services.

## Routes

```
GET  /admin/analytics                    → Dashboard
GET  /admin/analytics/sales              → Sales analytics
GET  /admin/analytics/menu               → Menu analytics
GET  /admin/analytics/orders             → Order analytics
GET  /admin/analytics/staff              → Staff performance
GET  /admin/analytics/export             → JSON export
```

All routes support date range filtering:
```
?start_date=2026-05-01&end_date=2026-05-31
```

## Views

### 1. Dashboard (`dashboard.blade.php`)
**Overview of all metrics:**
- Quick stats (today vs yesterday)
- Top 5 selling dishes
- Daily sales trend
- Category performance
- Peak order times
- Staff performance summary

### 2. Sales (`sales.blade.php`)
**Detailed sales analysis:**
- Total sales, orders, average order value
- Daily sales trend with visual bars
- Hourly distribution for today

### 3. Menu (`menu.blade.php`)
**Menu item performance:**
- Category performance table
- Top selling dishes
- Bottom performing dishes
- Menu trends with percentage changes

### 4. Orders (`orders.blade.php`)
**Order patterns and trends:**
- Order trends (daily/weekly/monthly)
- Peak order times
- Order value distribution
- Order status distribution

### 5. Staff (`staff.blade.php`)
**Staff performance metrics:**
- Activity summary (staff count, efficiency score)
- Waiter performance table
- Chef performance table
- Activity summary stats

## Data Flow

```
Controller
    ↓
AnalyticsService (Coordinator)
    ↓
    ├─→ SalesAnalyticsService
    ├─→ MenuAnalyticsService
    ├─→ OrderAnalyticsService
    └─→ StaffPerformanceService
    ↓
Database Queries (Optimized)
    ↓
View (Rhetorich Design)
```

## Key Features

### 1. **Daily Sales Tracking**
- Track sales by day
- Compare with previous periods
- Identify trends

### 2. **Top-Selling Dishes**
- Rank items by quantity sold
- Show revenue contribution
- Identify bestsellers

### 3. **Revenue Trends**
- Daily/weekly/monthly trends
- Period comparisons
- Growth analysis

### 4. **Order Status Distribution**
- Pending, preparing, ready, completed
- Visual breakdown
- Status metrics

### 5. **Waiter Performance**
- Orders taken
- Revenue generated
- Average order value
- Orders per day

### 6. **Chef Efficiency**
- Orders prepared
- Items prepared
- Average completion time
- Items per order

### 7. **Peak Order Times**
- Identify busiest hours
- Plan staffing
- Optimize operations

### 8. **Order Value Distribution**
- Categorize orders by value
- Identify customer segments
- Revenue distribution

### 9. **Category Performance**
- Revenue by category
- Items sold by category
- Category trends

### 10. **Menu Trends**
- Compare current vs previous period
- Percentage changes
- Trend direction (up/down)

## Development Rules Followed

### ✅ Small Controllers
- Only 6 methods in AnalyticsController
- Each method is 5-10 lines
- No business logic in controller

### ✅ Separate Services
- 4 specialized services for different domains
- Each service has single responsibility
- Services are reusable and testable

### ✅ Organized Queries
- Each service handles specific domain
- Queries are optimized with proper grouping
- Database access is centralized

### ✅ Reusable Components
- Services can be used independently
- Each method is self-contained
- Easy to extend and maintain

### ✅ No Frontend/Backend Logic Mixing
- Controllers handle HTTP only
- Services handle business logic
- Views handle presentation only

### ✅ No Huge Files
- SalesAnalyticsService: ~150 lines
- MenuAnalyticsService: ~180 lines
- OrderAnalyticsService: ~170 lines
- StaffPerformanceService: ~190 lines
- AnalyticsService: ~120 lines
- AnalyticsController: ~100 lines

## Usage Examples

### Get Dashboard Data
```php
$analytics = app(AnalyticsService::class);
$data = $analytics->getDashboardData($restaurant, $startDate, $endDate);

// Access specific metrics
$topDishes = $data['menu']['top_selling_dishes'];
$waiters = $data['staff']['waiter_performance'];
$trends = $data['orders']['order_trends'];
```

### Get Quick Stats
```php
$stats = $analytics->getQuickStats($restaurant);

echo "Today's Sales: " . $stats['today']['sales'];
echo "Yesterday's Sales: " . $stats['yesterday']['sales'];
echo "Change: " . $stats['comparison']['sales_change_percent'] . "%";
```

### Access Individual Services
```php
// Sales
$sales = $analytics->sales()->getTotalSales($restaurant);

// Menu
$topDishes = $analytics->menu()->getTopSellingDishes($restaurant, 10);

// Orders
$trends = $analytics->orders()->getOrderTrends($restaurant, 'daily');

// Staff
$waiters = $analytics->staff()->getWaiterPerformance($restaurant);
```

## Performance Optimization

### Database Queries
- Uses `selectRaw()` for efficient aggregation
- Groups data at database level
- Minimizes data transfer

### Caching Opportunities
- Dashboard data can be cached for 1 hour
- Quick stats can be cached for 15 minutes
- Implement with: `Cache::remember()`

### Query Optimization
- Eager loading with `with()`
- Proper indexing on `restaurant_id`, `status`, `created_at`
- Date range filtering reduces result set

## Security

### Authorization
- All routes protected with `auth` middleware
- Restaurant isolation via `restaurant_id`
- Only admins can access analytics

### Data Isolation
- Queries scoped to `restaurant_id`
- No cross-restaurant data leakage
- Tenant security enforced

## Future Enhancements

### Phase 2
- Export to CSV/PDF
- Email reports
- Scheduled reports
- Custom date ranges

### Phase 3
- Advanced charts (Chart.js, ApexCharts)
- Predictive analytics
- Anomaly detection
- Forecasting

### Phase 4
- Real-time dashboards
- WebSocket updates
- Mobile app analytics
- API for third-party integration

## Testing

### Unit Tests
```php
// Test SalesAnalyticsService
$service = new SalesAnalyticsService();
$sales = $service->getTotalSales($restaurant);
$this->assertIsNumeric($sales);

// Test MenuAnalyticsService
$topDishes = $service->getTopSellingDishes($restaurant, 10);
$this->assertCount(10, $topDishes);
```

### Integration Tests
```php
// Test full dashboard
$analytics = app(AnalyticsService::class);
$data = $analytics->getDashboardData($restaurant);
$this->assertArrayHasKey('sales', $data);
$this->assertArrayHasKey('menu', $data);
```

## API Endpoints

### Export Analytics
```
GET /admin/analytics/export?start_date=2026-05-01&end_date=2026-05-31
```

Returns JSON with complete analytics data:
```json
{
    "period": {
        "start_date": "2026-05-01",
        "end_date": "2026-05-31"
    },
    "sales": { ... },
    "menu": { ... },
    "orders": { ... },
    "staff": { ... }
}
```

## Troubleshooting

### No Data Showing
- Check date range is correct
- Verify orders exist in database
- Check restaurant_id matches

### Slow Performance
- Check database indexes
- Reduce date range
- Implement caching
- Check query execution time

### Incorrect Calculations
- Verify order status is 'completed'
- Check price fields are numeric
- Verify restaurant_id filtering

## Conclusion

The Analytics Dashboard provides a robust, scalable foundation for restaurant analytics. The service-oriented architecture makes it easy to extend, test, and maintain. Each service is focused on a specific domain, making the codebase clean and organized.

Key benefits:
- ✅ Clean separation of concerns
- ✅ Reusable services
- ✅ Easy to test
- ✅ Scalable architecture
- ✅ Maintainable code
- ✅ Professional design
- ✅ Comprehensive metrics
