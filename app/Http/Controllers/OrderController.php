<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                $totalPrice = 0;
                $items = $validated['items'];

                // First pass: verify all items and calculate total
                $orderItems = [];
                foreach ($items as $item) {
                    // Query the menu item directly from database to verify current price
                    $menuItem = MenuItem::findOrFail($item['id']);

                    // Verify the menu item belongs to this restaurant
                    if ($menuItem->restaurant_id !== $restaurant->id) {
                        throw new \Exception('Menu item does not belong to this restaurant');
                    }

                    // Calculate item total using database price (not frontend price)
                    $quantity = $item['quantity'];
                    $price = $menuItem->price;
                    $itemTotal = $price * $quantity;

                    $orderItems[] = [
                        'menu_item_id' => $menuItem->id,
                        'quantity' => $quantity,
                        'price' => $price,
                    ];

                    $totalPrice += $itemTotal;
                }

                // Create the order with calculated total
                $order = Order::create([
                    'restaurant_id' => $restaurant->id,
                    'table_number' => $validated['table_number'] ?? null,
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                ]);

                // Create order items
                foreach ($orderItems as $item) {
                    $order->orderItems()->create($item);
                }

                return $order;
            });

            // Return success response with order details
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'order_id' => $order->id,
                'total_price' => $order->total_price,
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
     * Get order details
     */
    public function show(Order $order)
    {
        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'table_number' => $order->table_number,
                'status' => $order->status,
                'total_price' => $order->total_price,
                'items' => $order->orderItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'menu_item_id' => $item->menu_item_id,
                        'menu_item_name' => $item->menuItem->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Update order status to preparing (kitchen starts cooking)
     */
    public function updateToPreparing(Order $order)
    {
        // Verify order belongs to current restaurant
        if ($order->restaurant_id !== auth()->user()->restaurant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this order',
            ], 403);
        }

        $order->update(['status' => 'preparing']);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated to preparing',
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
            ],
        ]);
    }

    /**
     * Update order status to ready (food is ready for delivery)
     */
    public function updateToReady(Order $order)
    {
        // Verify order belongs to current restaurant
        if ($order->restaurant_id !== auth()->user()->restaurant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this order',
            ], 403);
        }

        $order->update(['status' => 'ready']);

        return response()->json([
            'success' => true,
            'message' => 'Order status updated to ready',
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
            ],
        ]);
    }

    /**
     * Update order status to completed (delivered to table)
     */
    public function updateToCompleted(Order $order)
    {
        // Verify order belongs to current restaurant
        if ($order->restaurant_id !== auth()->user()->restaurant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this order',
            ], 403);
        }

        $order->update(['status' => 'completed']);

        return response()->json([
            'success' => true,
            'message' => 'Order marked as completed',
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
            ],
        ]);
    }

    /**
     * Get all orders for a restaurant (for POS and KDS)
     */
    public function getRestaurantOrders(Restaurant $restaurant)
    {
        $orders = Order::where('restaurant_id', $restaurant->id)
            ->with('orderItems.menuItem')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'orders' => $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'table_number' => $order->table_number,
                    'status' => $order->status,
                    'total_price' => $order->total_price,
                    'created_at' => $order->created_at,
                    'items' => $order->orderItems->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'name' => $item->menuItem->name,
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                        ];
                    }),
                ];
            }),
        ]);
    }
}
