# Kite - Core Modules Documentation

## Module Overview

This document outlines all core modules in the Kite restaurant SaaS platform and their implementation status.

---

## 1. Authentication Module

**Status:** ✅ IMPLEMENTED

### Features:
- User registration (restaurant owners)
- User login with email/password
- Staff registration (waiter, chef)
- Role-based access control (admin, waiter, chef, super_admin)
- Session management
- Logout functionality

### Files:
- `app/Http/Controllers/Auth/LoginController.php`
- `app/Http/Controllers/Auth/RegisterController.php`
- `app/Http/Controllers/RegistrationController.php`
- `resources/views/auth/login.blade.php`
- `resources/views/auth/staff-register.blade.php`

### Routes:
- `POST /login` - User login
- `GET /register` - Registration form
- `POST /register` - Register restaurant
- `GET /join/{restaurant:slug}` - Staff registration form
- `POST /join/{restaurant:slug}` - Register staff member
- `POST /logout` - Logout

---

## 2. Restaurant/Tenant Management

**Status:** ✅ IMPLEMENTED

### Features:
- Restaurant registration and onboarding
- Restaurant verification (super admin approval)
- Restaurant suspension/reactivation
- Multi-tenant architecture with slug-based routing
- Restaurant context middleware
- Branding system (custom colors)
- Restaurant settings management

### Files:
- `app/Models/Restaurant.php`
- `app/Models/RestaurantSetting.php`
- `app/Http/Middleware/RestaurantContextMiddleware.php`
- `app/Http/Middleware/EnsureRestaurantIsVerified.php`
- `app/Http/Controllers/OnboardingController.php`
- `app/Http/Controllers/SuperAdmin/SuperAdminController.php`

### Routes:
- `GET /get-started` - Onboarding registration
- `POST /get-started` - Submit registration
- `GET /onboarding/subscription` - Subscription selection
- `POST /onboarding/subscription` - Process subscription
- `GET /onboarding/pending` - Pending verification page
- `GET /super-admin` - Super admin dashboard
- `GET /super-admin/pending-queue` - Pending restaurants
- `GET /super-admin/restaurants` - All restaurants

---

## 3. Website Builder

**Status:** ⚠️ PARTIAL

### Features:
- Dynamic branding with CSS variables
- Restaurant-specific color scheme
- Public menu view
- Restaurant information display

### Files:
- `resources/views/restaurant/menu.blade.php`
- `resources/css/app.css`
- `resources/views/layouts/app.blade.php`

### Routes:
- `GET /{slug}` - Public menu view
- `GET /{slug}/` - Public menu (subdomain)

### TODO:
- [ ] Website customization interface
- [ ] Custom domain support
- [ ] Page builder
- [ ] SEO optimization
- [ ] Social media integration

---

## 4. Menu Management

**Status:** ✅ IMPLEMENTED

### Features:
- Category management (CRUD)
- Menu item management (CRUD)
- Image uploads for items and categories
- Item availability toggle
- Featured items marking
- Sort order management
- Live preview for menu items
- Price management in Nepali Rs

### Files:
- `app/Http/Controllers/CategoryController.php`
- `app/Http/Controllers/MenuItemController.php`
- `app/Models/Category.php`
- `app/Models/MenuItem.php`
- `resources/views/admin/categories/*`
- `resources/views/admin/menu-items/*`

### Routes:
- `GET /{slug}/admin/categories` - List categories
- `GET /{slug}/admin/categories/create` - Create category form
- `POST /{slug}/admin/categories` - Store category
- `GET /{slug}/admin/categories/{category}/edit` - Edit category form
- `PUT /{slug}/admin/categories/{category}` - Update category
- `DELETE /{slug}/admin/categories/{category}` - Delete category
- `PATCH /{slug}/admin/categories/{category}/toggle-status` - Toggle category status
- `GET /{slug}/admin/menu-items` - List menu items
- `GET /{slug}/admin/menu-items/create` - Create menu item form
- `POST /{slug}/admin/menu-items` - Store menu item
- `GET /{slug}/admin/menu-items/{menuItem}/edit` - Edit menu item form
- `PUT /{slug}/admin/menu-items/{menuItem}` - Update menu item
- `DELETE /{slug}/admin/menu-items/{menuItem}` - Delete menu item
- `PATCH /{slug}/admin/menu-items/{menuItem}/toggle-availability` - Toggle availability
- `PATCH /{slug}/admin/menu-items/{menuItem}/toggle-featured` - Toggle featured status

---

## 5. Table Management

**Status:** ✅ IMPLEMENTED

### Features:
- Table CRUD operations
- Table status management (available, occupied, reserved, maintenance)
- Table capacity management (1-20 seats)
- Table statistics dashboard
- QR code generation for each table
- Table assignment to orders
- Admin interface for table management

### Files:
- `app/Http/Controllers/TableController.php`
- `app/Models/Table.php`
- `resources/views/admin/tables/index.blade.php`
- `resources/views/admin/tables/create.blade.php`
- `resources/views/admin/tables/edit.blade.php`
- `resources/views/admin/tables/qr.blade.php`
- `database/migrations/2026_05_22_000001_create_tables_table.php`

