<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createPlatformRole(string $slug): Role
    {
        return Role::query()->create([
            'name' => str_replace('-', ' ', ucwords($slug, '-')),
            'slug' => $slug,
            'description' => null,
            'tenant_id' => null,
            'role_type' => 'platform',
            'is_active' => true,
        ]);
    }

    private function createSuperAdmin(): User
    {
        $role = $this->createPlatformRole('super-admin');

        $user = User::factory()->create([
            'tenant_id' => null,
            'status' => 'active',
        ]);

        $user->roles()->attach($role->id);

        return $user;
    }

    private function createPlatformAdmin(): User
    {
        $role = $this->createPlatformRole('platform-admin');

        $user = User::factory()->create([
            'tenant_id' => null,
            'status' => 'active',
        ]);

        $user->roles()->attach($role->id);

        return $user;
    }

    public function test_super_admin_can_create_plan(): void
    {
        $user = $this->createSuperAdmin();

        $response = $this
            ->actingAs($user)
            ->post(route('admin.plans.store'), [
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => 'Professional SaaS plan',
                'monthly_price' => 999,
                'yearly_price' => 9999,
                'trial_days' => 14,
                'sort_order' => 2,
                'is_free' => false,
                'is_popular' => true,
                'is_active' => true,
            ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('success', 'Plan created successfully.');

        $this->assertDatabaseHas('plans', [
            'name' => 'Professional',
            'slug' => 'professional',
            'monthly_price' => 999,
            'yearly_price' => 9999,
            'trial_days' => 14,
            'is_free' => false,
            'is_popular' => true,
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }

    public function test_platform_admin_cannot_create_plan(): void
    {
        $user = $this->createPlatformAdmin();

        $response = $this
            ->actingAs($user)
            ->post(route('admin.plans.store'), [
                'name' => 'Unauthorized Plan',
                'slug' => 'unauthorized-plan',
                'monthly_price' => 100,
                'yearly_price' => 1000,
                'trial_days' => 0,
                'sort_order' => 1,
                'is_active' => true,
            ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('plans', [
            'slug' => 'unauthorized-plan',
        ]);
    }

    public function test_guest_cannot_create_plan(): void
    {
        $response = $this->post(route('admin.plans.store'), [
            'name' => 'Guest Plan',
            'slug' => 'guest-plan',
            'monthly_price' => 100,
            'yearly_price' => 1000,
            'trial_days' => 0,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('plans', [
            'slug' => 'guest-plan',
        ]);
    }

    public function test_plan_creation_requires_required_fields(): void
    {
        $user = $this->createSuperAdmin();

        $response = $this
            ->actingAs($user)
            ->post(route('admin.plans.store'), []);

        $response->assertSessionHasErrors([
            'name',
            'slug',
            'monthly_price',
            'yearly_price',
            'trial_days',
            'sort_order',
        ]);

        $this->assertDatabaseCount('plans', 0);
    }

    public function test_plan_creation_rejects_duplicate_slug(): void
    {
        $user = $this->createSuperAdmin();

        Plan::query()->create([
            'name' => 'Existing Plan',
            'slug' => 'existing-plan',
            'description' => null,
            'monthly_price' => 100,
            'yearly_price' => 1000,
            'trial_days' => 0,
            'is_free' => false,
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('admin.plans.store'), [
                'name' => 'Another Plan',
                'slug' => 'existing-plan',
                'monthly_price' => 200,
                'yearly_price' => 2000,
                'trial_days' => 7,
                'sort_order' => 2,
                'is_active' => true,
            ]);

        $response->assertSessionHasErrors(['slug']);

        $this->assertDatabaseCount('plans', 1);
    }

    public function test_plan_creation_rejects_invalid_numeric_values(): void
    {
        $user = $this->createSuperAdmin();

        $response = $this
            ->actingAs($user)
            ->post(route('admin.plans.store'), [
                'name' => 'Invalid Plan',
                'slug' => 'invalid-plan',
                'monthly_price' => -100,
                'yearly_price' => -1000,
                'trial_days' => -1,
                'sort_order' => -1,
                'is_active' => true,
            ]);

        $response->assertSessionHasErrors([
            'monthly_price',
            'yearly_price',
            'trial_days',
            'sort_order',
        ]);

        $this->assertDatabaseMissing('plans', [
            'slug' => 'invalid-plan',
        ]);
    }

    public function test_super_admin_can_update_plan(): void
    {
        $user = $this->createSuperAdmin();

        $plan = Plan::query()->create([
            'name' => 'Starter',
            'slug' => 'starter',
            'description' => 'Starter plan',
            'monthly_price' => 499,
            'yearly_price' => 4999,
            'trial_days' => 7,
            'is_free' => false,
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('admin.plans.update', $plan), [
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => 'Professional plan',
                'monthly_price' => 999,
                'yearly_price' => 9999,
                'trial_days' => 14,
                'sort_order' => 2,
                'is_free' => false,
                'is_popular' => true,
                'is_active' => true,
            ]);

        $response
            ->assertRedirect(route('admin.plans.show', $plan))
            ->assertSessionHas('success', 'Plan updated successfully.');

        $this->assertDatabaseHas('plans', [
            'id' => $plan->id,
            'name' => 'Professional',
            'slug' => 'professional',
            'monthly_price' => 999,
            'yearly_price' => 9999,
            'trial_days' => 14,
            'is_popular' => true,
            'sort_order' => 2,
        ]);
    }

    public function test_super_admin_can_update_plan_without_changing_its_slug(): void
    {
        $user = $this->createSuperAdmin();

        $plan = Plan::query()->create([
            'name' => 'Starter',
            'slug' => 'starter',
            'description' => null,
            'monthly_price' => 499,
            'yearly_price' => 4999,
            'trial_days' => 7,
            'is_free' => false,
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('admin.plans.update', $plan), [
                'name' => 'Starter Updated',
                'slug' => 'starter',
                'description' => 'Updated description',
                'monthly_price' => 599,
                'yearly_price' => 5999,
                'trial_days' => 10,
                'sort_order' => 1,
                'is_free' => false,
                'is_popular' => false,
                'is_active' => true,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('plans', [
            'id' => $plan->id,
            'name' => 'Starter Updated',
            'slug' => 'starter',
        ]);
    }

    public function test_super_admin_cannot_update_plan_to_existing_slug(): void
    {
        $user = $this->createSuperAdmin();

        $firstPlan = Plan::query()->create([
            'name' => 'Starter',
            'slug' => 'starter',
            'description' => null,
            'monthly_price' => 499,
            'yearly_price' => 4999,
            'trial_days' => 7,
            'is_free' => false,
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $secondPlan = Plan::query()->create([
            'name' => 'Professional',
            'slug' => 'professional',
            'description' => null,
            'monthly_price' => 999,
            'yearly_price' => 9999,
            'trial_days' => 14,
            'is_free' => false,
            'is_popular' => true,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('admin.plans.update', $secondPlan), [
                'name' => 'Professional Updated',
                'slug' => $firstPlan->slug,
                'description' => null,
                'monthly_price' => 1099,
                'yearly_price' => 10999,
                'trial_days' => 14,
                'sort_order' => 2,
                'is_free' => false,
                'is_popular' => true,
                'is_active' => true,
            ]);

        $response->assertSessionHasErrors(['slug']);

        $this->assertDatabaseHas('plans', [
            'id' => $secondPlan->id,
            'slug' => 'professional',
        ]);
    }

    public function test_platform_admin_cannot_update_plan(): void
    {
        $user = $this->createPlatformAdmin();

        $plan = Plan::query()->create([
            'name' => 'Starter',
            'slug' => 'starter',
            'description' => null,
            'monthly_price' => 499,
            'yearly_price' => 4999,
            'trial_days' => 7,
            'is_free' => false,
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('admin.plans.update', $plan), [
                'name' => 'Hacked Plan',
                'slug' => 'hacked-plan',
                'monthly_price' => 1,
                'yearly_price' => 1,
                'trial_days' => 0,
                'sort_order' => 1,
                'is_active' => true,
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('plans', [
            'id' => $plan->id,
            'name' => 'Starter',
            'slug' => 'starter',
        ]);
    }

    public function test_super_admin_can_view_plan_show_page(): void
    {
        $user = $this->createSuperAdmin();

        $plan = Plan::query()->create([
            'name' => 'Starter',
            'slug' => 'starter',
            'description' => 'Starter plan',
            'monthly_price' => 499,
            'yearly_price' => 4999,
            'trial_days' => 7,
            'is_free' => false,
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('admin.plans.show', $plan));

        $response
            ->assertOk()
            ->assertSee('Starter')
            ->assertSee('499.00')
            ->assertSee('4,999.00');
    }

    public function test_platform_admin_cannot_view_plan_show_page(): void
    {
        $user = $this->createPlatformAdmin();

        $plan = Plan::query()->create([
            'name' => 'Starter',
            'slug' => 'starter',
            'description' => null,
            'monthly_price' => 499,
            'yearly_price' => 4999,
            'trial_days' => 7,
            'is_free' => false,
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('admin.plans.show', $plan));

        $response->assertForbidden();
    }

    public function test_super_admin_can_deactivate_plan_without_active_subscriptions(): void
    {
        $user = $this->createSuperAdmin();

        $plan = Plan::query()->create([
            'name' => 'Starter',
            'slug' => 'starter',
            'description' => null,
            'monthly_price' => 499,
            'yearly_price' => 4999,
            'trial_days' => 7,
            'is_free' => false,
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('admin.plans.destroy', $plan));

        $response
            ->assertRedirect(route('admin.plans.index'))
            ->assertSessionHas('success', 'Plan deactivated successfully.');

        $this->assertDatabaseHas('plans', [
            'id' => $plan->id,
            'is_active' => false,
        ]);
    }

    public function test_super_admin_cannot_deactivate_plan_with_active_subscription(): void
    {
        $user = $this->createSuperAdmin();

        $plan = Plan::query()->create([
            'name' => 'Starter',
            'slug' => 'starter',
            'description' => null,
            'monthly_price' => 499,
            'yearly_price' => 4999,
            'trial_days' => 7,
            'is_free' => false,
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $tenant = \App\Models\Tenant::query()->create([
            'name' => 'Test Tenant',
            'slug' => 'test-tenant-' . uniqid(),
            'status' => 'active',
        ]);

        \App\Models\Subscription::query()->create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'billing_cycle' => 'monthly',
            'starts_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('admin.plans.destroy', $plan));

        $response->assertSessionHasErrors(['plan']);

        $this->assertDatabaseHas('plans', [
            'id' => $plan->id,
            'is_active' => true,
        ]);
    }

    public function test_platform_admin_cannot_deactivate_plan(): void
    {
        $user = $this->createPlatformAdmin();

        $plan = Plan::query()->create([
            'name' => 'Starter',
            'slug' => 'starter',
            'description' => null,
            'monthly_price' => 499,
            'yearly_price' => 4999,
            'trial_days' => 7,
            'is_free' => false,
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('admin.plans.destroy', $plan));

        $response->assertForbidden();

        $this->assertDatabaseHas('plans', [
            'id' => $plan->id,
            'is_active' => true,
        ]);
    }
}
