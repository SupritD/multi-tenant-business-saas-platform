<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'tenant_id',
        'plan_id',
        'status',
        'billing_cycle',
        'starts_at',
        'ends_at',
        'trial_ends_at',
        'cancelled_at',
        'auto_renew',
        'external_subscription_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'auto_renew' => 'boolean',
    ];

    /*
     * --------------------------------------------------------------------------
     * Relationships
     * --------------------------------------------------------------------------
     */

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /*
     * --------------------------------------------------------------------------
     * Subscription State
     * --------------------------------------------------------------------------
     */

    /**
     * Determine whether this subscription is currently active.
     *
     * A subscription is active when:
     *
     * - status is trial or active
     * - starts_at has been reached
     * - ends_at has not been reached
     */
    public function isActive(): bool
    {
        if (!in_array($this->status, ['trial', 'active'], true)) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->ends_at && $now->gte($this->ends_at)) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether this subscription has been cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled' ||
            $this->cancelled_at !== null;
    }

    /**
     * Determine whether this subscription has expired.
     */
    public function isExpired(): bool
    {
        if ($this->status === 'expired') {
            return true;
        }

        return $this->ends_at !== null &&
            now()->gte($this->ends_at);
    }

    /*
     * --------------------------------------------------------------------------
     * Feature Access
     * --------------------------------------------------------------------------
     */

    /**
     * Determine whether the subscription's plan provides a feature.
     *
     * This checks subscription + plan + plan feature.
     *
     * It does NOT check user permissions.
     */
    public function hasFeature(string $featureSlug): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        if (!$this->plan) {
            return false;
        }

        return $this->plan->hasFeature($featureSlug);
    }
}
