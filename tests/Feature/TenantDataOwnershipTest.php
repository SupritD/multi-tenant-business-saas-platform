<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use App\Services\RoleAssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class TenantDataOwnershipTest extends TestCase
{
    use RefreshDatabase;

    private function createTenant(
        int $id,
        string $name,
        string $slug
    ): Tenant {
        return Tenant::create([
            'id' => $id,
            'name' => $name,
            'slug' => $slug,
            'status' => 'active',
        ]);
    }

    private function createUser(
        int $id,
        int $tenantId
    ): User {
        return User::factory()->create([
            'id' => $id,
            'tenant_id' => $tenantId,
            'status' => 'active',
        ]);
    }

    private function createRole(
        int $id,
        int $tenantId,
        string $slug
    ): Role {
        return Role::create([
            'id' => $id,
            'tenant_id' => $tenantId,
            'name' => ucfirst($slug),
            'slug' => $slug,
            'description' => null,
            'role_type' => 'tenant',
            'is_system' => false,
            'is_active' => true,
            'created_by' => null,
        ]);
    }

    private function createSubscription(
        int $id,
        int $tenantId
    ): void {
        DB::table('plans')->insert([
            'id' => $id,
            'name' => "Plan {$id}",
            'slug' => "plan-{$id}",
            'description' => null,
            'monthly_price' => 1000,
            'yearly_price' => 10000,
            'trial_days' => 0,
            'is_free' => false,
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => $id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('subscriptions')->insert([
            'id' => $id,
            'tenant_id' => $tenantId,
            'plan_id' => $id,
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

    public function test_tenant_only_returns_its_own_users(): void
    {
        $tenantOne = $this->createTenant(
            1,
            'Tenant One',
            'tenant-one'
        );

        $tenantTwo = $this->createTenant(
            2,
            'Tenant Two',
            'tenant-two'
        );

        $userOne = $this->createUser(1, $tenantOne->id);
        $this->createUser(2, $tenantTwo->id);

        $users = $tenantOne->users()->get();

        $this->assertCount(1, $users);
        $this->assertTrue(
            $users->contains($userOne)
        );
        $this->assertFalse(
            $users->contains(
                fn (User $user) => $user->tenant_id === $tenantTwo->id
            )
        );
    }

    public function test_tenant_only_returns_its_own_roles(): void
    {
        $tenantOne = $this->createTenant(
            1,
            'Tenant One',
            'tenant-one'
        );

        $tenantTwo = $this->createTenant(
            2,
            'Tenant Two',
            'tenant-two'
        );

        $roleOne = $this->createRole(
            1,
            $tenantOne->id,
            'manager'
        );

        $this->createRole(
            2,
            $tenantTwo->id,
            'manager'
        );

        $roles = $tenantOne->roles()->get();

        $this->assertCount(1, $roles);
        $this->assertTrue(
            $roles->contains($roleOne)
        );
        $this->assertEquals(
            $tenantOne->id,
            $roles->first()->tenant_id
        );
    }

    public function test_tenant_only_returns_its_own_subscriptions(): void
    {
        $tenantOne = $this->createTenant(
            1,
            'Tenant One',
            'tenant-one'
        );

        $tenantTwo = $this->createTenant(
            2,
            'Tenant Two',
            'tenant-two'
        );

        $this->createSubscription(
            1,
            $tenantOne->id
        );

        $this->createSubscription(
            2,
            $tenantTwo->id
        );

        $subscriptions = $tenantOne->subscriptions()->get();

        $this->assertCount(1, $subscriptions);
        $this->assertEquals(
            $tenantOne->id,
            $subscriptions->first()->tenant_id
        );
    }

    public function test_user_belongs_to_exactly_one_tenant(): void
    {
        $tenantOne = $this->createTenant(
            1,
            'Tenant One',
            'tenant-one'
        );

        $tenantTwo = $this->createTenant(
            2,
            'Tenant Two',
            'tenant-two'
        );

        $user = $this->createUser(
            1,
            $tenantOne->id
        );

        $this->assertEquals(
            $tenantOne->id,
            $user->tenant_id
        );

        $this->assertEquals(
            $tenantOne->id,
            $user->tenant->id
        );

        $this->assertNotEquals(
            $tenantTwo->id,
            $user->tenant_id
        );
    }

    public function test_role_belongs_to_exactly_one_tenant(): void
    {
        $tenantOne = $this->createTenant(
            1,
            'Tenant One',
            'tenant-one'
        );

        $tenantTwo = $this->createTenant(
            2,
            'Tenant Two',
            'tenant-two'
        );

        $role = $this->createRole(
            1,
            $tenantOne->id,
            'manager'
        );

        $this->assertEquals(
            $tenantOne->id,
            $role->tenant_id
        );

        $this->assertEquals(
            $tenantOne->id,
            $role->tenant->id
        );

        $this->assertNotEquals(
            $tenantTwo->id,
            $role->tenant_id
        );
    }

    public function test_tenant_role_cannot_be_assigned_to_user_from_another_tenant(): void
    {
        $tenantOne = $this->createTenant(
            1,
            'Tenant One',
            'tenant-one'
        );

        $tenantTwo = $this->createTenant(
            2,
            'Tenant Two',
            'tenant-two'
        );

        $user = $this->createUser(
            1,
            $tenantOne->id
        );

        $role = $this->createRole(
            1,
            $tenantTwo->id,
            'manager'
        );

        $this->expectException(
            ValidationException::class
        );

        app(RoleAssignmentService::class)
            ->assignRole($user, $role);
    }

    public function test_platform_user_has_no_tenant(): void
    {
        $user = User::factory()->create([
            'tenant_id' => null,
            'status' => 'active',
        ]);

        $this->assertNull(
            $user->tenant_id
        );

        $this->assertNull(
            $user->tenant
        );

        $this->assertTrue(
            $user->isPlatformUser()
        );

        $this->assertFalse(
            $user->isTenantUser()
        );
    }

    public function test_tenant_user_is_not_a_platform_user(): void
    {
        $tenant = $this->createTenant(
            1,
            'Tenant One',
            'tenant-one'
        );

        $user = $this->createUser(
            1,
            $tenant->id
        );

        $this->assertFalse(
            $user->isPlatformUser()
        );

        $this->assertTrue(
            $user->isTenantUser()
        );
    }

    public function test_tenant_delete_cascades_to_users(): void
    {
        $tenant = $this->createTenant(
            1,
            'Tenant One',
            'tenant-one'
        );

        $this->createUser(
            1,
            $tenant->id
        );

        $tenant->delete();

        $this->assertDatabaseMissing(
            'tenants',
            ['id' => $tenant->id]
        );

        $this->assertDatabaseMissing(
            'users',
            ['id' => 1]
        );
    }

    public function test_tenant_delete_cascades_to_roles(): void
    {
        $tenant = $this->createTenant(
            1,
            'Tenant One',
            'tenant-one'
        );

        $this->createRole(
            1,
            $tenant->id,
            'manager'
        );

        $tenant->delete();

        $this->assertDatabaseMissing(
            'tenants',
            ['id' => $tenant->id]
        );

        $this->assertDatabaseMissing(
            'roles',
            ['id' => 1]
        );
    }

    public function test_tenant_delete_cascades_to_subscriptions(): void
    {
        $tenant = $this->createTenant(
            1,
            'Tenant One',
            'tenant-one'
        );

        $this->createSubscription(
            1,
            $tenant->id
        );

        $tenant->delete();

        $this->assertDatabaseMissing(
            'tenants',
            ['id' => $tenant->id]
        );

        $this->assertDatabaseMissing(
            'subscriptions',
            ['id' => 1]
        );
    }
}
