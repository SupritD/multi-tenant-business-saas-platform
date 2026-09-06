<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use App\Services\RoleAssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_view_subscription_index(): void
    {
        $user = User::factory()->create([
            'tenant_id' => null,
            'status' => 'active',
        ]);

        $role = Role::query()->create([
            'tenant_id' => null,
            'name' => 'Super Admin',
            'slug' => 'super-admin',
            'role_type' => 'platform',
        ]);

        app(RoleAssignmentService::class)->syncRoles($user, [$role->id]);

        $response = $this
            ->actingAs($user)
            ->get(route('admin.subscriptions.index'));

        $response->assertOk();
    }

    public function test_platform_admin_cannot_view_subscription_index(): void
    {
        $user = User::factory()->create([
            'tenant_id' => null,
            'status' => 'active',
        ]);

        $role = Role::query()->create([
            'tenant_id' => null,
            'name' => 'Platform Admin',
            'slug' => 'platform-admin',
            'role_type' => 'platform',
        ]);

        app(RoleAssignmentService::class)->syncRoles($user, [$role->id]);

        $response = $this
            ->actingAs($user)
            ->get(route('admin.subscriptions.index'));

        $response->assertForbidden();
    }

    public function test_tenant_user_cannot_view_subscription_index(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('admin.subscriptions.index'));

        $response->assertForbidden();
    }

    public function test_guest_cannot_view_subscription_index(): void
    {
        $response = $this->get(route('admin.subscriptions.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_platform_admin_cannot_activate_subscription(): void
    {
        $user = User::factory()->create([
            'tenant_id' => null,
            'status' => 'active',
        ]);

        $role = Role::query()->create([
            'tenant_id' => null,
            'name' => 'Platform Admin',
            'slug' => 'platform-admin',
            'role_type' => 'platform',
        ]);

        app(RoleAssignmentService::class)->syncRoles($user, [$role->id]);

        $tenant = Tenant::query()->create([
            'name' => 'Test Tenant',
            'slug' => 'test-tenant-' . uniqid(),
            'status' => 'active',
        ]);

        $plan = Plan::query()->create([
            'name' => 'Test Plan',
            'slug' => 'test-plan-' . uniqid(),
            'monthly_price' => 0,
            'yearly_price' => 0,
            'trial_days' => 0,
            'is_free' => true,
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $subscription = Subscription::query()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'billing_cycle' => 'monthly',
            'starts_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('admin.subscriptions.activate', $subscription));

        $response->assertForbidden();
    }

    public function test_platform_admin_cannot_suspend_subscription(): void
    {
        $user = User::factory()->create([
            'tenant_id' => null,
            'status' => 'active',
        ]);

        $role = Role::query()->create([
            'tenant_id' => null,
            'name' => 'Platform Admin',
            'slug' => 'platform-admin',
            'role_type' => 'platform',
        ]);

        app(RoleAssignmentService::class)->syncRoles($user, [$role->id]);

        $tenant = Tenant::query()->create([
            'name' => 'Test Tenant',
            'slug' => 'test-tenant-' . uniqid(),
            'status' => 'active',
        ]);

        $plan = Plan::query()->create([
            'name' => 'Test Plan',
            'slug' => 'test-plan-' . uniqid(),
            'monthly_price' => 0,
            'yearly_price' => 0,
            'trial_days' => 0,
            'is_free' => true,
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $subscription = Subscription::query()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'billing_cycle' => 'monthly',
            'starts_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('admin.subscriptions.suspend', $subscription));

        $response->assertForbidden();
    }

    public function test_platform_admin_cannot_cancel_subscription(): void
    {
        $user = User::factory()->create([
            'tenant_id' => null,
            'status' => 'active',
        ]);

        $role = Role::query()->create([
            'tenant_id' => null,
            'name' => 'Platform Admin',
            'slug' => 'platform-admin',
            'role_type' => 'platform',
        ]);

        app(RoleAssignmentService::class)->syncRoles($user, [$role->id]);

        $tenant = Tenant::query()->create([
            'name' => 'Test Tenant',
            'slug' => 'test-tenant-' . uniqid(),
            'status' => 'active',
        ]);

        $plan = Plan::query()->create([
            'name' => 'Test Plan',
            'slug' => 'test-plan-' . uniqid(),
            'monthly_price' => 0,
            'yearly_price' => 0,
            'trial_days' => 0,
            'is_free' => true,
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $subscription = Subscription::query()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'billing_cycle' => 'monthly',
            'starts_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('admin.subscriptions.cancel', $subscription));

        $response->assertForbidden();
    }
}
