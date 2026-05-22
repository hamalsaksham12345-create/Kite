# Kite - Multi-Tenant Restaurant Management SaaS Platform

![Kite Logo](https://via.placeholder.com/200x50?text=KITE)

## Overview

**Kite** is a comprehensive, multi-tenant SaaS-based restaurant management platform designed to empower restaurant owners with a complete digital ecosystem. From website generation to kitchen operations, Kite provides an all-in-one solution that eliminates the need for separate tools and developers.

### Vision

To democratize restaurant digitization by providing an affordable, scalable, and user-friendly platform that enables restaurants of all sizes to establish a professional online presence and streamline their operations through a single integrated system.

---

## Key Features

### 🏢 For Restaurant Owners

- **No-Code Website Builder** - Create and customize restaurant websites without coding
- **Digital Menu Management** - Easily manage categories, items, pricing, and availability
- **Real-Time Analytics** - Monitor sales, popular dishes, and business performance
- **Staff Management** - Invite and manage waiters, chefs, and cashiers
- **Subscription Plans** - Flexible pricing tiers (Basic, Professional, Enterprise)
- **Custom Branding** - Change colors, logos, and business information instantly

### 👥 For Customers

- **Digital Menu Browsing** - View restaurant menus with images and descriptions
- **Online Ordering** - Place orders directly from the website
- **QR-Based Menu Access** - Scan QR codes at tables for instant menu access
- **Order Tracking** - Real-time order status updates

### 👨‍🍳 For Kitchen Staff

- **Kitchen Display System (KDS)** - Real-time order updates with Kanban board
- **Order Management** - View, prepare, and mark orders as ready
- **Time Tracking** - See how long each order has been in preparation
- **Item Quantities** - Clear visibility of all items in each order

### 💼 For Waiters/POS Staff

- **Digital Ordering System** - Take orders directly from tables using tablets
- **Table Management** - Track table status and occupancy
- **Order Delivery** - Mark orders as delivered to customers
- **Payment Processing** - Process payments and generate receipts

---

## Technology Stack

### Frontend
- **HTML5** - Semantic markup
- **JavaScript (Alpine.js)** - Interactive components and real-time updates
- **Tailwind CSS** - Responsive, utility-first styling
- **Rhetorich Design** - Bold typography, thick borders, heavy shadows

### Backend
- **Laravel 13** - PHP web framework
- **MySQL** - Relational database
- **Laravel Eloquent ORM** - Database abstraction
- **Laravel Middleware** - Authentication and authorization

### Architecture
- **Multi-Tenant** - Slug-based routing with isolated data
- **RESTful APIs** - JSON endpoints for frontend communication
- **Real-Time Updates** - AJAX polling for live data synchronization
- **Database Transactions** - Atomic operations for data integrity

---

## Core Modules

### 1. Authentication & Authorization ✅
- User registration and login
- Staff onboarding with role assignment
- Role-based access control (Super Admin, Admin, Waiter, Chef, Cashier)
- Session management and logout

### 2. Restaurant Management ✅
- Restaurant registration and onboarding
- Super admin approval workflow
- Restaurant verification and suspension
- Multi-tenant data isolation
- Custom branding (colors, logos)

### 3. Menu Management ✅
- Category CRUD operations
- Menu item management with images
- Price management in Nepali Rs
- Item availability and featured status
- Sort order customization
- Live preview functionality

### 4. Order Management ✅
- Order creation from digital menu
- Order status tracking (pending → preparing → ready → completed)
- Price verification on backend
- Order item tracking with quantities
- Database transaction safety

### 5. Kitchen Display System ✅
- Real-time order display
- Kanban board layout (New, In Preparation, Ready)
- Order status updates
- Time tracking for each order
- Auto-refresh every 3-5 seconds

### 6. POS & Billing ⚠️
- Table matrix display
- Order ticket stream
- Payment model and database structure
- **TODO:** Payment gateway integration, multiple payment methods

### 7. Table Management ⚠️
- Table model and database structure
- Table capacity and status tracking
- **TODO:** CRUD operations, reservations, table assignment

### 8. Analytics Dashboard ⚠️
- Total revenue calculation
- Order statistics
- Top selling items report
- Active orders tracking
- **TODO:** Advanced reports, date filtering, trends, exports

### 9. Subscription System ⚠️
- Subscription model and database structure
- Plan management (Basic, Professional, Enterprise)
- Feature flags (analytics, delivery, loyalty)
- **TODO:** Payment processing, renewal, upgrades

### 10. Website Builder ⚠️
- Dynamic branding with CSS variables
- Public menu view
- Restaurant information display
- **TODO:** Customization interface, custom domains, SEO

---

## Project Structure

```
kite/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── SuperAdmin/
│   │   │   ├── AdminDashboardController.php
│   │   │   ├── CategoryController.php
│   │   │   ├── MenuItemController.php
│   │   │   ├── OrderController.php
│   │   │   └── ...
│   │   └── Middleware/
│   │       ├── RestaurantContextMiddleware.php
│   │       ├── EnsureRestaurantIsVerified.php
│   │       ├── RoleMiddleware.php
│   │       └── ...
│   └── Models/
│       ├── User.php
│       ├── Restaurant.php
│       ├── Category.php
│       ├── MenuItem.php
│       ├── Order.php
│       ├── OrderItem.php
│       ├── Table.php
│       ├── Payment.php
│       ├── Subscription.php
│       ├── RestaurantSetting.php
│       └── Role.php
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   ├── admin/
│   │   ├── restaurant/
│   │   ├── super-admin/
│   │   └── layouts/
│   ├── css/
│   └── js/
├── routes/
│   └── web.php
├── config/
├── public/
├── storage/
└── tests/
```

---

## Database Schema

### Core Tables
- **users** - User accounts with roles
- **restaurants** - Restaurant information and settings
- **roles** - Role definitions
- **categories** - Menu categories
- **menu_items** - Individual menu items
- **tables** - Restaurant tables
- **orders** - Customer orders
- **order_items** - Items within orders
- **payments** - Payment records
- **subscriptions** - Restaurant subscriptions
- **restaurant_settings** - Restaurant configuration

---

## Getting Started

### Prerequisites
- PHP 8.5+
- MySQL 8.0+
- Composer
- Node.js & npm

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/yourusername/kite.git
cd kite
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database setup**
```bash
php artisan migrate
php artisan db:seed
```

5. **Build assets**
```bash
npm run build
```

6. **Start development server**
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the platform.

---

## Usage

### For Super Administrators

1. Access `/super-admin` dashboard
2. View pending restaurant registrations
3. Approve or reject restaurants
4. Manage all restaurants
5. Suspend/reactivate restaurants

### For Restaurant Owners

1. Register restaurant at `/get-started`
2. Select subscription plan
3. Wait for super admin approval
4. Access admin dashboard at `/{slug}/admin`
5. Manage menu, staff, and view analytics

### For Waiters

1. Access POS dashboard at `/{slug}/pos`
2. View table matrix
3. Take orders from customers
4. Mark orders as delivered

### For Kitchen Staff

1. Access kitchen dashboard at `/{slug}/kitchen`
2. View orders in Kanban board
3. Update order status (preparing → ready)
4. Track order preparation time

---

## API Endpoints

### Authentication
- `POST /login` - User login
- `POST /register` - Restaurant registration
- `POST /logout` - User logout
- `GET /join/{slug}` - Staff registration form
- `POST /join/{slug}` - Register staff member

### Menu Management
- `GET /{slug}/admin/categories` - List categories
- `POST /{slug}/admin/categories` - Create category
- `PUT /{slug}/admin/categories/{id}` - Update category
- `DELETE /{slug}/admin/categories/{id}` - Delete category
- `GET /{slug}/admin/menu-items` - List menu items
- `POST /{slug}/admin/menu-items` - Create menu item
- `PUT /{slug}/admin/menu-items/{id}` - Update menu item
- `DELETE /{slug}/admin/menu-items/{id}` - Delete menu item

### Orders
- `POST /{slug}/checkout` - Create order
- `GET /{slug}/orders` - Get restaurant orders
- `PATCH /{slug}/orders/{id}/preparing` - Mark as preparing
- `PATCH /{slug}/orders/{id}/ready` - Mark as ready
- `PATCH /{slug}/orders/{id}/completed` - Mark as completed

### Analytics
- `GET /{slug}/admin` - Admin dashboard with metrics

---

## Design Language

Kite uses the **Rhetorich Design System** characterized by:
- **Bold Typography** - Heavy font weights (font-black, font-bold)
- **Thick Borders** - 4px solid black borders (border-4 border-black)
- **Heavy Shadows** - Offset shadows for depth (shadow-[4px_4px_0px_0px_rgba(0,0,0,1)])
- **No Icons/Emojis** - Pure text-based interface
- **High Contrast** - Black and white with accent colors
- **Responsive Grid** - Bento grid layouts

---

## Multi-Tenant Architecture

### Slug-Based Routing
```
Path-based: http://localhost:8000/{restaurant-slug}/admin
Subdomain-based: http://{restaurant-slug}.kite.test/admin
```

### Data Isolation
- All queries automatically scoped to `auth()->user()->restaurant_id`
- Middleware ensures restaurant context is set
- Soft deletes for data recovery

### Security
- Restaurant ID verification on all operations
- Role-based middleware checks
- CSRF protection on all forms
- Input validation and sanitization

---

## Deployment

### Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure MySQL database
- [ ] Set up SSL certificate
- [ ] Configure email service
- [ ] Set up payment gateway
- [ ] Configure file storage (S3)
- [ ] Set up monitoring and logging
- [ ] Configure backup strategy

### Deployment Platforms
- **Heroku** - Easy deployment with buildpacks
- **DigitalOcean** - VPS with full control
- **AWS** - Scalable cloud infrastructure
- **Laravel Forge** - Optimized Laravel hosting

---

## Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Commit Message Format
```
<type>: <subject>

<body>

<footer>
```

Types: `feat`, `fix`, `docs`, `style`, `refactor`, `test`, `chore`

---

## Roadmap

### Q2 2026
- ✅ Core authentication and authorization
- ✅ Restaurant management
- ✅ Menu management
- ✅ Order management
- ✅ Kitchen display system
- ⏳ Payment gateway integration

### Q3 2026
- ⏳ Advanced analytics and reporting
- ⏳ Table management and reservations
- ⏳ Website customization interface
- ⏳ Mobile app (React Native)

### Q4 2026
- ⏳ Loyalty program
- ⏳ Customer reviews and ratings
- ⏳ Marketing tools
- ⏳ Inventory management

### 2027
- ⏳ AI-powered recommendations
- ⏳ Multi-location support
- ⏳ Franchise management
- ⏳ Advanced integrations

---

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## Support

For support, email support@kite.com or visit our documentation at [docs.kite.com](https://docs.kite.com)

---

## Acknowledgments

- Laravel community for the excellent framework
- Tailwind CSS for the utility-first CSS framework
- All contributors and testers

---

## Contact

- **Website:** https://kite.com
- **Email:** hello@kite.com
- **Twitter:** @KiteRestaurants
- **LinkedIn:** Kite Restaurant Management

---

**Made with ❤️ for restaurants worldwide**
