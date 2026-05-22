# Kite Architecture Documentation

## System Architecture Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                        CLIENT LAYER                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐           │
│  │   Browser    │  │   Tablet     │  │   Mobile     │           │
│  │  (Customer)  │  │  (Waiter)    │  │  (Kitchen)   │           │
│  └──────────────┘  └──────────────┘  └──────────────┘           │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                            │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  Blade Templates (HTML + Tailwind CSS + Alpine.js)       │   │
│  │  - Public Menu View                                      │   │
│  │  - Admin Dashboard                                       │   │
│  │  - POS Interface                                         │   │
│  │  - Kitchen Display System                                │   │
│  │  - Super Admin Dashboard                                 │   │
│  └──────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                    ROUTING LAYER                                 │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  Laravel Routes (routes/web.php)                         │   │
│  │  - Public Routes                                         │   │
│  │  - Authentication Routes                                 │   │
│  │  - Restaurant Routes (Path & Subdomain)                  │   │
│  │  - Super Admin Routes                                    │   │
│  └──────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                   MIDDLEWARE LAYER                               │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  - RestaurantContextMiddleware (Set tenant context)      │   │
│  │  - EnsureRestaurantIsVerified (Verify restaurant)        │   │
│  │  - RoleMiddleware (Check user role)                      │   │
│  │  - Authenticate (Check authentication)                   │   │
│  │  - CSRF Protection                                       │   │
│  └──────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                  APPLICATION LAYER                               │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  Controllers (Business Logic)                            │   │
│  │  - AuthController                                        │   │
│  │  - CategoryController                                    │   │
│  │  - MenuItemController                                    │   │
│  │  - OrderController                                       │   │
│  │  - AdminDashboardController                              │   │
│  │  - SuperAdminController                                  │   │
│  └──────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                    MODEL LAYER                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  Eloquent Models (Data Abstraction)                      │   │
│  │  - User, Restaurant, Category, MenuItem                  │   │
│  │  - Order, OrderItem, Table, Payment                      │   │
│  │  - Subscription, RestaurantSetting, Role                 │   │
│  └──────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
                              ↓
┌─────────────────────────────────────────────────────────────────┐
│                   DATABASE LAYER                                 │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  MySQL Database                                          │   │
│  │  - Multi-tenant data storage                             │   │
│  │  - Relationships and constraints                         │   │
│  │  - Transactions for data integrity                       │   │
│  └──────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
```

---

## Multi-Tenant Architecture

### Tenant Isolation Strategy

#### 1. Slug-Based Routing
```
Path-based:     http://localhost:8000/{restaurant-slug}/admin
Subdomain-based: http://{restaurant-slug}.kite.test/admin
```

#### 2. Data Scoping
All queries are automatically scoped to the current restaurant:
```php
// Automatic scoping in middleware
$restaurant = Restaurant::where('slug', $slug)->firstOrFail();
view()->share('currentRestaurant', $restaurant);

// In controllers
$items = MenuItem::where('restaurant_id', auth()->user()->restaurant_id)->get();
```

#### 3. Middleware Chain
```
Request → RestaurantContextMiddleware → RoleMiddleware → Controller
         (Set tenant context)          (Check permissions)
```

### Tenant Context Flow

```
1. User accesses /{slug}/admin
   ↓
2. RestaurantContextMiddleware extracts slug
   ↓
3. Middleware loads Restaurant model
   ↓
4. Middleware shares restaurant with views
   ↓
5. Controller receives restaurant context
   ↓
6. All queries automatically scoped to restaurant_id
   ↓
7. Response rendered with restaurant branding
```

---

## Authentication & Authorization

### Authentication Flow

```
User Login
   ↓
LoginController::login()
   ↓
Validate credentials
   ↓
Check if user is active
   ↓
Check if restaurant is verified
   ↓
Check if restaurant is not suspended
   ↓
Auth::attempt() - Create session
   ↓
redirectToDashboard() - Route based on role
   ↓
User logged in
```

### Role-Based Access Control

```
Roles:
├── super_admin (Platform administrator)
├── admin (Restaurant owner/manager)
├── waiter (Order taking staff)
├── chef (Kitchen staff)
└── cashier (Payment processing)

Permissions:
├── super_admin → All platform features
├── admin → Restaurant management, menu, analytics
├── waiter → POS, order taking
├── chef → Kitchen display, order updates
└── cashier → Payment processing, receipts
```

### Middleware Protection

```php
// Route protection
Route::middleware(['auth', 'role:admin', 'restaurant.verified'])->group(function () {
    // Admin routes
});

// Middleware execution order
1. Authenticate - Check if user is logged in
2. RestaurantContextMiddleware - Set tenant context
3. RoleMiddleware - Check user role
4. EnsureRestaurantIsVerified - Verify restaurant status
```

---

## Data Flow Diagrams

### Order Creation Flow

```
Customer
   ↓
