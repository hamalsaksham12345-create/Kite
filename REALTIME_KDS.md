# Real-Time Kitchen Display System (KDS)

## Overview

The Kite platform now includes a real-time Kitchen Display System that uses Laravel Broadcasting to instantly update kitchen staff when orders are placed or status changes occur. No page refresh required.

## Architecture

### Broadcasting Setup

The system uses Laravel's broadcasting feature with database driver for local development:

- **Broadcast Driver**: Database (configured in `.env` as `BROADCAST_CONNECTION=database`)
- **Channel**: Private channel `restaurant.{restaurantId}`
- **Event**: `OrderStatusUpdated`

### Components

#### 1. Event: `OrderStatusUpdated`
**File**: `app/Events/OrderStatusUpdated.php`

Dispatched whenever an order status changes:
- Order created (pending)
- Status changed to preparing
- Status changed to ready
- Status changed to completed

**Broadcast Data**:
```json
{
  "order_id": 1,
  "table_number": "5",
  "status": "preparing",
  "previous_status": "pending",
  "items": [
    {
      "id": 1,
      "name": "Grilled Salmon",
      "quantity": 2,
      "price": 24.99
    }
  ],
  "total_price": 49.98,
  "created_at": "2026-05-22T12:00:00Z",
  "updated_at": "2026-05-22T12:05:00Z"
}
```

#### 2. Channel Authorization
**File**: `routes/channels.php`

Private channel `restaurant.{restaurantId}` is authorized only for users belonging to that restaurant:

```php
Broadcast::privateChannel('restaurant.{restaurantId}', function ($user, $restaurantId) {
    return $user->restaurant_id === (int) $restaurantId;
});
```

#### 3. Kitchen Dashboard
**File**: `resources/views/restaurant/kitchen/dashboard.blade.php`

Real-time Kanban board with three columns:
- **New Orders** (pending) - Yellow background, pulsing animation
- **In Preparation** (preparing) - Blue background
- **Food Ready** (ready) - Green background

Features:
- Live order count updates
- Instant card movement between columns
- Connection status indicator
- Fallback polling every 5 seconds

#### 4. Order Controller Updates
**File**: `app/Http/Controllers/OrderController.php`

All status update methods now dispatch the `OrderStatusUpdated` event:

```php
public function updateToPreparing(Order $order)
{
    $previousStatus = $order->status;
    $order->update(['status' => 'preparing']);
    
    // Dispatch event for real-time updates
    OrderStatusUpdated::dispatch($order, $previousStatus);
    
    return response()->json([...]);
}
```

## How It Works

### Real-Time Flow

1. **Order Placed**
   - Customer places order via public menu
   - `OrderController::store()` creates order with status `pending`
   - `OrderStatusUpdated` event dispatched
   - Event broadcast to `restaurant.{restaurantId}` channel
   - Kitchen dashboard receives event via Echo
   - Order card appears in "New Orders" column with pulse animation

2. **Chef Starts Cooking**
   - Chef clicks "Start Cooking" button
   - `OrderController::updateToPreparing()` updates status to `preparing`
   - `OrderStatusUpdated` event dispatched
   - Kitchen dashboard receives event
   - Order card moves to "In Preparation" column

3. **Food Ready**
   - Chef clicks "Mark as Ready" button
   - `OrderController::updateToReady()` updates status to `ready`
   - `OrderStatusUpdated` event dispatched
   - Kitchen dashboard receives event
   - Order card moves to "Food Ready" column

4. **Order Delivered**
   - Waiter clicks "Delivered" button (from POS)
   - `OrderController::updateToCompleted()` updates status to `completed`
   - `OrderStatusUpdated` event dispatched
   - Kitchen dashboard receives event
   - Order card disappears from view

### Frontend Implementation

The kitchen dashboard uses Alpine.js with Laravel Echo:

```javascript
function kdsApp() {
    return {
        orders: [],
        isConnected: false,
        restaurantId: {{ $currentRestaurant->id }},

        init() {
            this.loadOrders();
            this.setupWebSocket();
            // Fallback polling
            setInterval(() => this.loadOrders(), 5000);
        },

        setupWebSocket() {
            window.Echo.private(`restaurant.${this.restaurantId}`)
                .listen('OrderStatusUpdated', (event) => {
                    this.handleOrderUpdate(event);
                    this.isConnected = true;
                });
        },

        handleOrderUpdate(event) {
            // Update order in local state
            // Recategorize orders
            // UI automatically updates via Alpine
        }
    }
}
```

## Configuration

### Environment Variables

