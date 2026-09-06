<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use App\Services\TenantUserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class TenantUserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TenantUserService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(TenantUserService::class);
    }

    public function test_tenant_user_can_get_users_from_own_tenant(): void
    {
        $tenant = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $admin = $this->createTenantUser($tenant);

        $ownUser = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);

        $otherTenant = Tenant::factory()->create([
            'status' => 'active',
        ]);

        User::factory()->create([
            'tenant_id' => $otherTenant->id,
            'status' => 'active',
        ]);

        $users = $this->service->getUsers($admin);

        $this->assertTrue(
            $users->contains('id', $admin->id)
        );

        $this->assertTrue(
            $users->contains('id', $ownUser->id)
        );

        $this->assertCount(2, $users);
    }

    public function test_tenant_user_cannot_get_users_from_another_tenant(): void
    {
        $tenantOne = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $tenantTwo = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $admin = $this->createTenantUser($tenantOne);

        $foreignUser = User::factory()->create([
            'tenant_id' => $tenantTwo->id,
            'status' => 'active',
        ]);

        $users = $this->service->getUsers($admin);

        $this->assertFalse(
            $users->contains('id', $foreignUser->id)
        );
    }

    public function test_tenant_user_can_create_user(): void
    {
        $tenant = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $admin = $this->createTenantUser($tenant);

        $user = $this->service->create($admin, [
            'name' => 'New Employee',
            'email' => 'employee@example.com',
            'password' => 'Password@123',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'tenant_id' => $tenant->id,
            'name' => 'New Employee',
            'email' => 'employee@example.com',
            'status' => 'active',
        ]);
    }

    public function test_created_user_belongs_to_authenticated_users_tenant(): void
    {
        $tenantOne = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $tenantTwo = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $admin = $this->createTenantUser($tenantOne);

        $user = $this->service->create($admin, [
            'name' => 'Tenant Employee',
            'email' => 'employee@example.com',
            'password' => 'Password@123',
        ]);

        $this->assertSame(
            $tenantOne->id,
            $user->tenant_id
        );

        $this->assertNotSame(
            $tenantTwo->id,
            $user->tenant_id
        );
    }

    public function test_tenant_user_can_create_user_with_same_tenant_role(): void
    {
        $tenant = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $admin = $this->createTenantUser($tenant);

        $role = Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'Test Role',
            'slug' => 'test-role-'.uniqid(),
            'description' => 'Test role',
            'role_type' => 'tenant',
            'is_system' => false,
            'is_active' => true,
        ]);

        $user = $this->service->create($admin, [
            'name' => 'Sales Employee',
            'email' => 'sales@example.com',
            'password' => 'Password@123',
            'role_ids' => [$role->id],
        ]);

        $this->assertTrue(
            $user->roles->contains('id', $role->id)
        );
    }

    public function test_tenant_user_cannot_assign_role_from_another_tenant(): void
    {
        $tenantOne = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $tenantTwo = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $admin = $this->createTenantUser($tenantOne);

        $foreignRole = Role::create([
            'tenant_id' => $tenantTwo->id,
            'name' => 'Foreign Test Role',
            'slug' => 'foreign-test-role-'.uniqid(),
            'description' => 'Foreign test role',
            'role_type' => 'tenant',
            'is_system' => false,
            'is_active' => true,
        ]);

        $this->expectException(ValidationException::class);

        $this->service->create($admin, [
            'name' => 'Invalid Employee',
            'email' => 'invalid@example.com',
            'password' => 'Password@123',
            'role_ids' => [$foreignRole->id],
        ]);
    }

    public function test_tenant_user_cannot_create_platform_user(): void
    {
        $tenant = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $admin = $this->createTenantUser($tenant);

        $user = $this->service->create($admin, [
            'name' => 'Tenant Employee',
            'email' => 'employee@example.com',
            'password' => 'Password@123',
        ]);

        $this->assertNotNull($user->tenant_id);
        $this->assertSame($tenant->id, $user->tenant_id);
    }

    public function test_duplicate_email_is_rejected(): void
    {
        $tenant = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $admin = $this->createTenantUser($tenant);

        User::factory()->create([
            'tenant_id' => $tenant->id,
            'email' => 'existing@example.com',
            'status' => 'active',
        ]);

        $this->expectException(ValidationException::class);

        $this->service->create($admin, [
            'name' => 'Duplicate User',
            'email' => 'existing@example.com',
            'password' => 'Password@123',
        ]);
    }

    public function test_tenant_user_can_update_user_from_own_tenant(): void
    {
        $tenant = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $admin = $this->createTenantUser($tenant);

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);

        $updated = $this->service->update($admin, $user->id, [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $this->assertSame(
            'Updated Name',
            $updated->name
        );

        $this->assertSame(
            'updated@example.com',
            $updated->email
        );
    }

    public function test_tenant_user_cannot_update_user_from_another_tenant(): void
    {
        $tenantOne = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $tenantTwo = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $admin = $this->createTenantUser($tenantOne);

        $foreignUser = User::factory()->create([
            'tenant_id' => $tenantTwo->id,
            'status' => 'active',
        ]);

        $this->expectException(ModelNotFoundException::class);

        $this->service->update($admin, $foreignUser->id, [
            'name' => 'Hacked Name',
            'email' => 'hacked@example.com',
        ]);
    }

    public function test_update_without_role_ids_preserves_existing_roles(): void
    {
        $tenant = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $admin = $this->createTenantUser($tenant);

        $role = Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'Test Role',
            'slug' => 'test-role-'.uniqid(),
            'description' => 'Test role',
            'role_type' => 'tenant',
            'is_system' => false,
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);

        // $this->service->syncRolesForTest($user, [$role->id]);
        $user->roles()->attach($role->id, [
            'assigned_at' => now(),
        ]);

        $updated = $this->service->update($admin, $user->id, [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
        ]);

        $this->assertTrue(
            $updated->roles->contains('id', $role->id)
        );
    }

    public function test_update_with_role_ids_replaces_existing_roles(): void
    {
        $tenant = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $admin = $this->createTenantUser($tenant);

        $oldRole = Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'Old Test Role',
            'slug' => 'old-test-role-'.uniqid(),
            'description' => 'Old test role',
            'role_type' => 'tenant',
            'is_system' => false,
            'is_active' => true,
        ]);

        $newRole = Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'New Test Role',
            'slug' => 'new-test-role-'.uniqid(),
            'description' => 'New test role',
            'role_type' => 'tenant',
            'is_system' => false,
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);

        $user->roles()->attach($oldRole->id, [
            'assigned_at' => now(),
        ]);

        $updated = $this->service->update($admin, $user->id, [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
            'role_ids' => [$newRole->id],
        ]);

        $this->assertFalse(
            $updated->roles->contains('id', $oldRole->id)
        );

        $this->assertTrue(
            $updated->roles->contains('id', $newRole->id)
        );
    }

    public function test_tenant_user_can_deactivate_user_from_own_tenant(): void
    {
        $tenant = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $admin = $this->createTenantUser($tenant);

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);

        $this->service->deactivate($admin, $user->id);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'inactive',
        ]);
    }

    public function test_tenant_user_cannot_deactivate_user_from_another_tenant(): void
    {
        $tenantOne = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $tenantTwo = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $admin = $this->createTenantUser($tenantOne);

        $foreignUser = User::factory()->create([
            'tenant_id' => $tenantTwo->id,
            'status' => 'active',
        ]);

        $this->expectException(
            ModelNotFoundException::class
        );

        $this->service->deactivate(
            $admin,
            $foreignUser->id
        );
    }

    public function test_tenant_user_cannot_deactivate_themselves(): void
    {
        $tenant = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $admin = $this->createTenantUser($tenant);

        $this->expectException(ValidationException::class);

        $this->service->deactivate(
            $admin,
            $admin->id
        );
    }

    public function test_tenant_user_cannot_change_their_own_status(): void
    {
        $tenant = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $admin = $this->createTenantUser($tenant);

        $this->expectException(ValidationException::class);

        $this->service->update($admin, $admin->id, [
            'name' => $admin->name,
            'email' => $admin->email,
            'status' => 'inactive',
        ]);
    }

    public function test_tenant_user_cannot_change_their_own_roles(): void
    {
        $tenant = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $admin = $this->createTenantUser($tenant);

        $role = Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'Self Test Role',
            'slug' => 'self-test-role-'.uniqid(),
            'description' => 'Self test role',
            'role_type' => 'tenant',
            'is_system' => false,
            'is_active' => true,
        ]);

        $this->expectException(ValidationException::class);

        $this->service->update($admin, $admin->id, [
            'name' => $admin->name,
            'email' => $admin->email,
            'role_ids' => [$role->id],
        ]);
    }

    public function test_inactive_acting_user_cannot_use_service(): void
    {
        $tenant = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $admin = $this->createTenantUser($tenant, 'inactive');

        $this->expectException(ValidationException::class);

        $this->service->getUsers($admin);
    }

    public function test_user_from_inactive_tenant_cannot_use_service(): void
    {
        $tenant = Tenant::factory()->create([
            'status' => 'inactive',
        ]);

        $admin = $this->createTenantUser($tenant);

        $this->expectException(ValidationException::class);

        $this->service->getUsers($admin);
    }

    protected function createTenantUser(
        Tenant $tenant,
        string $status = 'active'
    ): User {
        return User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => $status,
        ]);
    }
}