Browse Menu (GET /{slug})
   ↓
Select Items
   ↓
Add to Cart (Alpine.js)
   ↓
Checkout (POST /{slug}/checkout)
   ↓
OrderController::store()
   ├─ Validate table_number and items
   ├─ Start database transaction
   ├─ Verify prices from menu_items table
   ├─ Calculate total_price
   ├─ Create Order record
   ├─ Create OrderItem records
   ├─ Commit transaction
   └─ Return JSON response
   ↓
Order created in database
   ↓
POS Dashboard updates (AJAX poll)
   ↓
Kitchen Dashboard updates (AJAX poll)
```

### Order Status Update Flow

```
Kitchen Staff
   ↓
Kitchen Dashboard (GET /{slug}/kitchen)
   ↓
View Orders in Kanban
   ↓
Click "Start Cooking" (PATCH /{slug}/orders/{id}/preparing)
   ↓
OrderController::updateToPreparing()
   ├─ Verify restaurant_id matches
   ├─ Update order status to 'preparing'
   ├─ Return JSON response
   └─ Redirect to kitchen dashboard
   ↓
Order status updated
   ↓
POS Dashboard updates (AJAX poll)
   ↓
Kitchen Dashboard updates (AJAX poll)
```

### Menu Management Flow

```
Restaurant Admin
   ↓
Access Admin Dashboard (GET /{slug}/admin)
   ↓
Click "Manage Menu Items"
   ↓
View Menu Items List (GET /{slug}/admin/menu-items)
   ↓
Click "Add Menu Item"
   ↓
Menu Item Form (GET /{slug}/admin/menu-items/create)
   ├─ Load categories
   ├─ Display form with live preview
   └─ Alpine.js handles image preview
   ↓
Submit Form (POST /{slug}/admin/menu-items)
   ↓
MenuItemController::store()
   ├─ Validate input
   ├─ Set restaurant_id automatically
   ├─ Upload image to storage
   ├─ Create MenuItem record
   └─ Redirect to list
   ↓
Menu item created
   ↓
Public menu updated automatically
```

---

## Database Schema Relationships

```
User
├── restaurant_id → Restaurant (Many-to-One)
└── role → Role (Many-to-One)

Restaurant
├── users → User (One-to-Many)
├── categories → Category (One-to-Many)
├── menu_items → MenuItem (One-to-Many)
├── orders → Order (One-to-Many)
├── tables → Table (One-to-Many)
├── payments → Payment (One-to-Many)
├── settings → RestaurantSetting (One-to-One)
└── subscription → Subscription (One-to-One)

Category
├── restaurant_id → Restaurant (Many-to-One)
└── menu_items → MenuItem (One-to-Many)

MenuItem
├── restaurant_id → Restaurant (Many-to-One)
├── category_id → Category (Many-to-One)
└── order_items → OrderItem (One-to-Many)

Order
├── restaurant_id → Restaurant (Many-to-One)
├── table_id → Table (Many-to-One, optional)
├── order_items → OrderItem (One-to-Many)
└── payments → Payment (One-to-Many)

OrderItem
├── order_id → Order (Many-to-One)
└── menu_item_id → MenuItem (Many-to-One)

Table
├── restaurant_id → Restaurant (Many-to-One)
└── orders → Order (One-to-Many)

Payment
├── restaurant_id → Restaurant (Many-to-One)
└── order_id → Order (Many-to-One)

Subscription
└── restaurant_id → Restaurant (One-to-One)

RestaurantSetting
└── restaurant_id → Restaurant (One-to-One)
```

---

## Request/Response Cycle

### Typical Request Flow

```
1. HTTP Request arrives
   ↓
2. Laravel Kernel processes request
   ↓
3. Global middleware applied
   ├─ TrustProxies
   ├─ HandleCors
   ├─ ValidatePostSize
   └─ ...
   ↓
4. Route middleware applied
   ├─ RestaurantContextMiddleware
   ├─ Authenticate
   ├─ RoleMiddleware
   └─ EnsureRestaurantIsVerified
   ↓
5. Route matched
   ↓
6. Controller action executed
   ├─ Validate input
   ├─ Query database
   ├─ Process business logic
   └─ Prepare response
   ↓
7. Response rendered
   ├─ Blade template compiled
   ├─ CSS/JS assets included
   └─ HTML generated
   ↓
8. Response sent to client
   ↓
9. Browser renders page
```

---

## Security Architecture

### Security Layers

```
Layer 1: Authentication
├─ Email/password validation
├─ Session management
└─ CSRF token protection

Layer 2: Authorization
├─ Role-based access control
├─ Restaurant verification check
└─ Middleware permission checks

Layer 3: Data Isolation
├─ Automatic restaurant_id scoping
├─ Soft deletes for data recovery
└─ Foreign key constraints

