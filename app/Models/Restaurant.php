<?php

namespace App\Models;

use Database\Factories\RestaurantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): RestaurantFactory
    {
        return RestaurantFactory::new();
    }

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'logo',
        'primary_color',
        'secondary_color',
        'description',
        'is_active',
        'is_verified',
        'subscription_expires_at',
        'subscription_plan',
        'subscription_amount',
        'verified_at',
        'rejected_at',
        'suspended_at',
        'rejection_reason',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'subscription_expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'rejected_at' => 'datetime',
        'suspended_at' => 'datetime',
        'settings' => 'array',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($restaurant) {
            if (empty($restaurant->slug)) {
                $restaurant->slug = Str::slug($restaurant->name);
            }
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function tables(): HasMany
    {
        return $this->hasMany(Table::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function settings()
    {
        return $this->hasOne(RestaurantSetting::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getDaysRemainingAttribute(): int
    {
        if (!$this->subscription_expires_at) {
            return 0;
        }

        return max(0, Carbon::now()->diffInDays($this->subscription_expires_at, false));
    }

    public function isSubscriptionExpired(): bool
    {
        return $this->subscription_expires_at && Carbon::now()->isAfter($this->subscription_expires_at);
    }

    public function isPending(): bool
    {
        return !$this->is_verified;
    }

    public function getOwner()
    {
        return $this->users()->where('role', 'admin')->first();
    }

    /**
     * Get the current restaurant from the global context
     */
    public static function current(): ?self
    {
        return config('app.current_restaurant');
    }

    /**
     * Check if this restaurant is the current context restaurant
     */
    public function isCurrent(): bool
    {
        $current = self::current();
        return $current && $current->id === $this->id;
    }
}
