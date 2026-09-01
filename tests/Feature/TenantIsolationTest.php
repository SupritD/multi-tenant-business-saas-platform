<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\AccessService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    private AccessService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new AccessService;
    }

    private function createTenant(
        int $id,
        string $name,
        string $slug,
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

    private function createPermission(
        int $id,
        string $slug
    ): void {
        DB::table('permissions')->insert([
            'id' => $id,
            'name' => ucfirst(str_replace('_', ' ', $slug)),
            'slug' => $slug,
            'module' => 'test',
            'action' => 'test',
            'description' => null,
            'permission_type' => 'system',
            'is_system' => true,
            'is_active' => true,
            'sort_order' => $id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createRole(
        int $id,
        int $tenantId,
        string $slug
    ): void {
        DB::table('roles')->insert([
            'id' => $id,
            'tenant_id' => $tenantId,
            'name' => ucfirst($slug),
            'slug' => $slug,
            'description' => null,
            'role_type' => 'tenant',
            'is_system' => false,
            'is_active' => true,
            'created_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_user_cannot_use_role_from_another_tenant(): void
    {
        $this->createTenant(1, 'Tenant One', 'tenant-one');
        $this->createTenant(2, 'Tenant Two', 'tenant-two');

        $user = User::factory()->create([
            'tenant_id' => 1,
            'status' => 'active',
        ]);

        $this->createRole(1, 2, 'tenant-two-admin');
        $this->createPermission(1, 'manage_users');

        DB::table('role_permissions')->insert([
            'role_id' => 1,
            'permission_id' => 1,
            'is_allowed' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => 1,
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertFalse(
            $this->service->can($user, 'manage_users')
        );
    }

    public function test_tenant_user_can_only_use_role_from_own_tenant(): void
    {
        $this->createTenant(1, 'Tenant One', 'tenant-one');
        $this->createTenant(2, 'Tenant Two', 'tenant-two');

        $user = User::factory()->create([
            'tenant_id' => 1,
            'status' => 'active',
        ]);

        $this->createRole(1, 1, 'tenant-one-admin');
        $this->createRole(2, 2, 'tenant-two-admin');

        $this->createPermission(1, 'manage_users');

        DB::table('role_permissions')->insert([
            'role_id' => 1,
            'permission_id' => 1,
            'is_allowed' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('role_permissions')->insert([
            'role_id' => 2,
            'permission_id' => 1,
            'is_allowed' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => 1,
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertTrue(
            $this->service->can($user, 'manage_users')
        );

        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => 2,
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertTrue(
            $this->service->can($user, 'manage_users')
        );
    }

    public function test_changing_user_tenant_invalidates_previous_tenant_roles(): void
    {
        $this->createTenant(1, 'Tenant One', 'tenant-one');
        $this->createTenant(2, 'Tenant Two', 'tenant-two');

        $user = User::factory()->create([
            'tenant_id' => 1,
            'status' => 'active',
        ]);

        $this->createRole(1, 1, 'tenant-one-admin');
        $this->createPermission(1, 'manage_users');

        DB::table('role_permissions')->insert([
            'role_id' => 1,
            'permission_id' => 1,
            'is_allowed' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => 1,
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertTrue(
            $this->service->can($user, 'manage_users')
        );

        $user->update([
            'tenant_id' => 2,
        ]);

        $this->assertFalse(
            $this->service->can($user, 'manage_users')
        );
    }

    public function test_inactive_tenant_blocks_tenant_user(): void
    {
        $this->createTenant(
            1,
            'Tenant One',
            'tenant-one',
            'inactive'
        );

        $user = User::factory()->create([
            'tenant_id' => 1,
            'status' => 'active',
        ]);

        $this->createRole(1, 1, 'tenant-one-admin');
        $this->createPermission(1, 'manage_users');

        DB::table('role_permissions')->insert([
            'role_id' => 1,
            'permission_id' => 1,
            'is_allowed' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => 1,
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertFalse(
            $this->service->can($user, 'manage_users')
        );
    }

    public function test_platform_user_is_not_granted_tenant_role_access(): void
    {
        $this->createTenant(1, 'Tenant One', 'tenant-one');

        $user = User::factory()->create([
            'tenant_id' => null,
            'status' => 'active',
        ]);

        $this->createRole(1, 1, 'tenant-admin');
        $this->createPermission(1, 'manage_users');

        DB::table('role_permissions')->insert([
            'role_id' => 1,
            'permission_id' => 1,
            'is_allowed' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => 1,
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertFalse(
            $this->service->can($user, 'manage_users')
        );
    }

    public function test_foreign_tenant_role_alone_does_not_grant_permission(): void
    {
        $this->createTenant(1, 'Tenant One', 'tenant-one');
        $this->createTenant(2, 'Tenant Two', 'tenant-two');

        $user = User::factory()->create([
            'tenant_id' => 1,
            'status' => 'active',
        ]);

        $this->createRole(1, 2, 'tenant-two-admin');
        $this->createPermission(1, 'manage_users');

        DB::table('role_permissions')->insert([
            'role_id' => 1,
            'permission_id' => 1,
            'is_allowed' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => 1,
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertFalse(
            $this->service->can($user, 'manage_users')
        );
    }
}
