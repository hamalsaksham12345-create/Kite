# Website Builder - Quick Setup Guide

## Installation

The Website Builder is already fully integrated into Kite. No additional installation is required.

## Database Setup

The website builder uses two new tables:

### 1. `website_settings`
Stores all website customization settings for each restaurant.

### 2. `website_sections`
Stores flexible content sections for future enhancements.

**Run migrations:**
```bash
php artisan migrate
```

**Seed initial data:**
```bash
php artisan db:seed --class=WebsiteSettingsSeeder
```

## Accessing the Website Builder

### For Restaurant Admins

1. **Login** to your restaurant admin account
2. **Go to Admin Dashboard**
3. **Click "Website Builder"** button in Quick Links section
4. **Choose a customization section:**
   - Design (colors, fonts)
   - Content (hero, about, features, SEO)
   - Media (logo, favicon, banner)
   - Contact (phone, email, social media)
   - Domain (subdomain or custom domain)

### URL Structure

**Admin Dashboard:**
```
/{restaurant-slug}/admin
```

**Website Builder:**
```
/{restaurant-slug}/admin/website-builder
```

**Individual Sections:**
```
/{restaurant-slug}/admin/website-builder/design
/{restaurant-slug}/admin/website-builder/content
/{restaurant-slug}/admin/website-builder/media
/{restaurant-slug}/admin/website-builder/contact
/{restaurant-slug}/admin/website-builder/domain
```

## Public Website Access

### Subdomain Routing
```
http://{restaurant-slug}.kite.test
http://{restaurant-slug}.kite.test/menu
http://{restaurant-slug}.kite.test/contact
```

### Path-based Routing
```
http://kite.test/{restaurant-slug}/website
http://kite.test/{restaurant-slug}/website/menu
http://kite.test/{restaurant-slug}/website/contact
```

## Configuration

### Environment Variables

No additional environment variables are required. The website builder uses existing Kite configuration.

### Storage Configuration

Images are stored in:
```
storage/app/public/restaurants/{restaurant-id}/
```

Make sure the storage disk is properly configured:
```bash
php artisan storage:link
```

## Features Overview

### 1. Design Customization
- **Colors**: 5 customizable colors (primary, secondary, accent, text, background)
- **Fonts**: 7 professional font options for body and headings
- **Live Preview**: See changes in real-time

### 2. Content Management
- **Hero Section**: Title and subtitle for homepage
- **About Section**: Restaurant story and description
- **Features Section**: Key highlights
- **SEO Settings**: Meta title, description, keywords
- **Display Options**: Toggle menu preview, testimonials, gallery

### 3. Media Management
- **Logo**: Max 2MB (PNG, JPG, GIF, SVG)
- **Favicon**: Max 1MB (PNG, JPG, GIF, ICO)
- **Banner**: Max 5MB (PNG, JPG, GIF)

### 4. Contact Details
- Phone, email, address
- City, state, postal code, country
- WhatsApp number
- Social media links (Facebook, Instagram, Twitter, LinkedIn, YouTube)

### 5. Domain Management
- **Subdomain**: Free subdomain (e.g., `restaurant.kite.test`)
- **Custom Domain**: Use your own domain with DNS configuration

### 6. Publishing
- **Draft Mode**: Test before publishing
- **Publish**: Make website live
- **Unpublish**: Take website offline
- **Maintenance Mode**: Temporary downtime with custom message

## Step-by-Step Usage

### Step 1: Customize Design
1. Go to Website Builder → Design
2. Choose colors for your brand
3. Select fonts for body and headings
4. See live preview
5. Click "Save Design Settings"

### Step 2: Upload Media
1. Go to Website Builder → Media
2. Upload logo (recommended 200x200px)
3. Upload favicon (recommended 32x32px)
4. Upload banner (recommended 1200x400px)
5. Images are automatically optimized

### Step 3: Add Content
1. Go to Website Builder → Content
2. Write hero title and subtitle
3. Add about section
4. Add features section
5. Configure SEO settings
6. Toggle display options
7. Click "Save Content"

### Step 4: Set Contact Details
1. Go to Website Builder → Contact
2. Add phone, email, address
3. Add social media links
4. Add WhatsApp number
5. Click "Save Contact Details"

