<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    /**
     * Display a listing of discount codes
     */
    public function index(Restaurant $restaurant)
    {
        // Verify user belongs to restaurant
        if (auth()->user()->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized');
        }

        $discountCodes = DiscountCode::where('restaurant_id', $restaurant->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.discount-codes.index', compact('restaurant', 'discountCodes'));
    }

    /**
     * Show the form for creating a new discount code
     */
    public function create(Restaurant $restaurant)
    {
        // Verify user belongs to restaurant
        if (auth()->user()->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized');
        }

        return view('admin.discount-codes.create', compact('restaurant'));
    }

    /**
     * Store a newly created discount code
     */
    public function store(Request $request, Restaurant $restaurant)
    {
        // Verify user belongs to restaurant
        if (auth()->user()->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:discount_codes,code',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
        ]);

        $validated['restaurant_id'] = $restaurant->id;
        $validated['code'] = strtoupper($validated['code']);

        DiscountCode::create($validated);

        return redirect()
            ->route('admin.path.discount-codes.index', $restaurant->slug)
            ->with('success', 'Discount code created successfully!');
    }

    /**
     * Show the form for editing a discount code
     */
    public function edit(Restaurant $restaurant, DiscountCode $discountCode)
    {
        // Verify user belongs to restaurant
        if (auth()->user()->restaurant_id !== $restaurant->id || $discountCode->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized');
        }

        return view('admin.discount-codes.edit', compact('restaurant', 'discountCode'));
    }

    /**
     * Update the specified discount code
     */
    public function update(Request $request, Restaurant $restaurant, DiscountCode $discountCode)
    {
        // Verify user belongs to restaurant
        if (auth()->user()->restaurant_id !== $restaurant->id || $discountCode->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
        ]);

        $discountCode->update($validated);

        return redirect()
            ->route('admin.path.discount-codes.index', $restaurant->slug)
            ->with('success', 'Discount code updated successfully!');
    }

    /**
     * Delete the specified discount code
     */
    public function destroy(Restaurant $restaurant, DiscountCode $discountCode)
    {
        // Verify user belongs to restaurant
        if (auth()->user()->restaurant_id !== $restaurant->id || $discountCode->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized');
        }

        $discountCode->delete();

        return redirect()
            ->route('admin.path.discount-codes.index', $restaurant->slug)
            ->with('success', 'Discount code deleted successfully!');
    }

    /**
     * Toggle discount code status
     */
    public function toggleStatus(Restaurant $restaurant, DiscountCode $discountCode)
    {
        // Verify user belongs to restaurant
        if (auth()->user()->restaurant_id !== $restaurant->id || $discountCode->restaurant_id !== $restaurant->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $discountCode->update([
            'is_active' => !$discountCode->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Discount code status updated',
            'is_active' => $discountCode->is_active,
        ]);
    }

    /**
     * Validate discount code
     */
    public function validate(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'order_total' => 'required|numeric|min:0',
        ]);

        $discountCode = DiscountCode::where('code', strtoupper($validated['code']))
            ->where('restaurant_id', $restaurant->id)
            ->first();

        if (!$discountCode) {
            return response()->json([
                'success' => false,
                'message' => 'Discount code not found',
            ]);
        }

        if (!$discountCode->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Discount code is not valid or has expired',
            ]);
        }

        $discountAmount = $discountCode->calculateDiscount($validated['order_total']);

        return response()->json([
            'success' => true,
            'message' => 'Discount code is valid',
            'discount_amount' => $discountAmount,
            'discount_percentage' => $discountCode->type === 'percentage' ? $discountCode->value : 0,
        ]);
    }
}
