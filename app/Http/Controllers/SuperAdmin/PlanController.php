<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Plan;
use App\Services\PlanService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function __construct(
        private readonly PlanService $planService
    ) {}

    public function index(): View
    {
        return view('super-admin.plans.index', [
            'plans' => $this->planService->paginate(),
        ]);
    }

    public function create(): View
    {
        return view('super-admin.plans.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                'unique:plans,slug',
            ],
            'description' => ['nullable', 'string'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'yearly_price' => ['required', 'numeric', 'min:0'],
            'trial_days' => ['required', 'integer', 'min:0'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_free' => ['nullable', 'boolean'],
            'is_popular' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_free'] = $request->boolean('is_free');
        $validated['is_popular'] = $request->boolean('is_popular');
        $validated['is_active'] = $request->boolean('is_active');

        $plan = $this->planService->create($validated);

        return redirect()
            ->route('admin.plans.show', $plan)
            ->with('success', 'Plan created successfully.');
    }

    public function edit(int $plan): View
    {
        return view('super-admin.plans.edit', [
            'plan' => $this->planService->find($plan),
        ]);
    }

    public function update(Request $request, int $plan): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                'unique:plans,slug,'.$plan,
            ],
            'description' => ['nullable', 'string'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'yearly_price' => ['required', 'numeric', 'min:0'],
            'trial_days' => ['required', 'integer', 'min:0'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_free' => ['nullable', 'boolean'],
            'is_popular' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_free'] = $request->boolean('is_free');
        $validated['is_popular'] = $request->boolean('is_popular');
        $validated['is_active'] = $request->boolean('is_active');

        $planModel = Plan::query()->findOrFail($plan);

        $this->planService->update($planModel, $validated);

        return redirect()
            ->route('admin.plans.show', $planModel)
            ->with('success', 'Plan updated successfully.');
    }

    public function show(int $plan): View
    {
        return view('super-admin.plans.show', [
            'plan' => $this->planService->find($plan),
        ]);
    }

    public function destroy(int $plan): RedirectResponse
    {
        $planModel = Plan::query()->findOrFail($plan);

        $this->planService->deactivate($planModel);

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plan deactivated successfully.');
    }

    public function editFeatures(int $plan): View
    {
        $planModel = $this->planService->find($plan);

        $features = Feature::query()
            ->where('is_active', true)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $selectedFeatureIds = $planModel
            ->features
            ->where('pivot.is_enabled', true)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        return view('super-admin.plans.features', [
            'plan' => $planModel,
            'features' => $features,
            'selectedFeatureIds' => $selectedFeatureIds,
        ]);
    }

    public function updateFeatures(Request $request, int $plan): RedirectResponse
    {
        $validated = $request->validate([
            'feature_ids' => ['nullable', 'array'],
            'feature_ids.*' => ['integer', 'exists:features,id'],
        ]);

        $planModel = Plan::query()->findOrFail($plan);

        $this->planService->syncFeatures(
            $planModel,
            $validated['feature_ids'] ?? []
        );

        return redirect()
            ->route('admin.plans.show', $planModel)
            ->with('success', 'Plan features updated successfully.');
    }
}
