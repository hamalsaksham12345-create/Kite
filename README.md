# 🪁 Kite - Multi-Tenant Restaurant SaaS Platform

A comprehensive multi-tenant restaurant management platform built with Laravel 13, featuring role-based access control, dynamic theming, and a complete POS system.

## 🚀 Features

### ✅ **Implemented**
- **Multi-Tenant Architecture**: Complete data isolation between restaurants
- **Role-Based Access Control**: Super Admin, Restaurant Admin, Waiter, and Chef roles
- **Dynamic Theming**: Restaurant-specific colors and branding
- **Super Admin Dashboard**: Complete restaurant management system
- **Responsive Design**: Mobile-first approach with Tailwind CSS
- **Custom Authentication**: Secure login system without external dependencies

### 🔄 **In Development**
- Restaurant Admin CMS (menu management, settings)
- Waiter POS System (order taking, table management)
- Chef Kitchen Display (order status management)
- Real-time order updates
- Payment integration

## 🏗️ Architecture

### Database Design
```
restaurants (1) ──→ (∞) users
restaurants (1) ──→ (∞) categories
restaurants (1) ──→ (∞) menu_items
restaurants (1) ──→ (∞) orders
orders (1) ──→ (∞) order_items
categories (1) ──→ (∞) menu_items
```

### User Roles
- **Super Admin**: Manage all restaurants and create new ones
- **Admin (Owner)**: Restaurant-specific management and settings
- **Waiter (POS)**: Take orders and manage tables
- **Chef (BOH)**: Kitchen display and order status management

### Multi-Tenancy
- Global scopes automatically filter data by `restaurant_id`
- Middleware ensures users only see their restaurant's data
- Dynamic routing supports both subdomain and path-based access

## 🛠️ Installation

### Prerequisites
- PHP 8.3+
- Composer
- Node.js & npm
- MySQL/SQLite

### Quick Start
```bash
# Clone the repository
git clone <repository-url> kite
cd kite

# Run the setup script
./start-dev.sh

# Start the development server
php artisan serve
```

### Manual Installation
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Create environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env file
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=kite
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Seed demo data
php artisan db:seed

# Build frontend assets
npm run build

# Start the server
php artisan serve
```

## 🔐 Demo Credentials

### Super Admin
- **Email**: `admin@kite.test`
- **Password**: `password`
- **Access**: Complete system management

### Restaurant Admin (Demo Pizza Palace)
- **Email**: `admin@demopizza.com`
- **Password**: `password`
- **Access**: Restaurant-specific management

## 📱 Usage

### Super Admin Dashboard
1. Login with super admin credentials
2. Navigate to `/super-admin/restaurants`
3. Create, edit, or manage restaurants
4. Assign master admin users to restaurants

### Restaurant Management
1. Login as restaurant admin
2. Access restaurant-specific dashboard
3. Manage menu categories and items (coming soon)
4. Configure restaurant settings and branding (coming soon)

### Dynamic Routing
The platform supports flexible routing:
- **Subdomain**: `{restaurant-slug}.kite.test`
- **Path-based**: `kite.test/{restaurant-slug}`

## 🎨 Theming System

Each restaurant can customize:
- **Primary Color**: Main brand color
- **Secondary Color**: Accent color
- **Logo**: Restaurant logo display
- **CSS Variables**: Dynamic color injection

Colors are automatically applied using CSS variables:
```css
:root {
    --primary-color: #E53E3E;
    --secondary-color: #2D3748;
}
```

## 🗂️ Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/                    # Authentication controllers
│   │   └── SuperAdmin/              # Super admin controllers
│   └── Middleware/
│       ├── RoleMiddleware.php       # Role-based access control
│       └── RestaurantContextMiddleware.php  # Restaurant context
├── Models/                          # Eloquent models
├── Scopes/
│   └── RestaurantScope.php         # Multi-tenancy global scope
└── ...

database/
├── migrations/                      # Database schema
└── seeders/                        # Demo data seeders

resources/
├── css/
│   └── app.css                     # Tailwind CSS
├── js/
│   └── app.js                      # Frontend JavaScript
└── views/
    ├── layouts/                    # Blade layouts
    ├── auth/                       # Authentication views
    ├── super-admin/                # Super admin interface
    └── ...
```

## 🔧 Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
# Format code
./vendor/bin/pint

# Check code style
./vendor/bin/pint --test
```

### Frontend Development
```bash
# Watch for changes (development)
npm run dev

# Build for production
npm run build
```

### Database Management
```bash
# Fresh migration with seeding
php artisan migrate:fresh --seed

# Create new migration
php artisan make:migration create_table_name

# Create new seeder
php artisan make:seeder TableSeeder
```

## 🚀 Deployment

### Production Setup
1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` in `.env`
3. Configure production database
4. Run `composer install --optimize-autoloader --no-dev`
5. Run `npm run build`
6. Run `php artisan config:cache`
7. Run `php artisan route:cache`
8. Run `php artisan view:cache`

### Environment Variables
```env
APP_NAME=Kite
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kite_production
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

If you encounter any issues:

1. Check the [troubleshooting guide](TROUBLESHOOTING.md)
2. Search existing [issues](../../issues)
3. Create a new issue with detailed information

## 🗺️ Roadmap

### Phase 1: Foundation ✅
- [x] Multi-tenant architecture
- [x] User authentication and roles
- [x] Super admin dashboard
- [x] Dynamic theming system

### Phase 2: Restaurant Management 🔄
- [ ] Restaurant admin CMS
- [ ] Menu management system
- [ ] Staff user management
- [ ] Restaurant settings

### Phase 3: POS System 📋
- [ ] Waiter interface
- [ ] Table management
- [ ] Order creation and management
- [ ] Mobile-responsive design

### Phase 4: Kitchen Operations 👨‍🍳
- [ ] Kitchen display system
- [ ] Order status management
- [ ] Real-time updates
- [ ] Kitchen-optimized interface

### Phase 5: Advanced Features 🚀
- [ ] Payment integration
- [ ] Reporting and analytics
- [ ] API endpoints
- [ ] Mobile applications
- [ ] Multi-language support

---

**Built with ❤️ using Laravel 13, Tailwind CSS, and modern web technologies.**