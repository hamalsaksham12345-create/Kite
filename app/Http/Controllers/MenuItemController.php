<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MenuItemController extends Controller
{
    /**
     * Display a listing of menu items
     */
    public function index()
    {
        $menuItems = MenuItem::with(['category'])
            ->orderBy('category_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.menu-items.index', compact('menuItems', 'categories'));
    }

    /**
     * Show the form for creating a new menu item
     */
    public function create()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.menu-items.create', compact('categories'));
    }

    /**
     * Store a newly created menu item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            'ingredients' => 'nullable|string|max:1000',
            'allergens' => 'nullable|string|max:500',
            'preparation_time' => 'nullable|integer|min:1|max:300', // Max 5 hours
            'sort_order' => 'nullable|integer|min:0',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        // Security: Automatically set restaurant_id
        $validated['restaurant_id'] = auth()->user()->restaurant_id;

        // Convert comma-separated strings to arrays
        if ($validated['ingredients']) {
            $validated['ingredients'] = array_map('trim', explode(',', $validated['ingredients']));
        }

        if ($validated['allergens']) {
            $validated['allergens'] = array_map('trim', explode(',', $validated['allergens']));
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menu-items', 'public');
        }

        // Set default sort order if not provided
        if (!isset($validated['sort_order'])) {
            $maxOrder = MenuItem::where('category_id', $validated['category_id'])->max('sort_order') ?? 0;
            $validated['sort_order'] = $maxOrder + 1;
        }

        $menuItem = MenuItem::create($validated);

        return redirect()
            ->route('admin.path.menu-items.index', auth()->user()->restaurant->slug)
            ->with('success', 'Menu item created successfully!');
    }

    /**
     * Display the specified menu item
     */
    public function show(MenuItem $menuItem)
    {
        $menuItem->load('category');
        return view('admin.menu-items.show', compact('menuItem'));
    }

    /**
     * Show the form for editing the specified menu item
     */
    public function edit(MenuItem $menuItem)
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.menu-items.edit', compact('menuItem', 'categories'));
    }

    /**
     * Update the specified menu item
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            'ingredients' => 'nullable|string|max:1000',
            'allergens' => 'nullable|string|max:500',
            'preparation_time' => 'nullable|integer|min:1|max:300', // Max 5 hours
            'sort_order' => 'nullable|integer|min:0',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        // Security: Ensure restaurant_id doesn't change
        $validated['restaurant_id'] = auth()->user()->restaurant_id;

        // Convert comma-separated strings to arrays
        if ($validated['ingredients']) {
            $validated['ingredients'] = array_map('trim', explode(',', $validated['ingredients']));
        } else {
            $validated['ingredients'] = [];
        }

        if ($validated['allergens']) {
            $validated['allergens'] = array_map('trim', explode(',', $validated['allergens']));
        } else {
            $validated['allergens'] = [];
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($menuItem->image) {
                Storage::disk('public')->delete($menuItem->image);
            }
            $validated['image'] = $request->file('image')->store('menu-items', 'public');
        }

        $menuItem->update($validated);

        return redirect()
            ->route('admin.path.menu-items.index', auth()->user()->restaurant->slug)
            ->with('success', 'Menu item updated successfully!');
    }

    /**
     * Remove the specified menu item
     */
    public function destroy(MenuItem $menuItem)
    {
        // Delete image if exists
        if ($menuItem->image) {
            Storage::disk('public')->delete($menuItem->image);
        }

        $menuItem->delete();

        return redirect()
            ->route('admin.path.menu-items.index', auth()->user()->restaurant->slug)
            ->with('success', 'Menu item deleted successfully!');
    }

    /**
     * Toggle menu item availability
     */
    public function toggleAvailability(MenuItem $menuItem)
    {
        $menuItem->update([
            'is_available' => !$menuItem->is_available,
            'restaurant_id' => auth()->user()->restaurant_id, // Security
        ]);

        $status = $menuItem->is_available ? 'available' : 'out of stock';
        
        return redirect()
            ->route('admin.path.menu-items.index', auth()->user()->restaurant->slug)
            ->with('success', "Menu item marked as {$status}!");
    }

    /**
     * Toggle menu item featured status
     */
    public function toggleFeatured(MenuItem $menuItem)
    {
        $menuItem->update([
            'is_featured' => !$menuItem->is_featured,
            'restaurant_id' => auth()->user()->restaurant_id, // Security
        ]);

        $status = $menuItem->is_featured ? 'featured' : 'not featured';
        
        return redirect()
            ->route('admin.path.menu-items.index', auth()->user()->restaurant->slug)
            ->with('success', "Menu item marked as {$status}!");
    }
}