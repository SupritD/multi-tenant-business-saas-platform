<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantUserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_user_with_view_permission_can_access_users_index(): void
    {
        $tenant = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);

        $role = Role::create([
            'tenant_id' => $tenant->id,
            'name' => 'Tenant Admin',
            'slug' => 'tenant-admin-test',
            'role_type' => 'tenant',
            'is_active' => true,
        ]);

        $permission = \App\Models\Permission::create([
            'name' => 'View Users',
            'slug' => 'users.view',
            'module' => 'users',
            'action' => 'view',
            'is_active' => true,
        ]);

        $role->permissions()->attach($permission->id, [
            'is_allowed' => true,
        ]);

        $user->roles()->attach($role->id, [
            'assigned_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('users.index'));

        $response->assertOk();
        $response->assertViewIs('users.index');
    }

    public function test_tenant_user_without_view_permission_is_forbidden(): void
    {
        $tenant = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('users.index'));

        $response->assertForbidden();
    }

    public function test_platform_user_cannot_access_tenant_user_management(): void
    {
        $user = User::factory()->create([
            'tenant_id' => null,
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('users.index'));

        $response->assertForbidden();
    }

    public function test_inactive_tenant_user_cannot_access_tenant_user_management(): void
    {
        $tenant = Tenant::factory()->create([
            'status' => 'active',
        ]);

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'inactive',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('users.index'));

        $response->assertForbidden();
    }

    public function test_inactive_tenant_cannot_access_tenant_user_management(): void
    {
        $tenant = Tenant::factory()->create([
            'status' => 'inactive',
        ]);

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('users.index'));

        $response->assertForbidden();
    }
}
