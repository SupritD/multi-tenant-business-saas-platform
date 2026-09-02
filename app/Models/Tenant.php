<?php

namespace App\Models;

use Database\Factories\TenantFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tenant extends Model
{
    /** @use HasFactory<TenantFactory> */
    use HasFactory;

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

    /**
     * @return HasMany<User, $this>
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

    /**
     * @return HasMany<Role, $this>
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
     *
     * @return HasMany<Subscription, $this>
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Most recently created subscription.
     *
     * This can be active, cancelled, expired, etc.
     *
     * @return HasOne<Subscription, $this>
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
     *
     * @return HasOne<Subscription, $this>
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
     *
     * @return BelongsToMany<Feature, $this>
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

    /**
     * Customers belonging to this tenant.
     *
     * @return HasMany<Customer, $this>
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
