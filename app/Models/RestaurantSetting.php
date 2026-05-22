<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantSetting extends Model
{
    protected $fillable = [
        'restaurant_id',
        'currency',
        'timezone',
        'enable_online_ordering',
        'enable_table_reservations',
        'enable_delivery',
        'order_preparation_time',
        'delivery_charge',
        'tax_percentage',
        'business_hours',
        'contact_email',
        'contact_phone',
        'address',
        'description',
    ];

    protected $casts = [
        'enable_online_ordering' => 'boolean',
        'enable_table_reservations' => 'boolean',
        'enable_delivery' => 'boolean',
        'order_preparation_time' => 'integer',
        'delivery_charge' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'business_hours' => 'array',
    ];

    /**
     * Get the restaurant that owns this setting
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
