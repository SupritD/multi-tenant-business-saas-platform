<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SubscriptionService
{
    public function getActiveSubscription(Tenant $tenant): ?Subscription
    {
        return $tenant
            ->subscriptions()
            ->with('plan')
            ->where('status', 'active')
            ->where(function ($query) {
                $query
                    ->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query
                    ->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->latest('id')
            ->first();
    }

    public function hasActiveSubscription(Tenant $tenant): bool
    {
        $subscription = $this->getActiveSubscription($tenant);

        return $subscription !== null &&
            $subscription->plan !== null &&
            $subscription->plan->is_active;
    }

    public function hasFeature(Tenant $tenant, string $featureSlug): bool
    {
        $subscription = $this->getActiveSubscription($tenant);

        if (! $subscription) {
            return false;
        }

        $plan = $subscription->plan;

        if (! $plan || ! $plan->is_active) {
            return false;
        }

        return $plan->hasFeature($featureSlug);
    }

    public function subscribe(
        Tenant $tenant,
        int $planId,
        string $billingCycle = 'monthly'
    ): Subscription {
        return DB::transaction(function () use (
            $tenant,
            $planId,
            $billingCycle
        ) {
            $plan = DB::table('plans')
                ->where('id', $planId)
                ->where('is_active', true)
                ->first();

            if (! $plan) {
                throw ValidationException::withMessages([
                    'plan_id' => 'The selected plan is not active or does not exist.',
                ]);
            }

            if (! in_array($billingCycle, ['monthly', 'yearly'], true)) {
                throw ValidationException::withMessages([
                    'billing_cycle' => 'Invalid billing cycle.',
                ]);
            }

            $tenant
                ->subscriptions()
                ->where('status', 'active')
                ->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                    'updated_at' => now(),
                ]);

            return $tenant->subscriptions()->create([
                'plan_id' => $planId,
                'status' => 'active',
                'billing_cycle' => $billingCycle,
                'starts_at' => now(),
            ]);
        });
    }
}
