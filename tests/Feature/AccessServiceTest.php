<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\AccessService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccessServiceTest extends TestCase
{
    use RefreshDatabase;

    private AccessService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new AccessService;
    }

    private function createTenant(
        int $id = 1,
        string $name = 'Test Tenant',
        string $slug = 'test-tenant',
        string $status = 'active'
    ): void {
        DB::table('tenants')->insert([
            'id' => $id,
            'name' => $name,
            'slug' => $slug,
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function setupTenantUserRolePermission(): User
    {
        $this->createTenant();

        $user = User::factory()->create([
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
        ]);

        DB::table('user_roles')->insert([
            'user_id' => 1,
            'role_id' => 1,
        ]);

        return $user;
    }

    private function setupFeaturePlanSubscription(
        bool $isFeatureIncluded = true
    ): void {
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
            'monthly_price' => 2499.0,
            'yearly_price' => 24990.0,
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
            'is_enabled' => $isFeatureIncluded,
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
            'ends_at' => now()->addMonth(),
            'trial_ends_at' => null,
            'cancelled_at' => null,
            'auto_renew' => true,
            'external_subscription_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_permission_allowed(): void
    {
        $user = $this->setupTenantUserRolePermission();

        $this->assertTrue(
            $this->service->can($user, 'manage_users')
        );
    }

    public function test_permission_denied(): void
    {
        $user = $this->setupTenantUserRolePermission();

        $this->assertFalse(
            $this->service->can($user, 'delete_users')
        );
    }

    public function test_feature_included(): void
    {
        $user = $this->setupTenantUserRolePermission();

        $this->setupFeaturePlanSubscription(true);

        $this->assertTrue(
            $this->service->can(
                $user,
                'manage_users',
                'advanced_reports'
            )
        );
    }

    public function test_feature_not_included(): void
    {
        $user = $this->setupTenantUserRolePermission();

        $this->setupFeaturePlanSubscription(false);

        $this->assertFalse(
            $this->service->can(
                $user,
                'manage_users',
                'advanced_reports'
            )
        );
    }

    public function test_inactive_subscription(): void
    {
        $user = $this->setupTenantUserRolePermission();

        $this->setupFeaturePlanSubscription(true);

        DB::table('subscriptions')
            ->where('id', 1)
            ->update([
                'status' => 'cancelled',
            ]);

        $this->assertFalse(
            $this->service->can(
                $user,
                'manage_users',
                'advanced_reports'
            )
        );
    }

    public function test_expired_subscription(): void
    {
        $user = $this->setupTenantUserRolePermission();

        $this->setupFeaturePlanSubscription(true);

        DB::table('subscriptions')
            ->where('id', 1)
            ->update([
                'ends_at' => now()->subDay(),
            ]);

        $this->assertFalse(
            $this->service->can(
                $user,
                'manage_users',
                'advanced_reports'
            )
        );
    }

    public function test_future_subscription(): void
    {
        $user = $this->setupTenantUserRolePermission();

        $this->setupFeaturePlanSubscription(true);

        DB::table('subscriptions')
            ->where('id', 1)
            ->update([
                'starts_at' => now()->addDay(),
                'ends_at' => now()->addMonth(),
            ]);

        $this->assertFalse(
            $this->service->can(
                $user,
                'manage_users',
                'advanced_reports'
            )
        );
    }

    public function test_cross_tenant_role(): void
    {
        $user = $this->setupTenantUserRolePermission();

        $this->createTenant(
            id: 2,
            name: 'Other Tenant',
            slug: 'other-tenant'
        );

        $user->tenant_id = 2;
        $user->save();

        $this->assertFalse(
            $this->service->can($user, 'manage_users')
        );
    }

    public function test_inactive_tenant(): void
    {
        $user = $this->setupTenantUserRolePermission();

        DB::table('tenants')
            ->where('id', 1)
            ->update([
                'status' => 'inactive',
            ]);

        $this->assertFalse(
            $this->service->can($user, 'manage_users')
        );
    }

    public function test_tenant_feature_disabled(): void
    {
        $user = $this->setupTenantUserRolePermission();

        $this->setupFeaturePlanSubscription(true);

        DB::table('tenant_features')->insert([
            'tenant_id' => 1,
            'feature_id' => 1,
            'is_enabled' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertFalse(
            $this->service->can(
                $user,
                'manage_users',
                'advanced_reports'
            )
        );
    }

    public function test_tenant_feature_enabled_override(): void
    {
        $user = $this->setupTenantUserRolePermission();

        $this->setupFeaturePlanSubscription(false);

        DB::table('tenant_features')->insert([
            'tenant_id' => 1,
            'feature_id' => 1,
            'is_enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertTrue(
            $this->service->can(
                $user,
                'manage_users',
                'advanced_reports'
            )
        );
    }

    public function test_platform_user(): void
    {
        $user = User::factory()->create([
            'id' => 2,
            'tenant_id' => null,
            'status' => 'active',
        ]);

        DB::table('roles')->insert([
            'id' => 2,
            'tenant_id' => null,
            'name' => 'Super Admin',
            'slug' => 'super-admin',
            'description' => null,
            'role_type' => 'platform',
            'is_system' => true,
            'is_active' => true,
            'created_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('permissions')->insert([
            'id' => 2,
            'name' => 'Manage Platform',
            'slug' => 'manage_platform',
            'module' => 'platform',
            'action' => 'manage',
            'description' => null,
            'permission_type' => 'system',
            'is_system' => true,
            'is_active' => true,
            'sort_order' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('role_permissions')->insert([
            'role_id' => 2,
            'permission_id' => 2,
            'is_allowed' => true,
        ]);

        DB::table('user_roles')->insert([
            'user_id' => 2,
            'role_id' => 2,
        ]);

        $this->assertTrue(
            $this->service->can($user, 'manage_platform')
        );

        $this->assertFalse(
            $this->service->can($user, 'manage_users')
        );
    }

    public function test_inactive_user(): void
    {
        $user = $this->setupTenantUserRolePermission();

        $user->status = 'inactive';
        $user->save();

        $this->assertFalse(
            $this->service->can($user, 'manage_users')
        );
    }
}
