<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'restaurant_id',
        'table_number',
        'capacity',
        'status',
        'notes',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    /**
     * Get the restaurant that owns this table
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Get the orders for this table
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
