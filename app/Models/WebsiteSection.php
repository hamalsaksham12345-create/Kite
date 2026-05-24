<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteSection extends Model
{
    protected $fillable = [
        'restaurant_id',
        'type',
        'title',
        'content',
        'image_path',
        'data',
        'sort_order',
        'is_visible',
        'background_color',
        'text_color',
    ];

    protected $casts = [
        'data' => 'array',
        'is_visible' => 'boolean',
    ];

    /**
     * Get the restaurant that owns this section
     */
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * Scope to get visible sections
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * Scope to get sections ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Get sections by type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
