#!/bin/bash

echo "🚀 Starting Kite Restaurant SaaS Development Environment"
echo "=================================================="

# Check if .env exists
if [ ! -f .env ]; then
    echo "📋 Creating .env file from .env.example..."
    cp .env.example .env
fi

# Generate app key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate
fi

# Run migrations if needed
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Seed database if empty
USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();")
if [ "$USER_COUNT" -eq "0" ]; then
    echo "🌱 Seeding database with demo data..."
    php artisan db:seed
fi

# Build assets if manifest doesn't exist
if [ ! -f public/build/manifest.json ]; then
    echo "🎨 Building frontend assets..."
    npm install
    npm run build
fi

echo ""
echo "✅ Setup complete! You can now:"
echo "   1. Start the server: php artisan serve"
echo "   2. Login as Super Admin: admin@kite.test / password"
echo "   3. Login as Restaurant Admin: admin@demopizza.com / password"
echo ""
echo "🌐 Application will be available at: http://localhost:8000"
echo "📚 Super Admin Panel: http://localhost:8000/super-admin/restaurants"