# Website Builder - Kite Restaurant Management

## Overview

The Website Builder is Kite's core selling feature that empowers restaurant owners to create and customize their public-facing website without any coding knowledge. Each restaurant gets a fully customizable website with professional design, branding, and functionality.

## Key Features

### 1. **No-Code Customization**
- Restaurant owners can customize their website through an intuitive admin interface
- No technical knowledge required
- Real-time preview of changes
- Drag-and-drop style interface with Rhetorich design

### 2. **Design Customization**
- **Colors**: Primary, secondary, accent, text, and background colors
- **Typography**: Choose from 7 professional fonts for body and headings
- **Branding**: Upload logo, favicon, and banner images
- **Live Preview**: See changes in real-time as you customize

### 3. **Content Management**
- **Hero Section**: Customizable title and subtitle
- **About Section**: Tell your restaurant's story
- **Features Section**: Highlight what makes you special
- **SEO Settings**: Meta title, description, and keywords
- **Display Options**: Toggle menu preview, testimonials, and gallery

### 4. **Contact Details**
- Phone, email, and address
- City, state, postal code, country
- WhatsApp number for direct messaging
- Social media links (Facebook, Instagram, Twitter, LinkedIn, YouTube)

### 5. **Domain Management**
- **Subdomain**: Free subdomain (e.g., `restaurant-name.kite.test`)
- **Custom Domain**: Use your own domain with DNS configuration
- Easy switching between subdomain and custom domain

### 6. **Media Management**
- Upload and manage logo (max 2MB)
- Upload and manage favicon (max 1MB)
- Upload and manage banner (max 5MB)
- Automatic image optimization

### 7. **Website Publishing**
- Draft mode for testing
- One-click publish to make website live
- Maintenance mode for temporary downtime
- Custom maintenance message

## Architecture

### Database Schema

#### `website_settings` Table
```sql
- id (primary key)
- restaurant_id (foreign key)
- logo_path, favicon_path, banner_path
- primary_color, secondary_color, accent_color, text_color, background_color
- font_family, heading_font
- phone, email, address, city, state, postal_code, country
- facebook_url, instagram_url, twitter_url, linkedin_url, youtube_url, whatsapp_number
- business_hours (JSON)
- custom_domain, use_custom_domain, subdomain
- hero_title, hero_subtitle, about_section, features_section
- show_menu_preview, show_testimonials, show_gallery
- meta_title, meta_description, meta_keywords
- is_published, maintenance_mode, maintenance_message
- timestamps
```

#### `website_sections` Table
```sql
- id (primary key)
- restaurant_id (foreign key)
- type (hero, about, menu_preview, testimonials, gallery, cta, contact)
- title, content, image_path
- data (JSON for flexible storage)
- sort_order, is_visible
- background_color, text_color
- timestamps
```

### Models

#### WebsiteSetting
```php
// Relationships
- belongsTo(Restaurant)
- hasMany(WebsiteSection) via restaurant_id

// Key Methods
- getDomain(): Get the website domain (custom or subdomain)
- getUrl(): Get the full website URL
- isLive(): Check if website is published and not in maintenance
- getCSSVariables(): Get CSS variables for branding
- getBusinessHours(day): Get business hours for a specific day
```

#### WebsiteSection
```php
// Relationships
- belongsTo(Restaurant)

// Scopes
- visible(): Get only visible sections
- ordered(): Order by sort_order
- byType(type): Filter by section type
```

### Controllers

#### WebsiteBuilderController
Handles all admin website builder operations:
- `index()`: Dashboard overview
- `design()`: Design editor view
- `updateDesign()`: Save design settings
- `content()`: Content editor view
- `updateContent()`: Save content
- `contact()`: Contact details view
- `updateContact()`: Save contact details
- `media()`: Media manager view
- `uploadLogo()`: Upload logo image
- `uploadFavicon()`: Upload favicon
- `uploadBanner()`: Upload banner
- `domain()`: Domain settings view
- `updateDomain()`: Save domain settings
- `publish()`: Publish website
- `unpublish()`: Unpublish website
- `preview()`: Preview website

#### PublicWebsiteController
Renders the public website:
- `index()`: Homepage with customized branding
- `menu()`: Full menu page
- `contact()`: Contact page
- `about()`: About page
- `submitContact()`: Handle contact form submissions

## Routes

