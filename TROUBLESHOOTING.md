# 🔧 Troubleshooting Guide

## Common Issues and Solutions

### 1. Vite Manifest Not Found Error

**Error**: `Vite manifest not found at: /path/to/public/build/manifest.json`

**Solution**:
```bash
# Install npm dependencies
npm install

# Build the assets
npm run build
```

**For Development**:
```bash
# Run Vite in development mode
npm run dev
```

### 2. Database Connection Issues

**Error**: `SQLSTATE[HY000] [1049] Unknown database 'kite'`

**Solution**:
1. Create the database:
   ```sql
   CREATE DATABASE kite;
   ```

2. Update your `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kite
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

3. Run migrations:
   ```bash
   php artisan migrate
   ```

### 3. Permission Denied Errors

**Error**: `Permission denied` when accessing files

**Solution**:
```bash
# Set proper permissions for storage and cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# If using Apache/Nginx, ensure web server owns the files
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache
```

### 4. Class Not Found Errors

**Error**: `Class 'App\Models\Restaurant' not found`

**Solution**:
```bash
# Clear and regenerate autoload files
composer dump-autoload

# Clear application cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 5. Migration Issues

**Error**: `Base table or view not found`

**Solution**:
```bash
# Check migration status
php artisan migrate:status

# Run migrations
php artisan migrate

# If tables exist but migrations show as not run
php artisan migrate:refresh --seed
```

### 6. Seeder Issues

**Error**: `Class 'SuperAdminSeeder' not found`

**Solution**:
```bash
# Regenerate autoload files
composer dump-autoload

# Run specific seeder
php artisan db:seed --class=SuperAdminSeeder

# Run all seeders
php artisan db:seed
```

### 7. Route Not Found (404 Errors)

**Error**: `404 Not Found` for valid routes

**Solution**:
```bash
# Clear route cache
php artisan route:clear

# Check routes
php artisan route:list

# Ensure .htaccess exists in public folder (for Apache)
```

### 8. Middleware Issues

**Error**: `Access denied. Insufficient privileges.`

**Solution**:
1. Check user role in database:
   ```bash
   php artisan tinker
   >>> App\Models\User::find(1)->role
   ```

2. Ensure middleware is registered in `bootstrap/app.php`

3. Clear application cache:
   ```bash
   php artisan cache:clear
   ```

### 9. CSS/JS Not Loading

**Error**: Styles or scripts not loading properly

**Solution**:
```bash
# Rebuild assets
npm run build

# For development with hot reload
npm run dev

# Check if files exist
ls -la public/build/
```

### 10. Session Issues

**Error**: `Session store not set on request`

**Solution**:
1. Check session configuration in `.env`:
   ```env
   SESSION_DRIVER=file
   SESSION_LIFETIME=120
   ```

2. Clear sessions:
   ```bash
   php artisan session:table  # if using database sessions
   php artisan migrate
   ```

3. Set proper permissions:
   ```bash
   chmod -R 775 storage/framework/sessions
   ```

## Environment-Specific Issues

### Development Environment

1. **Port Already in Use**:
   ```bash
   # Use different port
   php artisan serve --port=8001
   ```

2. **Hot Reload Not Working**:
   ```bash
   # Ensure Vite is running
   npm run dev
   
   # Check vite.config.js configuration
   ```

### Production Environment

1. **Optimization Commands**:
   ```bash
   # Cache configuration
   php artisan config:cache
   
   # Cache routes
   php artisan route:cache
   
   # Cache views
   php artisan view:cache
   
   # Optimize autoloader
   composer install --optimize-autoloader --no-dev
   ```

2. **File Permissions**:
   ```bash
   # Set proper ownership
   sudo chown -R www-data:www-data /path/to/kite
   
   # Set proper permissions
   find /path/to/kite -type f -exec chmod 644 {} \;
   find /path/to/kite -type d -exec chmod 755 {} \;
   chmod -R 775 storage bootstrap/cache
   ```

## Debugging Tips

### 1. Enable Debug Mode
```env
APP_DEBUG=true
APP_LOG_LEVEL=debug
```

### 2. Check Logs
```bash
# View latest logs
tail -f storage/logs/laravel.log

# View specific log file
cat storage/logs/laravel-2024-01-01.log
```

### 3. Database Debugging
```bash
# Check database connection
php artisan tinker
>>> DB::connection()->getPdo()

# Run raw queries
>>> DB::select('SELECT * FROM users LIMIT 1')
```

### 4. Route Debugging
```bash
# List all routes
php artisan route:list

# List routes for specific URI
php artisan route:list --path=super-admin

# List routes for specific method
php artisan route:list --method=GET
```

### 5. Model Debugging
```bash
php artisan tinker
>>> App\Models\Restaurant::count()
>>> App\Models\User::where('role', 'super_admin')->first()
```

## Performance Issues

### 1. Slow Database Queries
```bash
# Enable query logging in AppServiceProvider
DB::listen(function ($query) {
    Log::info($query->sql, $query->bindings);
});
```

### 2. Memory Issues
```env
# Increase memory limit in .env
MEMORY_LIMIT=512M
```

### 3. Optimize Autoloader
```bash
composer dump-autoload --optimize
```

## Getting Help

If you're still experiencing issues:

1. **Check Laravel Documentation**: [https://laravel.com/docs](https://laravel.com/docs)
2. **Search Stack Overflow**: Include error message and "Laravel 13"
3. **Check GitHub Issues**: Look for similar problems in the repository
4. **Create New Issue**: Provide detailed error information, steps to reproduce, and environment details

### When Creating an Issue, Include:
- Laravel version (`php artisan --version`)
- PHP version (`php --version`)
- Operating system
- Complete error message
- Steps to reproduce
- Relevant code snippets
- Database configuration (without credentials)

## Useful Commands Reference

```bash
# Application
php artisan serve                    # Start development server
php artisan --version               # Check Laravel version
php artisan list                    # List all commands

# Database
php artisan migrate                 # Run migrations
php artisan migrate:fresh --seed    # Fresh migration with seeding
php artisan db:seed                 # Run seeders
php artisan tinker                  # Interactive shell

# Cache
php artisan cache:clear             # Clear application cache
php artisan config:clear            # Clear config cache
php artisan route:clear             # Clear route cache
php artisan view:clear              # Clear view cache

# Assets
npm install                         # Install dependencies
npm run dev                         # Development build with watch
npm run build                       # Production build

# Debugging
php artisan route:list              # List all routes
php artisan config:show             # Show configuration
tail -f storage/logs/laravel.log    # Watch logs
```