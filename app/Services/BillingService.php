<?php

namespace App\Services;

use App\Models\DiscountCode;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;

class BillingService
{
    /**
     * Calculate order totals with tax, discount, and service charge
     */
    public function calculateOrderTotals(
        Order $order,
        ?string $discountCode = null,
        ?float $taxPercentage = null,
        ?float $serviceChargePercentage = null
    ): array {
        // Get restaurant settings
        $restaurant = $order->restaurant;
        $taxPercentage = $taxPercentage ?? ($restaurant->restaurantSetting?->tax_percentage ?? 0);
        $serviceChargePercentage = $serviceChargePercentage ?? ($restaurant->restaurantSetting?->service_charge_percentage ?? 0);

        // Calculate subtotal from order items
        $subtotal = $order->orderItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        // Apply discount
        $discountAmount = 0;
        $discountPercentage = 0;
        if ($discountCode) {
            $discount = DiscountCode::where('code', $discountCode)
                ->where('restaurant_id', $restaurant->id)
                ->first();

            if ($discount && $discount->isValid()) {
                $discountAmount = $discount->calculateDiscount($subtotal);
                $discountPercentage = $discount->type === 'percentage' ? $discount->value : 0;
            }
        }

        // Calculate tax on subtotal after discount
        $taxableAmount = $subtotal - $discountAmount;
        $taxAmount = round(($taxableAmount * $taxPercentage) / 100, 2);

        // Calculate service charge
        $serviceCharge = round(($taxableAmount * $serviceChargePercentage) / 100, 2);

        // Calculate total
        $totalPrice = $taxableAmount + $taxAmount + $serviceCharge;

        return [
            'subtotal' => round($subtotal, 2),
            'discount_amount' => round($discountAmount, 2),
            'discount_percentage' => $discountPercentage,
            'discount_code' => $discountCode,
            'tax_amount' => $taxAmount,
            'tax_percentage' => $taxPercentage,
            'service_charge' => $serviceCharge,
            'service_charge_percentage' => $serviceChargePercentage,
            'total_price' => round($totalPrice, 2),
        ];
    }

    /**
     * Generate invoice for order
     */
    public function generateInvoice(
        Order $order,
        ?string $paymentMethod = null,
        ?string $customerName = null,
        ?string $customerPhone = null,
        ?string $customerEmail = null
    ): Invoice {
        return DB::transaction(function () use ($order, $paymentMethod, $customerName, $customerPhone, $customerEmail) {
            // Generate invoice number
            $invoiceNumber = Invoice::generateInvoiceNumber($order->restaurant);

            // Prepare items data
            $itemsData = $order->orderItems->map(function ($item) {
                return [
                    'name' => $item->menuItem->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity,
                ];
            })->toArray();

            // Create invoice
            $invoice = Invoice::create([
                'restaurant_id' => $order->restaurant_id,
                'order_id' => $order->id,
                'invoice_number' => $invoiceNumber,
                'customer_name' => $customerName ?? $order->customer_name,
                'customer_phone' => $customerPhone ?? $order->customer_phone,
                'customer_email' => $customerEmail,
                'subtotal' => $order->subtotal,
                'tax_amount' => $order->tax_amount,
                'tax_percentage' => $order->tax_percentage,
                'discount_amount' => $order->discount_amount,
                'discount_code' => $order->discount_code,
                'service_charge' => $order->service_charge,
                'total_amount' => $order->total_price,
                'payment_method' => $paymentMethod,
                'payment_status' => $order->payment_status,
                'paid_at' => $order->paid_at,
                'notes' => $order->notes,
                'items_json' => $itemsData,
                'issued_at' => now(),
                'due_at' => now()->addDays(30),
            ]);

            // Update order with invoice details
            $order->update([
                'invoice_number' => $invoiceNumber,
                'invoice_generated_at' => now(),
            ]);

            return $invoice;
        });
    }

    /**
     * Process payment for order
     */
    public function processPayment(
        Order $order,
        string $paymentMethod,
        ?string $transactionId = null
    ): Invoice {
        return DB::transaction(function () use ($order, $paymentMethod, $transactionId) {
            // Update order payment status
            $order->update([
                'payment_method' => $paymentMethod,
                'payment_status' => 'paid',
                'paid_at' => now(),
                'transaction_id' => $transactionId,
            ]);

            // Get or create invoice
            $invoice = $order->invoice ?? $this->generateInvoice($order, $paymentMethod);

            // Mark invoice as paid
            $invoice->markAsPaid($paymentMethod, $transactionId);

            return $invoice;
        });
    }

    /**
     * Apply discount code to order
     */
    public function applyDiscount(Order $order, string $code): array
    {
        $discount = DiscountCode::where('code', $code)
            ->where('restaurant_id', $order->restaurant_id)
            ->first();

        if (!$discount) {
            return [
                'success' => false,
                'message' => 'Discount code not found',
            ];
        }

        if (!$discount->isValid()) {
            return [
                'success' => false,
                'message' => 'Discount code is not valid or has expired',
            ];
        }

        // Recalculate totals with discount
        $totals = $this->calculateOrderTotals($order, $code);

        // Update order
        $order->update($totals);

        return [
            'success' => true,
            'message' => 'Discount applied successfully',
            'totals' => $totals,
        ];
    }

    /**
     * Get payment summary for restaurant
     */
    public function getPaymentSummary(Restaurant $restaurant, ?string $period = 'today'): array
    {
        $query = Order::where('restaurant_id', $restaurant->id)
            ->where('payment_status', 'paid');

        $startDate = match ($period) {
            'today' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->startOfDay(),
        };

        $query->whereDate('paid_at', '>=', $startDate);

        $paidOrders = $query->get();

        return [
            'total_revenue' => $paidOrders->sum('total_price'),
            'total_orders' => $paidOrders->count(),
            'average_order_value' => $paidOrders->count() > 0 ? $paidOrders->sum('total_price') / $paidOrders->count() : 0,
            'total_tax_collected' => $paidOrders->sum('tax_amount'),
            'total_discounts_given' => $paidOrders->sum('discount_amount'),
            'total_service_charges' => $paidOrders->sum('service_charge'),
            'period' => $period,
        ];
    }

    /**
     * Get unpaid orders for restaurant
     */
    public function getUnpaidOrders(Restaurant $restaurant): array
    {
        return Order::where('restaurant_id', $restaurant->id)
            ->where('payment_status', 'unpaid')
            ->with('orderItems.menuItem')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'table_number' => $order->table_number,
                    'total_price' => $order->total_price,
                    'created_at' => $order->created_at,
                    'items_count' => $order->orderItems->count(),
                ];
            })
            ->toArray();
    }
}