### Step 5: Configure Domain
1. Go to Website Builder → Domain
2. Choose between subdomain or custom domain
3. If custom domain:
   - Enter your domain name
   - Follow DNS configuration instructions
   - Wait 24-48 hours for propagation
4. Click "Save Domain Settings"

### Step 6: Publish Website
1. Go to Website Builder → Dashboard
2. Review all settings
3. Click "Publish Website"
4. Website is now live!

## Testing

### Test Checklist
- [ ] Design customization works
- [ ] Colors update in preview
- [ ] Fonts change correctly
- [ ] Logo uploads successfully
- [ ] Favicon uploads successfully
- [ ] Banner uploads successfully
- [ ] Contact details save correctly
- [ ] Social media links validate
- [ ] Domain settings save correctly
- [ ] Website publishes successfully
- [ ] Public website displays correctly
- [ ] Menu displays on public website
- [ ] Contact form works
- [ ] Responsive design works on mobile

### Test URLs

**Admin:**
```
http://localhost/marios-pizza/admin/website-builder
```

**Public Website (Subdomain):**
```
http://marios-pizza.kite.test
http://marios-pizza.kite.test/menu
http://marios-pizza.kite.test/contact
```

**Public Website (Path-based):**
```
http://localhost/marios-pizza/website
http://localhost/marios-pizza/website/menu
http://localhost/marios-pizza/website/contact
```

## Troubleshooting

### Website Not Displaying
**Problem**: Website shows "Coming Soon" page
**Solution**: 
- Check if website is published in Website Builder
- Verify website is not in maintenance mode
- Check domain/subdomain configuration

### Images Not Showing
**Problem**: Logo, favicon, or banner not displaying
**Solution**:
- Verify storage link is created: `php artisan storage:link`
- Check file permissions: `chmod -R 755 storage/app/public`
- Verify file was uploaded successfully
- Check file size limits

### Domain Not Working
**Problem**: Custom domain not accessible
**Solution**:
- Verify DNS records are configured correctly
- Wait 24-48 hours for DNS propagation
- Check domain is not already in use
- Verify domain format is correct

### Styling Issues
**Problem**: Colors or fonts not applying
**Solution**:
- Clear browser cache
- Hard refresh (Ctrl+Shift+R or Cmd+Shift+R)
- Verify CSS variables are set correctly
- Check color format is valid hex

## Performance Tips

1. **Optimize Images**
   - Use appropriate image sizes
   - Compress images before uploading
   - Use modern formats (WebP when possible)

2. **Caching**
   - Website settings are cached
   - Clear cache after major changes: `php artisan cache:clear`

3. **Database**
   - Website settings are indexed
   - Queries are optimized with eager loading

## Security

### File Upload Security
- Only image files allowed
- File size limits enforced
- Files stored in restaurant-specific directories
- Old files automatically deleted

### Authorization
- Only restaurant admins can access website builder
- Restaurant isolation enforced
- CSRF protection on all forms

### Data Validation
- Color format validation
- URL validation for social media
- Email validation
- Phone number validation
- Domain name validation

## API Integration

### Get Website Settings
```php
$restaurant = Restaurant::find($id);
$setting = $restaurant->websiteSetting;

// Get domain
$domain = $setting->getDomain();

// Get URL
$url = $setting->getUrl();

// Check if live
$isLive = $setting->isLive();

// Get CSS variables
$css = $setting->getCSSVariables();
```

### Update Website Settings
```php
$setting->update([
    'primary_color' => '#10b981',
    'hero_title' => 'Welcome',
    'is_published' => true,
]);
```

## Next Steps

1. **Test the Website Builder** with a sample restaurant
2. **Customize the design** with your brand colors
3. **Upload media** (logo, favicon, banner)
4. **Add content** (hero, about, features)
5. **Configure domain** (subdomain or custom)
6. **Publish the website** and share with customers

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Review WEBSITE_BUILDER.md for detailed documentation
3. Check application logs: `storage/logs/laravel.log`
4. Run diagnostics: `php artisan tinker`

## Additional Resources

- **Full Documentation**: See `WEBSITE_BUILDER.md`
- **Architecture**: See `ARCHITECTURE.md`
- **Database Schema**: See `MODULES.md`
- **README**: See `README.md`

---

**Website Builder is now ready to use!** 🚀
