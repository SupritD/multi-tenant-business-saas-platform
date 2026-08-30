<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'status',
    ];

    /*
     * |--------------------------------------------------------------------------
     * | Users
     * |--------------------------------------------------------------------------
     */

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /*
     * |--------------------------------------------------------------------------
     * | Roles
     * |--------------------------------------------------------------------------
     */

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    /*
     * |--------------------------------------------------------------------------
     * | Subscriptions
     * |--------------------------------------------------------------------------
     */

    /**
     * All subscriptions belonging to this tenant.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Most recently created subscription.
     *
     * This can be active, cancelled, expired, etc.
     */
    public function subscription(): HasOne
    {
        return $this
            ->hasOne(Subscription::class)
            ->latestOfMany();
    }

    /**
     * Currently active subscription.
     *
     * A subscription is considered active when:
     *
     * - status is trial or active
     * - starts_at has been reached
     * - ends_at has not been reached
     */
    public function activeSubscription(): HasOne
    {
        return $this
            ->hasOne(Subscription::class)
            ->whereIn('status', ['trial', 'active'])
            ->where('starts_at', '<=', now())
            ->where(function ($query) {
                $query
                    ->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->latestOfMany();
    }

    /**
     * Determine whether the tenant currently has an active subscription.
     */
    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }

    /*
     * |--------------------------------------------------------------------------
     * | Tenant Feature Overrides
     * |--------------------------------------------------------------------------
     */

    /**
     * Features explicitly configured for this tenant.
     *
     * Primary feature access should still come from:
     *
     * Subscription → Plan → Plan Features
     *
     * tenant_features can be used later for tenant-specific overrides.
     */
    public function features(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Feature::class,
                'tenant_features'
            )
            ->withPivot('is_enabled')
            ->withTimestamps();
    }
}
