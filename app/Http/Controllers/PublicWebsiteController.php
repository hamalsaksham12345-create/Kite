<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\WebsiteSetting;

class PublicWebsiteController extends Controller
{
    /**
     * Show the public website homepage
     */
    public function index(Restaurant $restaurant)
    {
        $websiteSetting = $restaurant->websiteSetting;

        // Check if website is published
        if (!$websiteSetting || !$websiteSetting->isLive()) {
            return view('website.maintenance', compact('restaurant', 'websiteSetting'));
        }

        $sections = $restaurant->websiteSections()
            ->visible()
            ->ordered()
            ->get();

        $categories = $restaurant->categories()
            ->where('is_active', true)
            ->with(['menuItems' => function ($query) {
                $query->where('is_available', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('website.index', compact('restaurant', 'websiteSetting', 'sections', 'categories'));
    }

    /**
     * Show the menu page
     */
    public function menu(Restaurant $restaurant)
    {
        $websiteSetting = $restaurant->websiteSetting;

        if (!$websiteSetting || !$websiteSetting->isLive()) {
            return view('website.maintenance', compact('restaurant', 'websiteSetting'));
        }

        $categories = $restaurant->categories()
            ->where('is_active', true)
            ->with(['menuItems' => function ($query) {
                $query->where('is_available', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('website.menu', compact('restaurant', 'websiteSetting', 'categories'));
    }

    /**
     * Show the contact page
     */
    public function contact(Restaurant $restaurant)
    {
        $websiteSetting = $restaurant->websiteSetting;

        if (!$websiteSetting || !$websiteSetting->isLive()) {
            return view('website.maintenance', compact('restaurant', 'websiteSetting'));
        }

        return view('website.contact', compact('restaurant', 'websiteSetting'));
    }

    /**
     * Show the about page
     */
    public function about(Restaurant $restaurant)
    {
        $websiteSetting = $restaurant->websiteSetting;

        if (!$websiteSetting || !$websiteSetting->isLive()) {
            return view('website.maintenance', compact('restaurant', 'websiteSetting'));
        }

        return view('website.about', compact('restaurant', 'websiteSetting'));
    }

    /**
     * Handle contact form submission
     */
    public function submitContact(Restaurant $restaurant)
    {
        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // TODO: Send email to restaurant
        // TODO: Save contact inquiry to database

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your message. We will get back to you soon!',
        ]);
    }
}
