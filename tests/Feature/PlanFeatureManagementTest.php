<?php

namespace Tests\Feature;

use App\Models\Feature;
use App\Models\Plan;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanFeatureManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createPlatformRole(string $slug): Role
    {
        return Role::query()->create([
            'name' => ucwords(str_replace('-', ' ', $slug)),
            'slug' => $slug,
            'role_type' => 'platform',
            'tenant_id' => null,
            'description' => null,
            'is_system' => true,
            'is_active' => true,
        ]);
    }

    private function createPlatformUser(string $roleSlug): User
    {
        $role = $this->createPlatformRole($roleSlug);

        $user = User::factory()->create([
            'tenant_id' => null,
            'status' => 'active',
        ]);

        $user->roles()->attach($role->id);

        return $user;
    }

    private function createPlan(): Plan
    {
        return Plan::query()->create([
            'name' => 'Test Plan',
            'slug' => 'test-plan-' . uniqid(),
            'description' => 'Test plan',
            'monthly_price' => 499,
            'yearly_price' => 4999,
            'trial_days' => 14,
            'is_free' => false,
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    private function createFeature(
        string $name,
        bool $isActive = true
    ): Feature {
        return Feature::query()->create([
            'name' => $name,
            'slug' => strtolower(str_replace(' ', '-', $name)) . '-' . uniqid(),
            'category' => 'core',
            'description' => 'Test feature',
            'feature_type' => 'feature',
            'is_system' => true,
            'is_active' => $isActive,
            'sort_order' => 1,
        ]);
    }

    public function test_super_admin_can_view_plan_feature_assignment_page(): void
    {
        $user = $this->createPlatformUser('super-admin');
        $plan = $this->createPlan();
        $this->createFeature('Customer Management');

        $response = $this
            ->actingAs($user)
            ->get(route('admin.plans.features.edit', $plan));

        $response
            ->assertOk()
            ->assertViewIs('super-admin.plans.features')
            ->assertSee('Customer Management');
    }

    public function test_platform_admin_cannot_view_plan_feature_assignment_page(): void
    {
        $user = $this->createPlatformUser('platform-admin');
        $plan = $this->createPlan();

        $response = $this
            ->actingAs($user)
            ->get(route('admin.plans.features.edit', $plan));

        $response->assertForbidden();
    }

    public function test_super_admin_can_assign_active_features_to_plan(): void
    {
        $user = $this->createPlatformUser('super-admin');
        $plan = $this->createPlan();

        $featureOne = $this->createFeature('Customer Management');
        $featureTwo = $this->createFeature('Inventory Management');

        $response = $this
            ->actingAs($user)
            ->put(route('admin.plans.features.update', $plan), [
                'feature_ids' => [
                    $featureOne->id,
                    $featureTwo->id,
                ],
            ]);

        $response
            ->assertRedirect(route('admin.plans.show', $plan))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('plan_features', [
            'plan_id' => $plan->id,
            'feature_id' => $featureOne->id,
            'is_enabled' => 1,
        ]);

        $this->assertDatabaseHas('plan_features', [
            'plan_id' => $plan->id,
            'feature_id' => $featureTwo->id,
            'is_enabled' => 1,
        ]);
    }

    public function test_super_admin_can_remove_previously_assigned_features(): void
    {
        $user = $this->createPlatformUser('super-admin');
        $plan = $this->createPlan();

        $featureOne = $this->createFeature('Customer Management');
        $featureTwo = $this->createFeature('Inventory Management');

        $plan->features()->sync([
            $featureOne->id => ['is_enabled' => true],
            $featureTwo->id => ['is_enabled' => true],
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('admin.plans.features.update', $plan), [
                'feature_ids' => [
                    $featureOne->id,
                ],
            ]);

        $response->assertRedirect(route('admin.plans.show', $plan));

        $this->assertDatabaseHas('plan_features', [
            'plan_id' => $plan->id,
            'feature_id' => $featureOne->id,
            'is_enabled' => 1,
        ]);

        $this->assertDatabaseMissing('plan_features', [
            'plan_id' => $plan->id,
            'feature_id' => $featureTwo->id,
        ]);
    }

    public function test_inactive_feature_cannot_be_assigned_to_plan(): void
    {
        $user = $this->createPlatformUser('super-admin');
        $plan = $this->createPlan();

        $inactiveFeature = $this->createFeature(
            'Inactive Feature',
            false
        );

        $response = $this
            ->actingAs($user)
            ->put(route('admin.plans.features.update', $plan), [
                'feature_ids' => [
                    $inactiveFeature->id,
                ],
            ]);

        $response->assertSessionHasErrors('feature_ids');

        $this->assertDatabaseMissing('plan_features', [
            'plan_id' => $plan->id,
            'feature_id' => $inactiveFeature->id,
        ]);
    }

    public function test_guest_cannot_update_plan_features(): void
    {
        $plan = $this->createPlan();
        $feature = $this->createFeature('Customer Management');

        $response = $this->put(
            route('admin.plans.features.update', $plan),
            [
                'feature_ids' => [$feature->id],
            ]
        );

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('plan_features', [
            'plan_id' => $plan->id,
            'feature_id' => $feature->id,
        ]);
    }
}