Layer 4: Input Validation
├─ Request validation rules
├─ File upload validation
└─ Type casting

Layer 5: Output Encoding
├─ Blade escaping
├─ JSON encoding
└─ HTML entity encoding
```

### Security Checklist

```
✅ CSRF Protection - All forms have CSRF tokens
✅ SQL Injection Prevention - Eloquent ORM with parameterized queries
✅ XSS Prevention - Blade template escaping
✅ Authentication - Session-based with password hashing
✅ Authorization - Role-based middleware checks
✅ Data Isolation - Restaurant ID verification on all queries
✅ File Upload - Validation and storage in public directory
✅ Password Security - Bcrypt hashing with configurable rounds
✅ Rate Limiting - Can be added to routes
✅ HTTPS - Recommended for production
```

---

## Performance Optimization

### Database Optimization

```php
// Eager loading to prevent N+1 queries
$restaurants = Restaurant::with('users', 'categories', 'orders')->get();

// Indexing strategy
- restaurant_id (Foreign key)
- slug (Unique, for route lookup)
- status (For filtering)
- created_at (For sorting)

// Query optimization
- Use select() to fetch only needed columns
- Use whereHas() for relationship filtering
- Use paginate() for large result sets
```

### Caching Strategy

```php
// Cache restaurant settings
Cache::remember("restaurant.{$id}.settings", 3600, function () {
    return RestaurantSetting::find($id);
});

// Cache menu items
Cache::remember("restaurant.{$id}.menu", 1800, function () {
    return MenuItem::where('restaurant_id', $id)->get();
});
```

### Frontend Optimization

```
- Minified CSS/JS
- Image optimization
- Lazy loading for images
- AJAX for real-time updates
- Alpine.js for lightweight interactivity
- Tailwind CSS for smaller bundle size
```

---

## Scalability Considerations

### Horizontal Scaling

```
Load Balancer
├─ Server 1 (Laravel App)
├─ Server 2 (Laravel App)
└─ Server 3 (Laravel App)
   ↓
Shared MySQL Database
   ↓
Shared File Storage (S3)
```

### Vertical Scaling

```
- Increase server resources (CPU, RAM)
- Optimize database queries
- Implement caching layer (Redis)
- Use CDN for static assets
- Implement queue system for heavy tasks
```

### Database Scaling

```
- Read replicas for analytics queries
- Partitioning by restaurant_id
- Archive old orders
- Optimize indexes
- Regular maintenance and cleanup
```

---

## Deployment Architecture

### Development Environment

```
Local Machine
├─ PHP 8.5
├─ MySQL 8.0
├─ Node.js
└─ Laravel Valet/Homestead
```

### Production Environment

```
Load Balancer (SSL)
   ↓
Web Servers (Laravel)
├─ Server 1
├─ Server 2
└─ Server 3
   ↓
Database Server (MySQL)
├─ Primary
└─ Replica
   ↓
File Storage (S3)
   ↓
CDN (CloudFront)
   ↓
Monitoring & Logging
```

---

## Error Handling & Logging

### Error Handling Strategy

```
1. Validation Errors
   └─ Return to form with error messages

2. Authentication Errors
   └─ Redirect to login

3. Authorization Errors
   └─ Return 403 Forbidden

4. Not Found Errors
   └─ Return 404 Not Found

5. Server Errors
   └─ Log error and show generic message
```

### Logging

```
Channels:
├─ single - Single file log
├─ daily - Daily rotating logs
├─ slack - Slack notifications
└─ syslog - System log

Log Levels:
├─ emergency
├─ alert
├─ critical
├─ error
├─ warning
├─ notice
├─ info
└─ debug
```

---

## Future Architecture Enhancements

### Real-Time Communication

```
Current: AJAX polling (3-5 second intervals)
Future: WebSockets (instant updates)

Implementation:
├─ Laravel Echo
├─ Pusher or Socket.io
└─ Real-time order notifications
```

### Microservices

```
Future architecture:
├─ Auth Service
├─ Menu Service
├─ Order Service
├─ Payment Service
├─ Analytics Service
└─ Notification Service
```

### Message Queue

```
For heavy operations:
├─ Send emails
├─ Generate reports
├─ Process payments
├─ Update analytics
└─ Send notifications

Using:
├─ Laravel Queue
├─ Redis or database driver
└─ Scheduled jobs
```

---

## Conclusion

Kite's architecture is designed to be:
- **Scalable** - Handle multiple restaurants and high traffic
- **Secure** - Multi-layer security with data isolation
- **Maintainable** - Clear separation of concerns
- **Extensible** - Easy to add new features
- **Performant** - Optimized queries and caching

The multi-tenant design ensures efficient resource utilization while maintaining complete data isolation between restaurants.
