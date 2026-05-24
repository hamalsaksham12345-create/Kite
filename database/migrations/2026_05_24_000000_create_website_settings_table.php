<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            
            // Basic Info
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('banner_path')->nullable();
            
            // Colors
            $table->string('primary_color')->default('#10b981');
            $table->string('secondary_color')->default('#059669');
            $table->string('accent_color')->default('#f59e0b');
            $table->string('text_color')->default('#1f2937');
            $table->string('background_color')->default('#ffffff');
            
            // Typography
            $table->string('font_family')->default('Inter');
            $table->string('heading_font')->default('Poppins');
            
            // Contact Details
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            
            // Social Media
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('whatsapp_number')->nullable();
            
            // Business Hours
            $table->json('business_hours')->nullable();
            
            // Domain Settings
            $table->string('custom_domain')->nullable();
            $table->boolean('use_custom_domain')->default(false);
            $table->string('subdomain')->nullable();
            
            // Homepage Settings
            $table->text('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->text('about_section')->nullable();
            $table->text('features_section')->nullable();
            $table->boolean('show_menu_preview')->default(true);
            $table->boolean('show_testimonials')->default(true);
            $table->boolean('show_gallery')->default(true);
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            // Settings
            $table->boolean('is_published')->default(false);
            $table->boolean('maintenance_mode')->default(false);
            $table->text('maintenance_message')->nullable();
            
            $table->timestamps();
            $table->unique(['restaurant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
