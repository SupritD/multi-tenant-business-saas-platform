<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_user_can_view_customer_index(): void
    {
        $tenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);

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
