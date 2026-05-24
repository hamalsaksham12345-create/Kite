<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

class WebsiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create website settings for all restaurants that don't have them
        $restaurants = Restaurant::all();

        foreach ($restaurants as $restaurant) {
            WebsiteSetting::firstOrCreate(
                ['restaurant_id' => $restaurant->id],
                [
                    'subdomain' => $restaurant->slug,
                    'primary_color' => $restaurant->primary_color ?? '#10b981',
                    'secondary_color' => $restaurant->secondary_color ?? '#059669',
                    'accent_color' => '#f59e0b',
                    'text_color' => '#1f2937',
                    'background_color' => '#ffffff',
                    'font_family' => 'Inter',
                    'heading_font' => 'Poppins',
                    'hero_title' => 'Welcome to ' . $restaurant->name,
                    'hero_subtitle' => 'Discover amazing food and great service',
                    'about_section' => 'We are a restaurant dedicated to providing the best dining experience with quality food and excellent service.',
                    'features_section' => 'Fresh ingredients, Expert chefs, Friendly service, Comfortable ambiance',
                    'show_menu_preview' => true,
                    'show_testimonials' => true,
                    'show_gallery' => true,
                    'is_published' => false,
                ]
            );
        }
    }
}
