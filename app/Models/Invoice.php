<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'restaurant_id',
        'order_id',
        'invoice_number',
        'customer_name',
        'customer_phone',
        'customer_email',
        'subtotal',
        'tax_amount',
        'tax_percentage',
        'discount_amount',
        'discount_code',
        'service_charge',
        'total_amount',
        'payment_method',
        'payment_status',
        'paid_at',
        'notes',
        'items_json',
        'issued_at',
        'due_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'service_charge' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'items_json' => 'array',
        'issued_at' => 'datetime',
        'due_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the restaurant that owns this invoice
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Get the order for this invoice
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Check if invoice is paid
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if invoice is unpaid
     */
    public function isUnpaid(): bool
    {
        return $this->payment_status === 'unpaid';
    }

    /**
     * Check if invoice is overdue
     */
    public function isOverdue(): bool
    {
        if ($this->isPaid()) {
            return false;
        }

        if (!$this->due_at) {
            return false;
        }

        return now()->isAfter($this->due_at);
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid(?string $paymentMethod = null, ?string $transactionId = null): void
    {
        $this->update([
            'payment_status' => 'paid',
            'payment_method' => $paymentMethod ?? $this->payment_method,
            'paid_at' => now(),
        ]);

        // Update order payment status
        $this->order->update([
            'payment_status' => 'paid',
            'payment_method' => $paymentMethod ?? $this->payment_method,
            'paid_at' => now(),
            'transaction_id' => $transactionId,
        ]);
    }

    /**
     * Generate invoice number
     */
    public static function generateInvoiceNumber(Restaurant $restaurant): string
    {
        $prefix = strtoupper(substr($restaurant->slug, 0, 3));
        $date = now()->format('Ymd');
        $count = static::where('restaurant_id', $restaurant->id)
            ->whereDate('created_at', now())
            ->count() + 1;

        return "{$prefix}-{$date}-" . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
