<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\AccessService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SubscriptionBoundaryTest extends TestCase
{
    use RefreshDatabase;

    private AccessService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new AccessService;

        $this->createTenant();
        $this->createUserRolePermission();
        $this->createFeaturePlanSubscription();
    }

    private function createTenant(): void
    {
        DB::table('tenants')->insert([
            'id' => 1,
            'name' => 'Test Tenant',
            'slug' => 'test-tenant',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createUserRolePermission(): void
    {
        User::factory()->create([
            'id' => 1,
            'tenant_id' => 1,
            'status' => 'active',
        ]);

        DB::table('roles')->insert([
            'id' => 1,
            'tenant_id' => 1,
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => null,
            'role_type' => 'tenant',
            'is_system' => false,
            'is_active' => true,
            'created_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('permissions')->insert([
            'id' => 1,
            'name' => 'Manage Users',
            'slug' => 'manage_users',
            'module' => 'users',
            'action' => 'manage',
            'description' => null,
            'permission_type' => 'system',
            'is_system' => true,
            'is_active' => true,
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('role_permissions')->insert([
            'role_id' => 1,
            'permission_id' => 1,
            'is_allowed' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_roles')->insert([
            'user_id' => 1,
            'role_id' => 1,
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createFeaturePlanSubscription(): void
    {
        DB::table('features')->insert([
            'id' => 1,
            'name' => 'Advanced Reports',
            'slug' => 'advanced_reports',
            'category' => 'Reports',
            'description' => null,
            'feature_type' => 'boolean',
            'is_system' => false,
            'is_active' => true,
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('plans')->insert([
            'id' => 1,
            'name' => 'Pro Plan',
            'slug' => 'pro-plan',
            'description' => null,
            'monthly_price' => 2499,
            'yearly_price' => 24990,
            'trial_days' => 0,
            'is_free' => false,
            'is_popular' => true,
            'is_active' => true,
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('plan_features')->insert([
            'plan_id' => 1,
            'feature_id' => 1,
            'is_enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subscriptions')->insert([
            'id' => 1,
            'tenant_id' => 1,
            'plan_id' => 1,
            'status' => 'active',
            'billing_cycle' => 'monthly',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
            'trial_ends_at' => null,
            'cancelled_at' => null,
            'auto_renew' => true,
            'external_subscription_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function user(): User
    {
        return User::findOrFail(1);
    }

    private function canAccessFeature(): bool
    {
        return $this->service->can(
            $this->user(),
            'manage_users',
            'advanced_reports'
        );
    }

    public function test_active_subscription_allows_feature(): void
    {
        $this->assertTrue($this->canAccessFeature());
    }

    public function test_trial_subscription_allows_feature(): void
    {
        DB::table('subscriptions')
            ->where('id', 1)
            ->update([
                'status' => 'trial',
            ]);

        $this->assertTrue($this->canAccessFeature());
    }

    public function test_cancelled_subscription_denies_feature(): void
    {
        DB::table('subscriptions')
            ->where('id', 1)
            ->update([
                'status' => 'cancelled',
            ]);

        $this->assertFalse($this->canAccessFeature());
    }

    public function test_subscription_starting_in_future_denies_feature(): void
    {
        DB::table('subscriptions')
            ->where('id', 1)
            ->update([
                'starts_at' => now()->addMinute(),
            ]);

        $this->assertFalse($this->canAccessFeature());
    }

    public function test_expired_subscription_denies_feature(): void
    {
        DB::table('subscriptions')
            ->where('id', 1)
            ->update([
                'ends_at' => now()->subMinute(),
            ]);

        $this->assertFalse($this->canAccessFeature());
    }

    public function test_subscription_without_end_date_allows_feature(): void
    {
        DB::table('subscriptions')
            ->where('id', 1)
            ->update([
                'ends_at' => null,
            ]);

        $this->assertTrue($this->canAccessFeature());
    }

    public function test_subscription_without_start_date_allows_feature(): void
    {
        DB::table('subscriptions')
            ->where('id', 1)
            ->update([
                'starts_at' => null,
            ]);

        $this->assertTrue($this->canAccessFeature());
    }

    public function test_inactive_plan_denies_feature(): void
    {
        DB::table('plans')
            ->where('id', 1)
            ->update([
                'is_active' => false,
            ]);

        $this->assertFalse($this->canAccessFeature());
    }

    public function test_feature_not_included_in_plan_denies_feature(): void
    {
        DB::table('plan_features')
            ->where('plan_id', 1)
            ->where('feature_id', 1)
            ->update([
                'is_enabled' => false,
            ]);

        $this->assertFalse($this->canAccessFeature());
    }

    public function test_subscription_start_boundary_allows_feature(): void
    {
        $start = now();

        DB::table('subscriptions')
            ->where('id', 1)
            ->update([
                'starts_at' => $start,
                'ends_at' => $start->copy()->addDay(),
            ]);

        $this->assertTrue($this->canAccessFeature());
    }

    public function test_subscription_end_boundary_allows_feature(): void
    {
        $end = now();

        DB::table('subscriptions')
            ->where('id', 1)
            ->update([
                'starts_at' => $end->copy()->subDay(),
                'ends_at' => $end,
            ]);

        $this->assertTrue($this->canAccessFeature());
    }
}
