<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'restaurant_id',
        'order_id',
        'payment_method',
        'amount',
        'status',
        'transaction_id',
        'notes',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the restaurant that owns this payment
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Get the order for this payment
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
