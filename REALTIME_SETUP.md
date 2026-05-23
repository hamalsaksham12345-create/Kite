# Real-Time Kitchen Display System - Setup Guide

## Quick Start (Local Development)

The real-time kitchen display system is already configured for local development using the database broadcasting driver.

### 1. Verify Configuration

Check `.env` file:
```env
BROADCAST_CONNECTION=database
```

### 2. Run Migrations

```bash
php artisan migrate
```

This creates the `broadcasts` table needed for storing events.

### 3. Test the System

#### Option A: Manual Testing

1. **Terminal 1** - Start Laravel development server:
   ```bash
   php artisan serve
   ```

2. **Terminal 2** - Start queue worker (optional, for async events):
   ```bash
   php artisan queue:work
   ```

3. **Browser 1** - Open kitchen dashboard:
   ```
   http://localhost:8000/marios-pizza/kitchen
   ```

4. **Browser 2** - Open public menu:
   ```
   http://localhost:8000/marios-pizza
   ```

5. **Place an order** from the menu
   - Watch it appear instantly in the kitchen dashboard
   - No page refresh needed!

6. **Update order status**:
   - Click "Start Cooking" → order moves to "In Preparation"
   - Click "Mark as Ready" → order moves to "Food Ready"

#### Option B: Automated Testing

```bash
php artisan test tests/Feature/OrderBroadcastingTest.php
```

## How It Works (Local)

1. Order placed → `OrderStatusUpdated` event dispatched
2. Event stored in `broadcasts` table
3. Kitchen dashboard polls `/orders` endpoint every 5 seconds
4. New orders appear instantly (or within 5 seconds)
5. Status changes trigger instant UI updates

## Production Setup

### Option 1: Pusher (Recommended for SaaS)

**Pros**: Managed service, reliable, scales automatically

1. Sign up at [pusher.com](https://pusher.com)
2. Create an app and get credentials
3. Update `.env`:
   ```env
   BROADCAST_CONNECTION=pusher
   PUSHER_APP_ID=your_app_id
   PUSHER_APP_KEY=your_app_key
   PUSHER_APP_SECRET=your_app_secret
   PUSHER_HOST=api-mt.pusher.com
   PUSHER_PORT=443
   PUSHER_SCHEME=https
   ```
4. Update kitchen dashboard to use Pusher's Echo client (already configured)
5. Deploy and test

### Option 2: Redis + Socket.io

**Pros**: Self-hosted, full control, lower cost at scale

1. Install Redis:
   ```bash
   # macOS
   brew install redis
   brew services start redis
   
   # Ubuntu
   sudo apt-get install redis-server
   sudo systemctl start redis-server
   ```

2. Install Socket.io server:
   ```bash
   npm install -g laravel-echo-server
   laravel-echo-server init
   ```

3. Update `.env`:
   ```env
   BROADCAST_CONNECTION=redis
   REDIS_HOST=127.0.0.1
   REDIS_PORT=6379
   ```

4. Start Echo server:
   ```bash
   laravel-echo-server start
   ```

### Option 3: Ably (Managed WebSockets)

**Pros**: Easy setup, reliable, good for global distribution

1. Sign up at [ably.io](https://ably.io)
2. Get API key
3. Update `.env`:
   ```env
   BROADCAST_CONNECTION=ably
   ABLY_KEY=your_api_key
   ```

## Testing Real-Time Updates

### Test 1: Order Creation

```bash
# Create an order via API
curl -X POST http://localhost:8000/marios-pizza/checkout \
  -H "Content-Type: application/json" \
  -d '{
    "table_number": "5",
    "items": [
      {"id": 1, "quantity": 2}
    ]
  }'
```

Watch the kitchen dashboard - order appears instantly!

### Test 2: Status Updates

```bash
# Update order status
curl -X PATCH http://localhost:8000/marios-pizza/orders/1/preparing \
  -H "X-CSRF-TOKEN: your_csrf_token"
```

Watch the order move to "In Preparation" column!

### Test 3: Multiple Browsers

1. Open kitchen dashboard in 3 different browser windows
2. Place an order from the menu
3. All 3 windows update simultaneously
4. No refresh needed!

## Troubleshooting

### Orders not updating in real-time

**Check 1**: Verify broadcasts table exists
```bash
php artisan migrate:status
# Should show: 2026_05_22_000005_create_broadcasts_table ✓
```

**Check 2**: Check browser console for errors
- Open DevTools (F12)
- Check Console tab for JavaScript errors
- Check Network tab for failed requests

**Check 3**: Verify user is authenticated
- Kitchen dashboard requires authentication
- User must belong to the restaurant

**Check 4**: Check event is being dispatched
```bash
# Add logging to OrderController
Log::info('Order status updated', ['order_id' => $order->id]);
```

### Connection shows "Disconnected"

This is **normal** with database broadcasting. The system uses polling fallback:
- Orders update every 5 seconds
- No persistent WebSocket connection needed
- Upgrade to Pusher/Redis for true real-time

### High database load

If using database broadcasting in production:
- Consider upgrading to Pusher or Redis
- Database broadcasting stores all events in `broadcasts` table
- Clean up old broadcasts periodically:
  ```bash
  php artisan broadcasts:cleanup
  ```

## Performance Tips

### Local Development
- Database broadcasting is fine
- Polling every 5 seconds is acceptable
- No additional services needed

### Small Restaurant (< 50 orders/day)
- Database broadcasting works
- Consider Pusher for better UX

### Medium Restaurant (50-500 orders/day)
- Use Pusher or Redis
- Database broadcasting may cause slowdowns

### Large Restaurant (> 500 orders/day)
- Use Redis + Socket.io or Pusher
- Consider load balancing
- Monitor WebSocket connections

## Monitoring

### Check broadcasts table size
```bash
php artisan tinker
>>> DB::table('broadcasts')->count()
>>> DB::table('broadcasts')->latest()->first()
```

### Monitor event dispatch
```bash
# In OrderController, add:
Log::info('Event dispatched', [
    'order_id' => $order->id,
    'status' => $order->status,
    'timestamp' => now(),
]);
```

### Check channel subscriptions
```bash
# With Pusher
curl https://api-mt.pusher.com/apps/{APP_ID}/channels \
  -u {APP_KEY}:{APP_SECRET}
```

## Next Steps

1. **Test locally** with the database driver
2. **Deploy to staging** with Pusher
3. **Monitor performance** in production
4. **Gather feedback** from kitchen staff
5. **Optimize** based on usage patterns

## Support

For issues or questions:
1. Check `REALTIME_KDS.md` for detailed documentation
2. Review Laravel Broadcasting docs: https://laravel.com/docs/broadcasting
3. Check Pusher docs: https://pusher.com/docs
4. Review code in `app/Events/OrderStatusUpdated.php`

## Files Reference

- **Event**: `app/Events/OrderStatusUpdated.php`
- **Controller**: `app/Http/Controllers/OrderController.php`
- **Channels**: `routes/channels.php`
- **Config**: `config/broadcasting.php`
- **Migration**: `database/migrations/2026_05_22_000005_create_broadcasts_table.php`
- **View**: `resources/views/restaurant/kitchen/dashboard.blade.php`
- **Documentation**: `REALTIME_KDS.md`
