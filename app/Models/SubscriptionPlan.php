<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_monthly',
        'price_yearly',
        'max_products',
        'max_users',
        'max_transactions_per_month',
        'features',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
    ];

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    public static function getCachedPlans()
    {
        return cache()->remember('subscription_plans', 3600, function () {
            return self::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });
    }

    public static function clearCache()
    {
        cache()->forget('subscription_plans');
    }

    protected static function booted()
    {
        static::saved(function () {
            self::clearCache();
        });
        
        static::deleted(function () {
            self::clearCache();
        });
    }
}
