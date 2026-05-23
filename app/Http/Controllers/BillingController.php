<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Restaurant;
use App\Services\BillingService;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    protected BillingService $billingService;

    public function __construct(BillingService $billingService)
    {
        $this->billingService = $billingService;
    }

    /**
     * Get billing dashboard
     */
    public function dashboard(Restaurant $restaurant)
    {
        // Verify user belongs to restaurant
        if (auth()->user()->restaurant_id !== $restaurant->id) {
            abort(403, 'Unauthorized');
        }

        $summary = $this->billingService->getPaymentSummary($restaurant, 'today');
        $unpaidOrders = $this->billingService->getUnpaidOrders($restaurant);

        return view('admin.billing.dashboard', compact('restaurant', 'summary', 'unpaidOrders'));
    }

    /**
     * Apply discount code to order
     */
    public function applyDiscount(Request $request, Order $order)
    {
        // Verify order belongs to user's restaurant
        if ($order->restaurant_id !== auth()->user()->restaurant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validated = $request->validate([
            'code' => 'required|string|max:50',
        ]);

        $result = $this->billingService->applyDiscount($order, $validated['code']);

        return response()->json($result);
    }

    /**
     * Generate invoice for order
     */
    public function generateInvoice(Request $request, Order $order)
    {
        // Verify order belongs to user's restaurant
        if ($order->restaurant_id !== auth()->user()->restaurant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email',
            'payment_method' => 'nullable|string|max:50',
        ]);

        try {
            $invoice = $this->billingService->generateInvoice(
                $order,
                $validated['payment_method'] ?? null,
                $validated['customer_name'] ?? null,
                $validated['customer_phone'] ?? null,
                $validated['customer_email'] ?? null
            );

            return response()->json([
                'success' => true,
                'message' => 'Invoice generated successfully',
                'invoice' => [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'total_amount' => $invoice->total_amount,
                    'payment_status' => $invoice->payment_status,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate invoice: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Process payment for order
     */
    public function processPayment(Request $request, Order $order)
    {
        // Verify order belongs to user's restaurant
        if ($order->restaurant_id !== auth()->user()->restaurant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validated = $request->validate([
            'payment_method' => 'required|string|in:cash,card,online',
            'transaction_id' => 'nullable|string|max:255',
        ]);

        try {
            $invoice = $this->billingService->processPayment(
                $order,
                $validated['payment_method'],
                $validated['transaction_id'] ?? null
            );

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'invoice' => [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'total_amount' => $invoice->total_amount,
                    'payment_status' => $invoice->payment_status,
                    'paid_at' => $invoice->paid_at,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process payment: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * View invoice
     */
    public function viewInvoice(Invoice $invoice)
    {
        // Verify invoice belongs to user's restaurant
        if ($invoice->restaurant_id !== auth()->user()->restaurant_id) {
            abort(403, 'Unauthorized');
        }

        return view('admin.billing.invoice', compact('invoice'));
    }

    /**
     * Download invoice as PDF
     */
    public function downloadInvoice(Invoice $invoice)
    {
        // Verify invoice belongs to user's restaurant
        if ($invoice->restaurant_id !== auth()->user()->restaurant_id) {
            abort(403, 'Unauthorized');
        }

        // For now, return JSON. PDF generation can be added later with a library like TCPDF or DomPDF
        return response()->json([
            'success' => true,
            'message' => 'PDF download feature coming soon',
            'invoice_number' => $invoice->invoice_number,
        ]);
    }

    /**
     * Get payment summary
     */
    public function paymentSummary(Request $request, Restaurant $restaurant)
    {
        // Verify user belongs to restaurant
        if (auth()->user()->restaurant_id !== $restaurant->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $period = $request->query('period', 'today');
        $summary = $this->billingService->getPaymentSummary($restaurant, $period);

        return response()->json([
            'success' => true,
            'summary' => $summary,
        ]);
    }

    /**
     * Get unpaid orders
     */
    public function unpaidOrders(Restaurant $restaurant)
    {
        // Verify user belongs to restaurant
        if (auth()->user()->restaurant_id !== $restaurant->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $unpaidOrders = $this->billingService->getUnpaidOrders($restaurant);

        return response()->json([
            'success' => true,
            'orders' => $unpaidOrders,
        ]);
    }
}
