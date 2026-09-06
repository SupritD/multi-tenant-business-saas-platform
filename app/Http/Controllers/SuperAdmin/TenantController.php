<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Tenant;
use App\Services\SubscriptionService;
use App\Services\TenantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function __construct(
        private readonly TenantService $tenantService,
        private readonly SubscriptionService $subscriptionService
    ) {}

    public function index(): View
    {
        return view('super-admin.tenants.index', [
            'tenants' => $this->tenantService->paginate(),
        ]);
    }

    public function create(): View
    {
        return view('super-admin.tenants.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:active,inactive,suspended'],
        ]);

        $tenant = $this->tenantService->create($validated);

        return redirect()
            ->route('admin.tenants.show', $tenant)
            ->with('success', 'Tenant created successfully.');
    }

    public function show(int $tenant): View
    {
        return view('super-admin.tenants.show', [
            'tenant' => $this->tenantService->find($tenant),
        ]);
    }

    public function edit(int $tenant): View
    {
        return view('super-admin.tenants.edit', [
            'tenant' => $this->tenantService->find($tenant),
        ]);
    }

    public function update(
        Request $request,
        int $tenant
    ): RedirectResponse {
        $tenantModel = Tenant::query()->findOrFail($tenant);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:active,inactive,suspended'],
        ]);

        $this->tenantService->update(
            $tenantModel,
            $validated
        );

        return redirect()
            ->route('admin.tenants.show', $tenantModel)
            ->with('success', 'Tenant updated successfully.');
    }

    public function destroy(int $tenant): RedirectResponse
    {
        $tenantModel = Tenant::query()->findOrFail($tenant);

        $this->tenantService->deactivate($tenantModel);

        return redirect()
            ->route('admin.tenants.index')
            ->with('success', 'Tenant deactivated successfully.');
    }

    public function editPlan(int $tenant): View
    {
        $tenantModel = $this->tenantService->find($tenant);

        return view('super-admin.tenants.plan', [
            'tenant' => $tenantModel,
            'plans' => Plan::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
            'currentSubscription' => $this
                ->subscriptionService
                ->getActiveSubscription($tenantModel),
        ]);
    }

    public function updatePlan(
        Request $request,
        int $tenant
    ): RedirectResponse {
        $validated = $request->validate([
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'billing_cycle' => ['required', 'in:monthly,yearly'],
        ]);

        $tenantModel = $this->tenantService->find($tenant);

        $this->subscriptionService->subscribe(
            $tenantModel,
            (int) $validated['plan_id'],
            $validated['billing_cycle']
        );

        return redirect()
            ->route('admin.tenants.show', $tenantModel)
            ->with('success', 'Tenant plan updated successfully.');
    }
}
