<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Services\AccessService;
use App\Services\RoleAssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    private RoleAssignmentService $roleService;

    private AccessService $accessService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->roleService = new RoleAssignmentService;
        $this->accessService = new AccessService;

        $this->createTenants();
    }

    private function createTenants(): void
    {
        DB::table('tenants')->insert([
            [
                'id' => 1,
                'name' => 'Tenant One',
                'slug' => 'tenant-one',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Tenant Two',
                'slug' => 'tenant-two',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    private function createUser(
        int $id,
        ?int $tenantId = 1,
        string $status = 'active'
    ): User {
        return User::factory()->create([
            'id' => $id,
            'tenant_id' => $tenantId,
            'status' => $status,
        ]);
    }

    private function createRole(
        int $id,
        ?int $tenantId,
        string $slug,
        bool $isActive = true,
        string $roleType = 'tenant'
    ): Role {
        return Role::create([
            'id' => $id,
            'tenant_id' => $tenantId,
            'name' => ucfirst($slug),
            'slug' => $slug,
            'description' => null,
            'role_type' => $roleType,
            'is_system' => false,
            'is_active' => $isActive,
            'created_by' => null,
        ]);
    }

    private function createPermission(
        int $id,
        string $slug,
        bool $isActive = true
    ): void {
        DB::table('permissions')->insert([
            'id' => $id,
            'name' => ucfirst($slug),
            'slug' => $slug,
            'module' => 'users',
            'action' => 'manage',
            'description' => null,
            'permission_type' => 'system',
            'is_system' => true,
            'is_active' => $isActive,
            'sort_order' => $id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function grantPermission(
        int $roleId,
        int $permissionId,
        bool $allowed = true
    ): void {
        DB::table('role_permissions')->insert([
            'role_id' => $roleId,
            'permission_id' => $permissionId,
            'is_allowed' => $allowed,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_active_tenant_role_can_be_assigned(): void
    {
        $user = $this->createUser(1, 1);
        $role = $this->createRole(1, 1, 'manager');

        $this->roleService->assignRole($user, $role);

        $this->assertDatabaseHas('user_roles', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
    }

    public function test_inactive_role_cannot_be_assigned(): void
    {
        $user = $this->createUser(1, 1);
        $role = $this->createRole(
            1,
            1,
            'manager',
            false
        );

        $this->expectException(ValidationException::class);

        $this->roleService->assignRole($user, $role);
    }

    public function test_inactive_user_cannot_receive_role(): void
    {
        $user = $this->createUser(
            1,
            1,
            'inactive'
        );

        $role = $this->createRole(1, 1, 'manager');

        $this->expectException(ValidationException::class);

        $this->roleService->assignRole($user, $role);
    }

    public function test_tenant_user_cannot_receive_role_from_another_tenant(): void
    {
        $user = $this->createUser(1, 1);
        $role = $this->createRole(1, 2, 'manager');

        $this->expectException(ValidationException::class);

        $this->roleService->assignRole($user, $role);
    }

    public function test_platform_user_can_receive_platform_role(): void
    {
        $user = $this->createUser(1, null);

        $role = $this->createRole(
            1,
            null,
            'super-admin',
            true,
            'platform'
        );

        $this->roleService->assignRole($user, $role);

        $this->assertDatabaseHas('user_roles', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
    }

    public function test_platform_user_cannot_receive_tenant_role(): void
    {
        $user = $this->createUser(1, null);
        $role = $this->createRole(1, 1, 'manager');

        $this->expectException(ValidationException::class);

        $this->roleService->assignRole($user, $role);
    }

    public function test_duplicate_role_assignment_is_harmless(): void
    {
        $user = $this->createUser(1, 1);
        $role = $this->createRole(1, 1, 'manager');

        $this->roleService->assignRole($user, $role);
        $this->roleService->assignRole($user, $role);

        $this->assertDatabaseCount('user_roles', 1);
    }

    public function test_role_can_be_removed(): void
    {
        $user = $this->createUser(1, 1);
        $role = $this->createRole(1, 1, 'manager');

        $this->roleService->assignRole($user, $role);
        $this->roleService->removeRole($user, $role);

        $this->assertDatabaseMissing('user_roles', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
    }

    public function test_sync_roles_replaces_existing_roles(): void
    {
        $user = $this->createUser(1, 1);

        $roleOne = $this->createRole(1, 1, 'manager');
        $roleTwo = $this->createRole(2, 1, 'sales');

        $this->roleService->assignRole($user, $roleOne);

        $this->roleService->syncRoles(
            $user,
            [$roleTwo->id]
        );

        $this->assertDatabaseMissing('user_roles', [
            'user_id' => $user->id,
            'role_id' => $roleOne->id,
        ]);

        $this->assertDatabaseHas('user_roles', [
            'user_id' => $user->id,
            'role_id' => $roleTwo->id,
        ]);
    }

    public function test_sync_roles_rejects_nonexistent_role(): void
    {
        $user = $this->createUser(1, 1);

        $this->expectException(ValidationException::class);

        $this->roleService->syncRoles(
            $user,
            [999]
        );
    }

    public function test_role_grants_permission_when_allowed(): void
    {
        $user = $this->createUser(1, 1);
        $role = $this->createRole(1, 1, 'manager');

        $this->createPermission(
            1,
            'manage_users'
        );

        $this->grantPermission(
            $role->id,
            1,
            true
        );

        $this->roleService->assignRole($user, $role);

        $this->assertTrue(
            $this->accessService->hasPermission(
                $user,
                'manage_users'
            )
        );
    }

    public function test_denied_role_permission_does_not_grant_access(): void
    {
        $user = $this->createUser(1, 1);
        $role = $this->createRole(1, 1, 'manager');

        $this->createPermission(
            1,
            'manage_users'
        );

        $this->grantPermission(
            $role->id,
            1,
            false
        );

        $this->roleService->assignRole($user, $role);

        $this->assertFalse(
            $this->accessService->hasPermission(
                $user,
                'manage_users'
            )
        );
    }

    public function test_inactive_permission_does_not_grant_access(): void
    {
        $user = $this->createUser(1, 1);
        $role = $this->createRole(1, 1, 'manager');

        $this->createPermission(
            1,
            'manage_users',
            false
        );

        $this->grantPermission(
            $role->id,
            1,
            true
        );

        $this->roleService->assignRole($user, $role);

        $this->assertFalse(
            $this->accessService->hasPermission(
                $user,
                'manage_users'
            )
        );
    }

    public function test_inactive_role_does_not_grant_permission(): void
    {
        $user = $this->createUser(1, 1);

        $role = $this->createRole(
            1,
            1,
            'manager',
            false
        );

        $this->createPermission(
            1,
            'manage_users'
        );

        $this->grantPermission(
            $role->id,
            1,
            true
        );

        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => $role->id,
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertFalse(
            $this->accessService->hasPermission(
                $user,
                'manage_users'
            )
        );
    }

    public function test_cross_tenant_role_cannot_grant_permission(): void
    {
        $user = $this->createUser(1, 1);

        $role = $this->createRole(
            1,
            2,
            'manager'
        );

        $this->createPermission(
            1,
            'manage_users'
        );

        $this->grantPermission(
            $role->id,
            1,
            true
        );

        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => $role->id,
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertFalse(
            $this->accessService->hasPermission(
                $user,
                'manage_users'
            )
        );
    }

    public function test_platform_role_does_not_grant_tenant_permission(): void
    {
        $user = $this->createUser(1, 1);

        $role = $this->createRole(
            1,
            null,
            'super-admin',
            true,
            'platform'
        );

        $this->createPermission(
            1,
            'manage_users'
        );

        $this->grantPermission(
            $role->id,
            1,
            true
        );

        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => $role->id,
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertFalse(
            $this->accessService->hasPermission(
                $user,
                'manage_users'
            )
        );
    }
}
