<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Tenant;
use App\Models\User;
use App\Services\CustomerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CustomerServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CustomerService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(CustomerService::class);
    }

    public function test_tenant_user_can_get_own_customers(): void
    {
        $tenant = Tenant::factory()->create();
        $otherTenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);

        $ownCustomer = Customer::create([
            'tenant_id' => $tenant->id,
            'name' => 'Own Customer',
        ]);

        Customer::create([
            'tenant_id' => $otherTenant->id,
            'name' => 'Other Customer',
        ]);

        $customers = $this->service->getForUser($user);

        $this->assertCount(1, $customers);
        $this->assertTrue($customers->contains($ownCustomer));
        $this->assertFalse(
            $customers->contains('name', 'Other Customer')
        );
    }

    public function test_tenant_user_cannot_find_customer_from_another_tenant(): void
    {
        $tenant = Tenant::factory()->create();
        $otherTenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);

        $otherCustomer = Customer::create([
            'tenant_id' => $otherTenant->id,
            'name' => 'Other Customer',
        ]);

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->service->findForUser($user, $otherCustomer->id);
    }

    public function test_tenant_user_cannot_update_customer_from_another_tenant(): void
    {
        $tenant = Tenant::factory()->create();
        $otherTenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);

        $otherCustomer = Customer::create([
            'tenant_id' => $otherTenant->id,
            'name' => 'Original Name',
        ]);

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->service->update(
            $user,
            $otherCustomer->id,
            ['name' => 'Hacked Name']
        );

        $this->assertDatabaseHas('customers', [
            'id' => $otherCustomer->id,
            'name' => 'Original Name',
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

        $otherCustomer = Customer::create([
            'tenant_id' => $otherTenant->id,
            'name' => 'Protected Customer',
        ]);

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->service->delete($user, $otherCustomer->id);

        $this->assertDatabaseHas('customers', [
            'id' => $otherCustomer->id,
        ]);
    }

    public function test_customer_is_created_under_logged_in_users_tenant(): void
    {
        $tenant = Tenant::factory()->create();
        $otherTenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);

        $customer = $this->service->create($user, [
            'name' => 'New Customer',
            'email' => 'customer@example.com',
            'tenant_id' => $otherTenant->id,
        ]);

        $this->assertSame($tenant->id, $customer->tenant_id);
        $this->assertNotSame($otherTenant->id, $customer->tenant_id);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'tenant_id' => $tenant->id,
            'name' => 'New Customer',
        ]);
    }

    public function test_platform_user_cannot_use_customer_service(): void
    {
        $platformUser = User::factory()->create([
            'tenant_id' => null,
            'status' => 'active',
        ]);

        $this->expectException(ValidationException::class);

        $this->service->create($platformUser, [
            'name' => 'Platform Customer',
        ]);
    }

    public function test_inactive_user_cannot_use_customer_service(): void
    {
        $tenant = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'status' => 'inactive',
        ]);

        $this->expectException(ValidationException::class);

        $this->service->create($user, [
            'name' => 'Inactive User Customer',
        ]);
    }
}