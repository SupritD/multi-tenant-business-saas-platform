<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class CustomerService
{
    /**
     * Get all customers belonging to the user's tenant.
     *
     * @return Collection<int, Customer>
     */
    public function getForUser(User $user): Collection
    {
        $this->validateTenantUser($user);

        return Customer::query()
            ->where('tenant_id', $user->tenant_id)
            ->latest('id')
            ->get();
    }

    /**
     * Create a customer for the user's tenant.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(User $user, array $data): Customer
    {
        $this->validateTenantUser($user);

        return Customer::create([
            'tenant_id' => $user->tenant_id,
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'company' => $data['company'] ?? null,
            'address' => $data['address'] ?? null,
            'status' => $data['status'] ?? 'active',
        ]);
    }

    /**
     * Find a customer belonging to the user's tenant.
     */
    public function findForUser(User $user, int $customerId): Customer
    {
        $this->validateTenantUser($user);

        return Customer::query()
            ->where('tenant_id', $user->tenant_id)
            ->findOrFail($customerId);
    }

    /**
     * Update a customer belonging to the user's tenant.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(
        User $user,
        int $customerId,
        array $data
    ): Customer {
        $customer = $this->findForUser($user, $customerId);

        $customer->update([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'company' => $data['company'] ?? null,
            'address' => $data['address'] ?? null,
            'status' => $data['status'] ?? $customer->status,
        ]);

        return $customer->refresh();
    }

    /**
     * Delete a customer belonging to the user's tenant.
     */
    public function delete(User $user, int $customerId): void
    {
        $customer = $this->findForUser($user, $customerId);

        $customer->delete();
    }

    /**
     * Ensure the user is an active tenant user.
     */
    protected function validateTenantUser(User $user): void
    {
        if (
            $user->status !== 'active' ||
            $user->tenant_id === null
        ) {
            throw ValidationException::withMessages([
                'user' => 'An active tenant user is required.',
            ]);
        }
    }
}
