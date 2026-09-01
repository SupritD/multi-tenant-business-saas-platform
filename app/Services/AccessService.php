<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class AccessService
{
    /**
     * Check whether a user has a permission.
     */
    public function hasPermission(User $user, string $permissionSlug): bool
    {
        if ($user->status !== 'active') {
            return false;
        }

        /*
         * |--------------------------------------------------------------------------
         * | Tenant User
         * |--------------------------------------------------------------------------
         * |
         * | Important:
         * | Only roles belonging to the same tenant may give permissions.
         * |
         */

        if ($user->tenant_id !== null) {
            return DB::table('user_roles')
                ->join('roles', 'roles.id', '=', 'user_roles.role_id')
                ->join(
                    'role_permissions',
                    'role_permissions.role_id',
                    '=',
                    'roles.id'
                )
                ->join(
                    'permissions',
                    'permissions.id',
                    '=',
                    'role_permissions.permission_id'
                )
                ->where('user_roles.user_id', $user->id)
                ->where('roles.tenant_id', $user->tenant_id)
                ->where('roles.is_active', true)
                ->where('permissions.slug', $permissionSlug)
                ->where('permissions.is_active', true)
                ->where('role_permissions.is_allowed', true)
                ->exists();
        }

        /*
         * |--------------------------------------------------------------------------
         * | Platform User
         * |--------------------------------------------------------------------------
         */

        return DB::table('user_roles')
            ->join('roles', 'roles.id', '=', 'user_roles.role_id')
            ->join(
                'role_permissions',
                'role_permissions.role_id',
                '=',
                'roles.id'
            )
            ->join(
                'permissions',
                'permissions.id',
                '=',
                'role_permissions.permission_id'
            )
            ->where('user_roles.user_id', $user->id)
            ->whereNull('roles.tenant_id')
            ->where('roles.role_type', 'platform')
            ->where('roles.is_active', true)
            ->where('permissions.slug', $permissionSlug)
            ->where('permissions.is_active', true)
            ->where('role_permissions.is_allowed', true)
            ->exists();
    }

    /**
     * Check whether a tenant currently has access to a feature.
     */
    public function hasFeature(
        int $tenantId,
        string $featureSlug
    ): bool {
        /*
         * |--------------------------------------------------------------------------
         * | Feature Must Exist & Be Globally Active
         * |--------------------------------------------------------------------------
         */

        $feature = DB::table('features')
            ->where('slug', $featureSlug)
            ->where('is_active', true)
            ->first();

        if (!$feature) {
            return false;
        }

        /*
         * |--------------------------------------------------------------------------
         * | Tenant Override
         * |--------------------------------------------------------------------------
         * |
         * | true  = force enabled
         * | false = force disabled
         * | null/no row = follow plan
         * |
         */

        $override = DB::table('tenant_features')
            ->where('tenant_id', $tenantId)
            ->where('feature_id', $feature->id)
            ->first();

        if ($override && $override->is_enabled !== null) {
            return (bool) $override->is_enabled;
        }

        /*
         * |--------------------------------------------------------------------------
         * | Active Subscription
         * |--------------------------------------------------------------------------
         */

        $subscription = DB::table('subscriptions')
            ->where('tenant_id', $tenantId)
            ->whereIn('status', [
                'active',
                'trial',
            ])
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

        if (!$subscription) {
            return false;
        }

        /*
         * |--------------------------------------------------------------------------
         * | Plan Must Be Active
         * |--------------------------------------------------------------------------
         */

        $planExists = DB::table('plans')
            ->where('id', $subscription->plan_id)
            ->where('is_active', true)
            ->exists();

        if (!$planExists) {
            return false;
        }

        /*
         * |--------------------------------------------------------------------------
         * | Feature Must Be Included In Plan
         * |--------------------------------------------------------------------------
         */

        return DB::table('plan_features')
            ->where('plan_id', $subscription->plan_id)
            ->where('feature_id', $feature->id)
            ->where('is_enabled', true)
            ->exists();
    }

    /**
     * Complete authorization check.
     */
    public function can(
        User $user,
        string $permissionSlug,
        ?string $featureSlug = null
    ): bool {
        /*
         * |--------------------------------------------------------------------------
         * | Account Status
         * |--------------------------------------------------------------------------
         */

        if ($user->status !== 'active') {
            return false;
        }

        /*
         * |--------------------------------------------------------------------------
         * | Platform Users
         * |--------------------------------------------------------------------------
         * |
         * | Platform permissions do not depend on tenant subscriptions.
         * |
         */

        if ($user->tenant_id === null) {
            return $this->hasPermission(
                $user,
                $permissionSlug
            );
        }

        /*
         * |--------------------------------------------------------------------------
         * | Tenant Validation
         * |--------------------------------------------------------------------------
         */

        $tenantActive = DB::table('tenants')
            ->where('id', $user->tenant_id)
            ->where('status', 'active')
            ->exists();

        if (!$tenantActive) {
            return false;
        }

        /*
         * |--------------------------------------------------------------------------
         * | Feature Entitlement
         * |--------------------------------------------------------------------------
         */

        if (
            $featureSlug !== null &&
            !$this->hasFeature($user->tenant_id, $featureSlug)
        ) {
            return false;
        }

        /*
         * |--------------------------------------------------------------------------
         * | Permission
         * |--------------------------------------------------------------------------
         */

        return $this->hasPermission(
            $user,
            $permissionSlug
        );
    }
}