### Admin Routes (Path-based)
```
GET  /admin/website-builder                    → index
GET  /admin/website-builder/design             → design
PATCH /admin/website-builder/design            → updateDesign
GET  /admin/website-builder/content            → content
PATCH /admin/website-builder/content           → updateContent
GET  /admin/website-builder/contact            → contact
PATCH /admin/website-builder/contact           → updateContact
GET  /admin/website-builder/media              → media
POST /admin/website-builder/upload-logo        → uploadLogo
POST /admin/website-builder/upload-favicon     → uploadFavicon
POST /admin/website-builder/upload-banner      → uploadBanner
GET  /admin/website-builder/domain             → domain
PATCH /admin/website-builder/domain            → updateDomain
POST /admin/website-builder/publish            → publish
POST /admin/website-builder/unpublish          → unpublish
GET  /admin/website-builder/preview            → preview
```

### Public Website Routes

#### Subdomain Routing
```
GET  /                    → index (homepage)
GET  /menu                → menu
GET  /about               → about
GET  /contact             → contact
POST /contact             → submitContact
```

#### Path-based Routing
```
GET  /website             → index (homepage)
GET  /website/menu        → menu
GET  /website/about       → about
GET  /website/contact     → contact
POST /website/contact     → submitContact
```

## Views

### Admin Views
- `admin/website-builder/index.blade.php` - Dashboard with 6 customization sections
- `admin/website-builder/design.blade.php` - Color and font customization
- `admin/website-builder/content.blade.php` - Hero, about, features, SEO
- `admin/website-builder/media.blade.php` - Logo, favicon, banner uploads
- `admin/website-builder/contact.blade.php` - Contact details and social media
- `admin/website-builder/domain.blade.php` - Subdomain and custom domain settings

### Public Website Views
- `website/index.blade.php` - Homepage with hero, about, menu preview, CTA, contact
- `website/menu.blade.php` - Full menu with categories and items
- `website/contact.blade.php` - Contact form and information
- `website/maintenance.blade.php` - Maintenance mode page

## Branding System

### CSS Variables
The website uses CSS variables for dynamic branding:
```css
:root {
    --color-primary: #10b981;
    --color-secondary: #059669;
    --color-accent: #f59e0b;
    --color-text: #1f2937;
    --color-background: #ffffff;
    --font-family: 'Inter', sans-serif;
    --font-heading: 'Poppins', sans-serif;
}
```

### Font Options
- Inter
- Poppins
- Roboto
- Playfair Display
- Montserrat
- Lato
- Open Sans

## Usage Flow

### For Restaurant Owners

1. **Access Website Builder**
   - Click "Website Builder" button on admin dashboard
   - Navigate to website builder section

2. **Customize Design**
   - Choose colors for primary, secondary, accent, text, background
   - Select fonts for body and headings
   - See live preview of changes
   - Save design settings

3. **Upload Media**
   - Upload logo (recommended 200x200px)
   - Upload favicon (recommended 32x32px)
   - Upload banner (recommended 1200x400px)

4. **Add Content**
   - Write hero title and subtitle
   - Add about section
   - Add features section
   - Configure SEO settings
   - Toggle display options

5. **Set Contact Details**
   - Add phone, email, address
   - Add social media links
   - Add WhatsApp number

6. **Configure Domain**
   - Choose between subdomain or custom domain
   - If custom domain, follow DNS configuration instructions

7. **Publish Website**
   - Review all settings
   - Click "Publish Website"
   - Website is now live and accessible

### For Customers

1. **Visit Website**
   - Access via subdomain (e.g., `restaurant.kite.test`)
   - Or via custom domain if configured

2. **Browse Content**
   - View homepage with restaurant info
   - Browse full menu with categories
   - View contact information
   - Access social media links

3. **Place Order**
   - Click "Order Now" or "Place Your Order"
   - Redirected to ordering system
   - Complete checkout

4. **Contact Restaurant**
   - Fill contact form
   - Call phone number
   - Message on WhatsApp
   - Visit social media

## Security

### Authorization
- Only restaurant admins can access website builder
- Restaurant isolation enforced via middleware
- CSRF protection on all forms

### File Uploads
- File type validation (images only)
- File size limits enforced
- Files stored in restaurant-specific directories
- Old files automatically deleted on replacement

### Data Validation
- Color format validation (hex colors)
- URL validation for social media links
- Email validation
- Phone number format validation
- Domain name validation

## Performance

