<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Store a new order from the public menu checkout
     */
    public function store(Request $request, Restaurant $restaurant)
    {
        // Validate incoming request
        $validated = $request->validate([
            'table_number' => 'nullable|string|max:50',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1|max:999',
        ]);

        try {
            // Start database transaction for safety
            $order = DB::transaction(function () use ($validated, $restaurant) {
                // Create the order
                $order = Order::create([
                    'restaurant_id' => $restaurant->id,
                    'user_id' => auth()->id() ?? null,
                    'table_number' => $validated['table_number'] ?? null,
                    'status' => 'pending',
                    'subtotal' => 0,
                    'tax_amount' => 0,
                    'total_amount' => 0,
                ]);

                $subtotal = 0;
                $items = $validated['items'];

                // Process each item in the order
                foreach ($items as $item) {
                    // Query the menu item directly from database to verify current price
                    $menuItem = MenuItem::findOrFail($item['id']);

                    // Verify the menu item belongs to this restaurant
                    if ($menuItem->restaurant_id !== $restaurant->id) {
                        throw new \Exception('Menu item does not belong to this restaurant');
                    }

                    // Calculate item total using database price (not frontend price)
                    $quantity = $item['quantity'];
                    $unitPrice = $menuItem->price;
                    $itemTotal = $unitPrice * $quantity;

                    // Create order item with captured price
                    $order->orderItems()->create([
                        'menu_item_id' => $menuItem->id,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $itemTotal,
                        'status' => 'pending',
                    ]);

                    $subtotal += $itemTotal;
                }

                // Calculate tax (assuming 13% VAT for Nepal)
                $taxAmount = $subtotal * 0.13;
                $totalAmount = $subtotal + $taxAmount;

                // Update order totals
                $order->update([
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount,
                ]);

                return $order;
            });

            // Return success response with order details
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total_amount' => $order->total_amount,
                'status' => $order->status,
            ], 201);

        } catch (\Exception $e) {
            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get order details (for confirmation page)
     */
    public function show(Order $order)
    {
        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'table_number' => $order->table_number,
                'status' => $order->status,
                'subtotal' => $order->subtotal,
                'tax_amount' => $order->tax_amount,
                'total_amount' => $order->total_amount,
                'items' => $order->orderItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'menu_item_id' => $item->menu_item_id,
                        'menu_item_name' => $item->menuItem->name,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total_price' => $item->total_price,
                    ];
                }),
            ],
        ]);
    }
}
