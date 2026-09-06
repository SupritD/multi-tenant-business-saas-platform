<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use App\Services\RoleAssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_view_plan_index(): void
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
            ->get(route('admin.plans.index'));

        $response->assertOk();
    }

    public function test_platform_admin_cannot_view_plan_index(): void
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
            ->get(route('admin.plans.index'));

        $response->assertForbidden();
    }

    public function test_tenant_user_cannot_view_plan_index(): void
    {
        $user = User::factory()->create([
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('admin.plans.index'));

        $response->assertForbidden();
    }

    public function test_guest_cannot_view_plan_index(): void
    {
        $response = $this->get(route('admin.plans.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_super_admin_can_view_plan_create_page(): void
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
            ->get(route('admin.plans.create'));

        $response->assertOk();
    }

    public function test_platform_admin_cannot_view_plan_create_page(): void
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
            ->get(route('admin.plans.create'));

        $response->assertForbidden();
    }
}