### Optimization
- CSS variables for efficient branding
- Lazy loading for images
- Caching of website settings
- Efficient database queries with eager loading

### Scalability
- Supports unlimited restaurants
- Each restaurant has isolated website settings
- Subdomain routing supports unlimited subdomains
- Custom domain support for enterprise customers

## Future Enhancements

1. **Advanced Features**
   - Testimonials management
   - Gallery management
   - Blog/news section
   - Events calendar
   - Reservation system

2. **Design Enhancements**
   - Pre-built website templates
   - Drag-and-drop page builder
   - Custom CSS support
   - Theme marketplace

3. **Marketing Features**
   - Email newsletter integration
   - Social media integration
   - Analytics and tracking
   - SEO optimization tools
   - Google Business Profile sync

4. **E-commerce**
   - Online ordering integration
   - Payment gateway integration
   - Delivery tracking
   - Customer reviews and ratings

## Testing

### Manual Testing Checklist
- [ ] Design customization saves correctly
- [ ] Colors update in real-time preview
- [ ] Fonts change correctly
- [ ] Logo upload works (max 2MB)
- [ ] Favicon upload works (max 1MB)
- [ ] Banner upload works (max 5MB)
- [ ] Contact details save correctly
- [ ] Social media links validate
- [ ] Domain settings save correctly
- [ ] Website publishes successfully
- [ ] Website unpublishes successfully
- [ ] Maintenance mode works
- [ ] Public website displays correctly
- [ ] Menu displays on public website
- [ ] Contact form works
- [ ] Subdomain routing works
- [ ] Custom domain routing works
- [ ] CSS variables apply correctly
- [ ] Responsive design works on mobile
- [ ] Images load correctly

## Troubleshooting

### Website Not Displaying
- Check if website is published (`is_published = true`)
- Check if not in maintenance mode
- Verify domain/subdomain configuration
- Check file permissions for uploaded images

### Images Not Showing
- Verify file was uploaded successfully
- Check file size limits
- Verify file format is supported
- Check storage disk configuration

### Domain Not Working
- For custom domain: verify DNS records are configured
- For subdomain: verify subdomain is set correctly
- Check domain is not already in use
- Wait 24-48 hours for DNS propagation

### Styling Issues
- Clear browser cache
- Verify CSS variables are set
- Check font files are loading
- Verify color format is valid hex

## API Endpoints

### Design
```
PATCH /admin/website-builder/design
Content-Type: application/json

{
    "primary_color": "#10b981",
    "secondary_color": "#059669",
    "accent_color": "#f59e0b",
    "text_color": "#1f2937",
    "background_color": "#ffffff",
    "font_family": "Inter",
    "heading_font": "Poppins"
}
```

### Content
```
PATCH /admin/website-builder/content
Content-Type: application/json

{
    "hero_title": "Welcome",
    "hero_subtitle": "Discover amazing food",
    "about_section": "About us...",
    "features_section": "Features...",
    "show_menu_preview": true,
    "show_testimonials": true,
    "show_gallery": true,
    "meta_title": "Restaurant Name",
    "meta_description": "Description",
    "meta_keywords": "keywords"
}
```

### Contact
```
PATCH /admin/website-builder/contact
Content-Type: application/json

{
    "phone": "+977-1-1234567",
    "email": "info@restaurant.com",
    "address": "123 Main St",
    "city": "Kathmandu",
    "state": "Bagmati",
    "postal_code": "44600",
    "country": "Nepal",
    "facebook_url": "https://facebook.com/...",
    "instagram_url": "https://instagram.com/...",
    "twitter_url": "https://twitter.com/...",
    "linkedin_url": "https://linkedin.com/...",
    "youtube_url": "https://youtube.com/...",
    "whatsapp_number": "+977-9841234567"
}
```

### Domain
```
PATCH /admin/website-builder/domain
Content-Type: application/json

{
    "use_custom_domain": false,
    "custom_domain": "restaurant.com",
    "subdomain": "restaurant"
}
```

### Publish
```
POST /admin/website-builder/publish
Content-Type: application/json
```

### Unpublish
```
POST /admin/website-builder/unpublish
Content-Type: application/json
```

## Conclusion

The Website Builder is a powerful, user-friendly tool that enables restaurant owners to create professional websites without any technical knowledge. With comprehensive customization options, media management, and domain flexibility, it's the perfect solution for restaurants looking to establish their online presence.