### Routes:
- `GET /{slug}/admin/tables` - List tables
- `GET /{slug}/admin/tables/create` - Create table form
- `POST /{slug}/admin/tables` - Store table
- `GET /{slug}/admin/tables/{table}/edit` - Edit table form
- `PUT /{slug}/admin/tables/{table}` - Update table
- `DELETE /{slug}/admin/tables/{table}` - Delete table
- `PATCH /{slug}/admin/tables/{table}/change-status` - Change table status
- `GET /{slug}/admin/tables/{table}/qr` - Generate QR code

### TODO:
- [ ] Table reservation system
- [ ] Table assignment to orders
- [ ] Table occupancy tracking

---

## 6. Order Management

**Status:** ✅ IMPLEMENTED

### Features:
- Order creation from menu
- Order status tracking (pending, preparing, ready, completed, cancelled)
- Order items with pricing
- Price verification on backend
- Database transactions for safety
- Order retrieval for restaurant staff
- Order status updates

### Files:
- `app/Http/Controllers/OrderController.php`
- `app/Models/Order.php`
- `app/Models/OrderItem.php`
- `database/migrations/2026_05_05_141535_create_orders_table.php`
- `database/migrations/2026_05_05_141601_create_order_items_table.php`

### Routes:
- `POST /{slug}/checkout` - Create order
- `GET /{slug}/orders` - Get restaurant orders (API)
- `PATCH /{slug}/orders/{order}/preparing` - Mark as preparing
- `PATCH /{slug}/orders/{order}/ready` - Mark as ready
- `PATCH /{slug}/orders/{order}/completed` - Mark as completed

---

## 7. Kitchen Display System (KDS)

**Status:** ✅ IMPLEMENTED

### Features:
- Real-time order display
- Kanban board layout (New Orders, In Preparation, Food Ready)
- Order status updates
- Time tracking for orders
- Item quantity display
- Auto-refresh every 3-5 seconds

### Files:
- `resources/views/restaurant/kitchen/dashboard.blade.php`
- `app/Http/Controllers/OrderController.php`

### Routes:
- `GET /{slug}/kitchen` - Kitchen dashboard

---

## 8. Billing/POS

**Status:** ✅ PARTIAL

### Features:
- POS dashboard with table matrix
- Order ticket stream
- Order delivery marking
- Payment model created
- Database structure ready

### Files:
- `resources/views/restaurant/pos/dashboard.blade.php`
- `app/Models/Payment.php`
- `database/migrations/2026_05_22_000002_create_payments_table.php`

### Routes:
- `GET /{slug}/pos` - POS dashboard

### TODO:
- [ ] Payment processing
- [ ] Multiple payment methods (cash, card, online)
- [ ] Invoice generation
- [ ] Receipt printing
- [ ] Refund management
- [ ] Payment reconciliation

---

## 9. Analytics & Reporting

**Status:** ✅ PARTIAL

### Features:
- Admin dashboard with metrics
- Total revenue calculation
- Total orders count
- Active orders tracking
- Top 5 selling items report
- Revenue by item

### Files:
- `app/Http/Controllers/AdminDashboardController.php`
- `resources/views/restaurant/admin/dashboard.blade.php`

### Routes:
- `GET /{slug}/admin` - Admin dashboard

### TODO:
- [ ] Advanced analytics
- [ ] Date range filtering
- [ ] Revenue trends
- [ ] Customer analytics
- [ ] Staff performance metrics
- [ ] Inventory tracking
- [ ] Export reports (PDF, CSV)

---

## 10. Subscription System

**Status:** ✅ PARTIAL

### Features:
- Subscription model created
- Database structure ready
- Plan management (basic, professional, enterprise)
- Feature flags (analytics, delivery, loyalty program)
- Subscription status tracking

### Files:
- `app/Models/Subscription.php`
- `database/migrations/2026_05_22_000004_create_subscriptions_table.php`

### Routes:
- `GET /onboarding/subscription` - Subscription selection
- `POST /onboarding/subscription` - Process subscription

### TODO:
- [ ] Payment gateway integration
- [ ] Subscription renewal
- [ ] Plan upgrade/downgrade
- [ ] Billing history
- [ ] Invoice generation
- [ ] Subscription cancellation
- [ ] Trial period management

---

## Implementation Summary

| Module | Status | Priority |
|--------|--------|----------|
| Authentication | ✅ Complete | High |
| Restaurant/Tenant Management | ✅ Complete | High |
| Website Builder | ⚠️ Partial | Medium |
| Menu Management | ✅ Complete | High |
| Table Management | ✅ Complete | High |
| Order Management | ✅ Complete | High |
| Kitchen Display | ✅ Complete | High |
| Billing/POS | ⚠️ Partial | High |
| Analytics | ⚠️ Partial | Medium |
| Subscription System | ⚠️ Partial | High |

---

## Next Steps

### High Priority:
1. Complete Billing/POS module (payment processing)
2. Complete Subscription System (payment gateway integration)
3. Complete Table Management (CRUD + reservations)

### Medium Priority:
1. Enhance Analytics module (advanced reports)
2. Complete Website Builder (customization interface)
3. Add inventory management

### Low Priority:
1. Loyalty program
2. Customer reviews
3. Marketing tools

---

## Architecture Notes

- **Multi-tenant:** Uses slug-based routing with middleware context
- **Security:** Restaurant ID verification on all queries
- **Design:** Rhetorich design language (border-4, heavy shadows, no icons)
- **Currency:** Nepali Rs throughout
- **Database:** MySQL with soft deletes for restaurants
- **Authentication:** Laravel's built-in auth with custom roles

