<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TenantUserService
{
    public function __construct(
        protected RoleAssignmentService $roleAssignmentService
    ) {}

    /**
     * Get all users belonging to the authenticated user's tenant.
     *
     * @return Collection<int, User>
     */
    public function getUsers(User $authenticatedUser)
    {
        $this->validateTenantUser($authenticatedUser);

        return User::query()
            ->where('tenant_id', $authenticatedUser->tenant_id)
            ->with([
                'roles' => function ($query) {
                    $query->where('roles.is_active', true);
                },
            ])
            ->latest('id')
            ->get();
    }

    /**
     * Find a user belonging to the authenticated user's tenant.
     */
    public function getUser(
        User $authenticatedUser,
        int $userId
    ): User {
        $this->validateTenantUser($authenticatedUser);

        return User::query()
            ->where('tenant_id', $authenticatedUser->tenant_id)
            ->with('roles')
            ->findOrFail($userId);
    }

    /**
     * Create a new tenant user.
     *
     * @param  array{
     *     name: string,
     *     email: string,
     *     password: string,
     *     status?: string,
     *     role_ids?: array<int, int>
     * }  $data
     */
    public function create(
        User $authenticatedUser,
        array $data
    ): User {
        $this->validateTenantUser($authenticatedUser);

        $this->validateEmailAvailability(
            $data['email']
        );

        $roleIds = $data['role_ids'] ?? [];

        return DB::transaction(function () use (
            $authenticatedUser,
            $data,
            $roleIds
        ) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'status' => $data['status'] ?? 'active',
                'email_verified_at' => now(),
            ]);

            /*
             * tenant_id is intentionally not mass assignable.
             *
             * Set it explicitly after creation so it can never be
             * supplied or controlled by the request payload.
             */
            $user->tenant_id = $authenticatedUser->tenant_id;
            $user->save();

            $this->syncRoles($user, $roleIds);

            return $user->load('roles');
        });
    }

    /**
     * Update a tenant user.
     *
     * @param  array{
     *     name: string,
     *     email: string,
     *     password?: string,
     *     status?: string,
     *     role_ids?: array<int, int>
     * }  $data
     */
    public function update(
        User $authenticatedUser,
        int $userId,
        array $data
    ): User {
        $user = $this->getUser($authenticatedUser, $userId);

        if ($user->id === $authenticatedUser->id) {
            if (
                array_key_exists('status', $data) &&
                $data['status'] !== 'active'
            ) {
                throw ValidationException::withMessages([
                    'user' => 'You cannot deactivate or disable your own account.',
                ]);
            }

            if (array_key_exists('role_ids', $data)) {
                throw ValidationException::withMessages([
                    'roles' => 'You cannot change your own roles.',
                ]);
            }
        }

        $this->validateEmailAvailability(
            $data['email'],
            $user->id
        );

        return DB::transaction(function () use (
            $user,
            $data
        ) {
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'status' => $data['status'] ?? $user->status,
            ];

            if (
                isset($data['password']) &&
                $data['password'] !== ''
            ) {
                $updateData['password'] = $data['password'];
                $updateData['password_changed_at'] = now();
            }

            $user->update($updateData);

            if (array_key_exists('role_ids', $data)) {
                $this->syncRoles(
                    $user,
                    $data['role_ids']
                );
            }

            return $user->refresh()->load('roles');
        });
    }

    /**
     * Deactivate a tenant user.
     *
     * This intentionally does not delete the database record.
     */
    public function deactivate(
        User $authenticatedUser,
        int $userId
    ): void {
        $user = $this->getUser($authenticatedUser, $userId);

        /*
         * Prevent an administrator from accidentally removing their
         * own access to the tenant.
         */
        if ($user->id === $authenticatedUser->id) {
            throw ValidationException::withMessages([
                'user' => 'You cannot deactivate your own account.',
            ]);
        }

        $user->update([
            'status' => 'inactive',
        ]);
    }

    /**
     * Synchronize roles using the existing authorization service.
     *
     * @param  array<int, int>  $roleIds
     */
    protected function syncRoles(
        User $user,
        array $roleIds
    ): void {
        $this->roleAssignmentService->syncRoles(
            $user,
            array_map('intval', $roleIds)
        );
    }

    /**
     * Validate that the acting user is an active tenant user.
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

        $tenant = $user->tenant;

        if (! $tenant) {
            throw ValidationException::withMessages([
                'tenant' => 'Tenant not found.',
            ]);
        }

        if ($tenant->status !== 'active') {
            throw ValidationException::withMessages([
                'tenant' => 'Tenant is not active.',
            ]);
        }
    }

    /**
     * Ensure the email is not already used by another user.
     *
     * User emails are globally unique in the current schema, so this
     * check intentionally applies globally rather than per tenant.
     */
    protected function validateEmailAvailability(
        string $email,
        ?int $ignoreUserId = null
    ): void {
        $query = User::query()
            ->where('email', $email);

        if ($ignoreUserId !== null) {
            $query->where('id', '!=', $ignoreUserId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'email' => 'This email address is already in use.',
            ]);
        }
    }
}
