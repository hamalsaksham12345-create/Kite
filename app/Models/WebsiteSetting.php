<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WebsiteSetting extends Model
{
    protected $fillable = [
        'restaurant_id',
        'logo_path',
        'favicon_path',
        'banner_path',
        'primary_color',
        'secondary_color',
        'accent_color',
        'text_color',
        'background_color',
        'font_family',
        'heading_font',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'linkedin_url',
        'youtube_url',
        'whatsapp_number',
        'business_hours',
        'custom_domain',
        'use_custom_domain',
        'subdomain',
        'hero_title',
        'hero_subtitle',
        'about_section',
        'features_section',
        'show_menu_preview',
        'show_testimonials',
        'show_gallery',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_published',
        'maintenance_mode',
        'maintenance_message',
    ];

    protected $casts = [
        'business_hours' => 'array',
        'show_menu_preview' => 'boolean',
        'show_testimonials' => 'boolean',
        'show_gallery' => 'boolean',
        'use_custom_domain' => 'boolean',
        'is_published' => 'boolean',
        'maintenance_mode' => 'boolean',
    ];

    /**
     * Get the restaurant that owns this website setting
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Get the website sections
     */
    public function sections(): HasMany
    {
        return $this->hasMany(WebsiteSection::class, 'restaurant_id', 'restaurant_id');
    }

    /**
     * Get the domain for this website
     */
    public function getDomain(): string
    {
        if ($this->use_custom_domain && $this->custom_domain) {
            return $this->custom_domain;
        }

        return $this->subdomain . '.kite.test';
    }

    /**
     * Get the full URL for this website
     */
    public function getUrl(): string
    {
        return 'http://' . $this->getDomain();
    }

    /**
     * Check if website is live
     */
    public function isLive(): bool
    {
        return $this->is_published && !$this->maintenance_mode;
    }

    /**
     * Get business hours for a specific day
     */
    public function getBusinessHours(string $day): ?array
    {
        if (!$this->business_hours) {
            return null;
        }

        return $this->business_hours[$day] ?? null;
    }

    /**
     * Set business hours
     */
    public function setBusinessHours(array $hours): void
    {
        $this->business_hours = $hours;
        $this->save();
    }

    /**
     * Get CSS variables for branding
     */
    public function getCSSVariables(): string
    {
        return ":root {
            --color-primary: {$this->primary_color};
            --color-secondary: {$this->secondary_color};
            --color-accent: {$this->accent_color};
            --color-text: {$this->text_color};
            --color-background: {$this->background_color};
            --font-family: '{$this->font_family}', sans-serif;
            --font-heading: '{$this->heading_font}', sans-serif;
        }";
    }
}
