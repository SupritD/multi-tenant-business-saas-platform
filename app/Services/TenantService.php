<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TenantService
{
    /**
     * Get paginated tenants for Super Admin management.
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Tenant::query()
            ->with([
                'activeSubscription.plan',
            ])
            ->withCount('users')
            ->latest('id')
            ->paginate($perPage);
    }

    /**
     * Find a tenant for management.
     */
    public function find(int $tenantId): Tenant
    {
        return Tenant::query()
            ->with([
                'users' => fn($query) => $query
                    ->with('roles')
                    ->latest('id'),
                'subscriptions.plan',
                'features',
            ])
            ->withCount('users')
            ->findOrFail($tenantId);
    }

    /**
     * Create a tenant.
     */
    public function create(array $data): Tenant
    {
        return DB::transaction(function () use ($data) {
            $data['slug'] = $this->makeUniqueSlug(
                $data['slug'] ?? $data['name']
            );

            return Tenant::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'status' => $data['status'] ?? 'active',
            ]);
        });
    }

    /**
     * Update a tenant.
     */
    public function update(Tenant $tenant, array $data): Tenant
    {
        return DB::transaction(function () use ($tenant, $data) {
            if (isset($data['slug'])) {
                $data['slug'] = $this->makeUniqueSlug(
                    $data['slug'],
                    $tenant->id
                );
            }

            $tenant->update([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'status' => $data['status'] ?? $tenant->status,
            ]);

            return $tenant->fresh([
                'activeSubscription.plan',
            ]);
        });
    }

    /**
     * Safely deactivate a tenant.
     *
     * We intentionally do not hard-delete tenant data.
     */
    public function deactivate(Tenant $tenant): Tenant
    {
        $tenant->update([
            'status' => 'inactive',
        ]);

        return $tenant->fresh();
    }

    /**
     * Generate a unique tenant slug.
     */
    private function makeUniqueSlug(string $value, ?int $ignoreTenantId = null): string
    {
        $slug = Str::slug($value);

        if ($slug === '') {
            throw ValidationException::withMessages([
                'slug' => 'A valid tenant slug could not be generated.',
            ]);
        }

        $baseSlug = $slug;
        $counter = 1;

        while (
            Tenant::query()
                ->where('slug', $slug)
                ->when(
                    $ignoreTenantId !== null,
                    fn($query) => $query->whereKey('!=', $ignoreTenantId)
                )
                ->exists()
        ) {
            $counter++;
            $slug = "{$baseSlug}-{$counter}";
        }

        return $slug;
    }
}
