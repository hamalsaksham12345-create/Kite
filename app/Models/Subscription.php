<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'restaurant_id',
        'plan_name',
        'plan_price',
        'billing_cycle',
        'status',
        'started_at',
        'expires_at',
        'cancelled_at',
        'max_users',
        'max_menu_items',
        'max_tables',
        'has_analytics',
        'has_delivery',
        'has_loyalty_program',
        'notes',
    ];

    protected $casts = [
        'plan_price' => 'decimal:2',
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'max_users' => 'integer',
        'max_menu_items' => 'integer',
        'max_tables' => 'integer',
        'has_analytics' => 'boolean',
        'has_delivery' => 'boolean',
        'has_loyalty_program' => 'boolean',
    ];

    /**
     * Get the restaurant that owns this subscription
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Check if subscription is active
     */
    public function isActive()
    {
        return $this->status === 'active' && 
               ($this->expires_at === null || $this->expires_at->isFuture());
    }
}
