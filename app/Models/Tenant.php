<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tenant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'subdomain',
        'phone',
        'address',
        'logo_path',
        'subscription_plan_id',
        'subscription_starts_at',
        'subscription_ends_at',
        'trial_ends_at',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
        'subscription_starts_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tenant) {
            $tenant->slug = $tenant->slug ?? \Illuminate\Support\Str::slug($tenant->name);
            $tenant->trial_ends_at = $tenant->trial_ends_at ?? now()->addDays(14);
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function isOnTrial(): bool
    {
        return $this->trial_ends_at && now()->lt($this->trial_ends_at);
    }

    public function isSubscribed(): bool
    {
        return $this->subscription_ends_at && now()->lt($this->subscription_ends_at);
    }

    public function isActive(): bool
    {
        return $this->is_active && ($this->isOnTrial() || $this->isSubscribed());
    }
}