```env
# Broadcasting driver (database for local development)
BROADCAST_CONNECTION=database

# For production with Pusher:
# BROADCAST_CONNECTION=pusher
# PUSHER_APP_ID=your_app_id
# PUSHER_APP_KEY=your_app_key
# PUSHER_APP_SECRET=your_app_secret
# PUSHER_HOST=api-mt.pusher.com
# PUSHER_PORT=443
# PUSHER_SCHEME=https
```

### Database

The broadcasts table stores event data:

```sql
CREATE TABLE broadcasts (
    id BIGINT PRIMARY KEY,
    channel VARCHAR(255),
    event VARCHAR(255),
    data LONGTEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX (channel),
    INDEX (created_at)
);
```

## Fallback Mechanism

If WebSocket connection fails:
- Kitchen dashboard falls back to polling every 5 seconds
- Connection status indicator shows "Disconnected"
- Orders still update, just with slight delay
- No manual refresh needed

## Scaling to Production

### Option 1: Pusher (Recommended for SaaS)

1. Sign up at [pusher.com](https://pusher.com)
2. Create an app and get credentials
3. Update `.env`:
   ```env
   BROADCAST_CONNECTION=pusher
   PUSHER_APP_ID=your_id
   PUSHER_APP_KEY=your_key
   PUSHER_APP_SECRET=your_secret
   ```
4. Update kitchen dashboard to use Pusher's Echo client

### Option 2: Redis + Socket.io

1. Install Redis
2. Set up Socket.io server
3. Update `.env`:
   ```env
   BROADCAST_CONNECTION=redis
   ```
4. Run Socket.io server alongside Laravel

### Option 3: Ably (Managed WebSockets)

1. Sign up at [ably.io](https://ably.io)
2. Get API key
3. Update `.env`:
   ```env
   BROADCAST_CONNECTION=ably
   ABLY_KEY=your_key
   ```

## Testing

### Manual Testing

1. Open kitchen dashboard in one browser tab
2. Open public menu in another tab
3. Place an order
4. Watch order appear instantly in kitchen dashboard
5. Click "Start Cooking" - order moves to "In Preparation"
6. Click "Mark as Ready" - order moves to "Food Ready"

### Automated Testing

```php
// Test event is dispatched
Event::fake();
$order = Order::factory()->create();
OrderStatusUpdated::dispatch($order, 'pending');
Event::assertDispatched(OrderStatusUpdated::class);

// Test channel authorization
$user = User::factory()->create(['restaurant_id' => 1]);
$this->assertTrue(
    Broadcast::channel('restaurant.1', $user)
);
```

## Performance Considerations

- **Database Broadcasting**: Suitable for development and small deployments
- **Polling Fallback**: 5-second interval balances responsiveness and server load
- **Channel Isolation**: Each restaurant has its own private channel
- **Event Data**: Minimal payload (order ID, status, items) for fast transmission

## Troubleshooting

### Orders not updating in real-time

1. Check `.env` has `BROADCAST_CONNECTION=database`
2. Verify `broadcasts` table exists: `php artisan migrate`
3. Check browser console for JavaScript errors
4. Verify user is authenticated and belongs to restaurant
5. Check channel authorization in `routes/channels.php`

### Connection shows "Disconnected"

1. This is normal if using database broadcasting (no persistent connection)
2. Orders will still update via polling fallback
3. For persistent connections, upgrade to Pusher or Redis

### Events not being stored

1. Check `broadcasts` table has data
2. Verify event is being dispatched in OrderController
3. Check `BROADCAST_CONNECTION` is set to `database`
4. Run `php artisan migrate` to create table

## Future Enhancements

- [ ] Sound/visual alerts for new orders
- [ ] Order priority system
- [ ] Estimated preparation time
- [ ] Multi-kitchen support
- [ ] Mobile app integration
- [ ] Order history and analytics
- [ ] Staff performance metrics
- [ ] Integration with POS system

## Files Modified/Created

- `app/Events/OrderStatusUpdated.php` - Broadcasting event
- `app/Http/Controllers/OrderController.php` - Event dispatch
- `routes/channels.php` - Channel authorization
- `config/broadcasting.php` - Broadcasting configuration
- `database/migrations/2026_05_22_000005_create_broadcasts_table.php` - Broadcasts table
- `resources/views/restaurant/kitchen/dashboard.blade.php` - Real-time UI
- `resources/views/layouts/app.blade.php` - Added script stacks
- `.env` - Updated BROADCAST_CONNECTION

## References

- [Laravel Broadcasting Documentation](https://laravel.com/docs/broadcasting)
- [Laravel Echo Documentation](https://laravel.com/docs/echo)
- [Pusher Documentation](https://pusher.com/docs)
- [Alpine.js Documentation](https://alpinejs.dev)
