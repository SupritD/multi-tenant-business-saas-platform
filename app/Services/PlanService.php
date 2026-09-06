<?php

namespace App\Services;

use App\Models\Feature;
use App\Models\Plan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PlanService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Plan::query()
            ->withCount('subscriptions')
            ->with('features')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate($perPage);
    }

    public function find(int $planId): Plan
    {
        return Plan::query()
            ->with([
                'features',
                'subscriptions' => fn ($query) => $query
                    ->with('tenant')
                    ->latest('id'),
            ])
            ->withCount('subscriptions')
            ->findOrFail($planId);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Plan
    {
        $this->validateUniqueSlug($data['slug'] ?? null);

        return Plan::query()->create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'monthly_price' => $data['monthly_price'] ?? 0,
            'yearly_price' => $data['yearly_price'] ?? 0,
            'trial_days' => $data['trial_days'] ?? 0,
            'is_free' => $data['is_free'] ?? false,
            'is_popular' => $data['is_popular'] ?? false,
            'is_active' => $data['is_active'] ?? true,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Plan $plan, array $data): Plan
    {
        $this->validateUniqueSlug(
            $data['slug'] ?? null,
            $plan->id
        );

        $plan->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'monthly_price' => $data['monthly_price'] ?? 0,
            'yearly_price' => $data['yearly_price'] ?? 0,
            'trial_days' => $data['trial_days'] ?? 0,
            'is_free' => $data['is_free'] ?? false,
            'is_popular' => $data['is_popular'] ?? false,
            'is_active' => $data['is_active'] ?? true,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return $plan->fresh(['features']);
    }

    public function deactivate(Plan $plan): Plan
    {
        if ($plan->subscriptions()->whereIn('status', ['trial', 'active'])->exists()) {
            throw ValidationException::withMessages([
                'plan' => 'This plan cannot be deactivated while it has active subscriptions.',
            ]);
        }

        $plan->update([
            'is_active' => false,
        ]);

        return $plan->fresh();
    }

    /**
     * @param  array<int, int|string>  $featureIds
     */
    public function syncFeatures(Plan $plan, array $featureIds): Plan
    {
        return DB::transaction(function () use ($plan, $featureIds) {
            $featureIds = collect($featureIds)
                ->map(fn ($featureId) => (int) $featureId)
                ->unique()
                ->values();

            if ($featureIds->isNotEmpty()) {
                $validFeatureIds = Feature::query()
                    ->whereIn('id', $featureIds)
                    ->where('is_active', true)
                    ->pluck('id');

                if ($validFeatureIds->count() !== $featureIds->count()) {
                    throw ValidationException::withMessages([
                        'feature_ids' => 'One or more selected features are invalid or inactive.',
                    ]);
                }
            }

            $plan->features()->sync(
                $featureIds
                    ->mapWithKeys(fn ($featureId) => [
                        $featureId => [
                            'is_enabled' => true,
                        ],
                    ])
                    ->all()
            );

            return $plan->fresh(['features']);
        });
    }

    private function validateUniqueSlug(
        ?string $slug,
        ?int $ignorePlanId = null
    ): void {
        if ($slug === null) {
            return;
        }

        $query = Plan::query()
            ->where('slug', $slug);

        if ($ignorePlanId !== null) {
            $query->where('id', '!=', $ignorePlanId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'slug' => 'The slug has already been taken.',
            ]);
        }
    }
}
