<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Feature;
use App\Models\Permission;
use App\Models\Plan;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    private function grantCustomerAccess(User $user): void
    {
        $tenantId = $user->tenant_id;

        $role = Role::create([
            'tenant_id' => $tenantId,
            'name' => 'Test Tenant Admin',
            'slug' => 'test-tenant-admin',
            'description' => 'Test role for customer authorization.',
            'role_type' => 'tenant',
            'is_system' => false,
            'is_active' => true,
        ]);

        $user->roles()->attach($role->id, [
            'assigned_at' => now(),
        ]);

        $permissionSlugs = [
            'customers.view',
            'customers.create',
            'customers.update',
            'customers.delete',
        ];

        foreach ($permissionSlugs as $slug) {
            $permission = Permission::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => ucfirst(str_replace('.', ' ', $slug)),
                    'module' => 'customers',
                    'action' => explode('.', $slug)[1],
                    'permission_type' => 'system',
                    'is_system' => true,
                    'is_active' => true,
                    'sort_order' => 0,
                ]
            );

            $role->permissions()->attach($permission->id, [
                'is_allowed' => true,
            ]);
        }

        $feature = Feature::firstOrCreate(
            ['slug' => 'customer-management'],
            [
                'name' => 'Customer Management',
                'category' => 'customers',
                'description' => 'Customer management module.',
                'feature_type' => 'module',
                'is_system' => true,
                'is_active' => true,
                'sort_order' => 0,
            ]
        );

        $plan = Plan::create([
            'name' => 'Test Plan',
            'slug' => 'test-plan-'.$tenantId,
            'description' => 'Test plan for customer authorization.',
            'monthly_price' => 0,
            'yearly_price' => 0,
            'trial_days' => 0,
            'is_free' => true,
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $plan->features()->attach($feature->id, [
            'is_enabled' => true,
        ]);

        Subscription::create([
            'tenant_id' => $tenantId,
            'plan_id' => $plan->id,
            'status' => 'active',
            'billing_cycle' => 'monthly',
            'starts_at' => now(),
            'ends_at' => null,
            'trial_ends_at' => null,
            'cancelled_at' => null,
            'auto_renew' => true,
        ]);
    }

    public function test_tenant_user_can_view_customer_index(): void
    {
        $tenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);

        $this->grantCustomerAccess($user);

        Customer::create([
            'tenant_id' => $tenant->id,
            'name' => 'Own Customer',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('customers.index'));

        $response->assertOk();
        $response->assertSee('Own Customer');
    }

    public function test_tenant_user_can_view_create_customer_page(): void
    {
        $tenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);
        $this->grantCustomerAccess($user);

        $response = $this
            ->actingAs($user)
            ->get(route('customers.create'));

        $response->assertOk();
        $response->assertSee('Add Customer');
    }

    public function test_tenant_user_can_create_customer(): void
    {
        $tenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);
        $this->grantCustomerAccess($user);

        $response = $this
            ->actingAs($user)
            ->post(route('customers.store'), [
                'name' => 'New Customer',
                'email' => 'new@example.com',
                'phone' => '9876543210',
                'company' => 'Example Company',
                'address' => 'Mumbai',
                'status' => 'active',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('customers', [
            'tenant_id' => $tenant->id,
            'name' => 'New Customer',
            'email' => 'new@example.com',
        ]);
    }

    public function test_tenant_user_can_view_own_customer(): void
    {
        $tenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);
        $this->grantCustomerAccess($user);

        $customer = Customer::create([
            'tenant_id' => $tenant->id,
            'name' => 'Own Customer',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('customers.show', $customer));

        $response->assertOk();
        $response->assertSee('Own Customer');
    }

    public function test_tenant_user_cannot_view_customer_from_another_tenant(): void
    {
        $tenant = Tenant::factory()->create();
        $otherTenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);
        $this->grantCustomerAccess($user);

        $customer = Customer::create([
            'tenant_id' => $otherTenant->id,
            'name' => 'Other Customer',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('customers.show', $customer));

        $response->assertNotFound();
    }

    public function test_tenant_user_can_update_own_customer(): void
    {
        $tenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);
        $this->grantCustomerAccess($user);

        $customer = Customer::create([
            'tenant_id' => $tenant->id,
            'name' => 'Original Name',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('customers.update', $customer), [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
                'phone' => '9876543210',
                'company' => 'Updated Company',
                'address' => 'Updated Address',
                'status' => 'active',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'tenant_id' => $tenant->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_tenant_user_cannot_update_customer_from_another_tenant(): void
    {
        $tenant = Tenant::factory()->create();
        $otherTenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);
        $this->grantCustomerAccess($user);

        $customer = Customer::create([
            'tenant_id' => $otherTenant->id,
            'name' => 'Original Name',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('customers.update', $customer), [
                'name' => 'Hacked Name',
                'status' => 'active',
            ]);

        $response->assertNotFound();

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Original Name',
        ]);
    }

    public function test_tenant_user_can_delete_own_customer(): void
    {
        $tenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);

        $customer = Customer::create([
            'tenant_id' => $tenant->id,
            'name' => 'Delete Me',
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('customers.destroy', $customer));

        $response->assertRedirect(route('customers.index'));

        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }

    public function test_tenant_user_cannot_delete_customer_from_another_tenant(): void
    {
        $tenant = Tenant::factory()->create();
        $otherTenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);

        $customer = Customer::create([
            'tenant_id' => $otherTenant->id,
            'name' => 'Protected Customer',
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('customers.destroy', $customer));

        $response->assertNotFound();

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
        ]);
    }

    public function test_platform_user_cannot_access_customers(): void
    {
        $user = User::factory()->create([
            'tenant_id' => null,
            'status' => 'active',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('customers.index'));

        $response->assertForbidden();
    }

    public function test_inactive_tenant_user_cannot_access_customers(): void
    {
        $tenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'inactive',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('customers.index'));

        $response->assertForbidden();
    }
}
