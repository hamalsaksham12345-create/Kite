<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\WebsiteSetting;
use App\Models\WebsiteSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class WebsiteBuilderController extends Controller
{
    /**
     * Show the website builder dashboard
     */
    public function index(Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $websiteSetting = $restaurant->websiteSetting ?? WebsiteSetting::create([
            'restaurant_id' => $restaurant->id,
            'subdomain' => $restaurant->slug,
        ]);

        $sections = $restaurant->websiteSections()
            ->visible()
            ->ordered()
            ->get();

        return view('admin.website-builder.index', compact('restaurant', 'websiteSetting', 'sections'));
    }

    /**
     * Show the design editor
     */
    public function design(Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $websiteSetting = $restaurant->websiteSetting ?? WebsiteSetting::create([
            'restaurant_id' => $restaurant->id,
            'subdomain' => $restaurant->slug,
        ]);

        return view('admin.website-builder.design', compact('restaurant', 'websiteSetting'));
    }

    /**
     * Update website design settings
     */
    public function updateDesign(Request $request, Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $validated = $request->validate([
            'primary_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'secondary_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'accent_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'text_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'background_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'font_family' => 'nullable|string|in:Inter,Poppins,Roboto,Playfair Display,Montserrat,Lato,Open Sans',
            'heading_font' => 'nullable|string|in:Inter,Poppins,Roboto,Playfair Display,Montserrat,Lato,Open Sans',
        ]);

        $websiteSetting = $restaurant->websiteSetting ?? WebsiteSetting::create([
            'restaurant_id' => $restaurant->id,
            'subdomain' => $restaurant->slug,
        ]);

        $websiteSetting->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Design settings updated successfully',
            'data' => $websiteSetting,
        ]);
    }

    /**
     * Show the content editor
     */
    public function content(Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $websiteSetting = $restaurant->websiteSetting ?? WebsiteSetting::create([
            'restaurant_id' => $restaurant->id,
            'subdomain' => $restaurant->slug,
        ]);

        $sections = $restaurant->websiteSections()
            ->ordered()
            ->get();

        return view('admin.website-builder.content', compact('restaurant', 'websiteSetting', 'sections'));
    }

    /**
     * Update website content
     */
    public function updateContent(Request $request, Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $validated = $request->validate([
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:500',
            'about_section' => 'nullable|string|max:2000',
            'features_section' => 'nullable|string|max:2000',
            'show_menu_preview' => 'boolean',
            'show_testimonials' => 'boolean',
            'show_gallery' => 'boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        $websiteSetting = $restaurant->websiteSetting ?? WebsiteSetting::create([
            'restaurant_id' => $restaurant->id,
            'subdomain' => $restaurant->slug,
        ]);

        $websiteSetting->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Content updated successfully',
            'data' => $websiteSetting,
        ]);
    }

    /**
     * Show the contact details editor
     */
    public function contact(Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $websiteSetting = $restaurant->websiteSetting ?? WebsiteSetting::create([
            'restaurant_id' => $restaurant->id,
            'subdomain' => $restaurant->slug,
        ]);

        return view('admin.website-builder.contact', compact('restaurant', 'websiteSetting'));
    }

    /**
     * Update contact details
     */
    public function updateContact(Request $request, Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
        ]);

        $websiteSetting = $restaurant->websiteSetting ?? WebsiteSetting::create([
            'restaurant_id' => $restaurant->id,
            'subdomain' => $restaurant->slug,
        ]);

        $websiteSetting->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Contact details updated successfully',
            'data' => $websiteSetting,
        ]);
    }

    /**
     * Show the media manager
     */
    public function media(Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $websiteSetting = $restaurant->websiteSetting ?? WebsiteSetting::create([
            'restaurant_id' => $restaurant->id,
            'subdomain' => $restaurant->slug,
        ]);

        return view('admin.website-builder.media', compact('restaurant', 'websiteSetting'));
    }

    /**
     * Upload logo
     */
    public function uploadLogo(Request $request, Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $websiteSetting = $restaurant->websiteSetting ?? WebsiteSetting::create([
            'restaurant_id' => $restaurant->id,
            'subdomain' => $restaurant->slug,
        ]);

        // Delete old logo if exists
        if ($websiteSetting->logo_path && Storage::disk('public')->exists($websiteSetting->logo_path)) {
            Storage::disk('public')->delete($websiteSetting->logo_path);
        }

        $path = $request->file('logo')->store("restaurants/{$restaurant->id}/logo", 'public');
        $websiteSetting->update(['logo_path' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Logo uploaded successfully',
            'path' => Storage::url($path),
        ]);
    }

    /**
     * Upload favicon
     */
    public function uploadFavicon(Request $request, Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $request->validate([
            'favicon' => 'required|image|mimes:jpeg,png,jpg,gif,ico|max:1024',
        ]);

        $websiteSetting = $restaurant->websiteSetting ?? WebsiteSetting::create([
            'restaurant_id' => $restaurant->id,
            'subdomain' => $restaurant->slug,
        ]);

        // Delete old favicon if exists
        if ($websiteSetting->favicon_path && Storage::disk('public')->exists($websiteSetting->favicon_path)) {
            Storage::disk('public')->delete($websiteSetting->favicon_path);
        }

        $path = $request->file('favicon')->store("restaurants/{$restaurant->id}/favicon", 'public');
        $websiteSetting->update(['favicon_path' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Favicon uploaded successfully',
            'path' => Storage::url($path),
        ]);
    }

    /**
     * Upload banner
     */
    public function uploadBanner(Request $request, Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $request->validate([
            'banner' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $websiteSetting = $restaurant->websiteSetting ?? WebsiteSetting::create([
            'restaurant_id' => $restaurant->id,
            'subdomain' => $restaurant->slug,
        ]);

        // Delete old banner if exists
        if ($websiteSetting->banner_path && Storage::disk('public')->exists($websiteSetting->banner_path)) {
            Storage::disk('public')->delete($websiteSetting->banner_path);
        }

        $path = $request->file('banner')->store("restaurants/{$restaurant->id}/banner", 'public');
        $websiteSetting->update(['banner_path' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Banner uploaded successfully',
            'path' => Storage::url($path),
        ]);
    }

    /**
     * Show domain settings
     */
    public function domain(Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $websiteSetting = $restaurant->websiteSetting ?? WebsiteSetting::create([
            'restaurant_id' => $restaurant->id,
            'subdomain' => $restaurant->slug,
        ]);

        return view('admin.website-builder.domain', compact('restaurant', 'websiteSetting'));
    }

    /**
     * Update domain settings
     */
    public function updateDomain(Request $request, Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $validated = $request->validate([
            'use_custom_domain' => 'boolean',
            'custom_domain' => 'nullable|string|max:255',
            'subdomain' => 'nullable|string|max:100|regex:/^[a-z0-9-]+$/',
        ]);

        $websiteSetting = $restaurant->websiteSetting ?? WebsiteSetting::create([
            'restaurant_id' => $restaurant->id,
            'subdomain' => $restaurant->slug,
        ]);

        $websiteSetting->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Domain settings updated successfully',
            'data' => $websiteSetting,
        ]);
    }

    /**
     * Publish website
     */
    public function publish(Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $websiteSetting = $restaurant->websiteSetting ?? WebsiteSetting::create([
            'restaurant_id' => $restaurant->id,
            'subdomain' => $restaurant->slug,
        ]);

        $websiteSetting->update(['is_published' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Website published successfully',
            'url' => $websiteSetting->getUrl(),
        ]);
    }

    /**
     * Unpublish website
     */
    public function unpublish(Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);

        $websiteSetting = $restaurant->websiteSetting;

        if ($websiteSetting) {
            $websiteSetting->update(['is_published' => false]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Website unpublished successfully',
        ]);
    }

    /**
     * Preview website
     */
    public function preview(Restaurant $restaurant)
    {
        $websiteSetting = $restaurant->websiteSetting;

        if (!$websiteSetting) {
            return redirect()->route('admin.path.website-builder.index', $restaurant->slug)
                ->with('error', 'Website not configured yet');
        }

        return redirect($websiteSetting->getUrl());
    }
}
