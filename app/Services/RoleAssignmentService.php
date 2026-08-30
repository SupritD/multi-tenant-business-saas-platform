<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RoleAssignmentService
{
    /**
     * Assign a role to a user.
     */
    public function assignRole(User $user, Role $role): void
    {
        $this->validateAssignment($user, $role);

        if ($user->roles()->where('roles.id', $role->id)->exists()) {
            return;
        }

        $user->roles()->attach($role->id, [
            'assigned_at' => now(),
        ]);
    }

    /**
     * Remove a role from a user.
     */
    public function removeRole(User $user, Role $role): void
    {
        $user->roles()->detach($role->id);
    }

    /**
     * Check whether a role can be assigned to a user.
     */
    public function canAssign(User $user, Role $role): bool
    {
        try {
            $this->validateAssignment($user, $role);

            return true;
        } catch (ValidationException) {
            return false;
        }
    }

    /**
     * Validate user/role assignment.
     */
    protected function validateAssignment(User $user, Role $role): void
    {
        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'user' => 'Cannot assign a role to an inactive, blocked, or suspended user.',
            ]);
        }

        if (!$role->is_active) {
            throw ValidationException::withMessages([
                'role' => 'Cannot assign an inactive role.',
            ]);
        }

        /*
         * Platform user
         *
         * tenant_id = NULL
         *
         * Platform users may only receive platform roles.
         */
        if ($user->tenant_id === null) {
            if ($role->tenant_id !== null) {
                throw ValidationException::withMessages([
                    'role' => 'A platform user cannot receive a tenant role.',
                ]);
            }

            return;
        }

        /*
         * Tenant user
         *
         * tenant_id must match.
         */
        if ((int) $user->tenant_id !== (int) $role->tenant_id) {
            throw ValidationException::withMessages([
                'role' => 'A user can only be assigned a role from the same tenant.',
            ]);
        }
    }

    /**
     * Replace all roles for a user.
     */
    public function syncRoles(User $user, array $roleIds): void
    {
        $roles = Role::whereIn('id', $roleIds)->get();

        if ($roles->count() !== count(array_unique($roleIds))) {
            throw ValidationException::withMessages([
                'roles' => 'One or more selected roles do not exist.',
            ]);
        }

        foreach ($roles as $role) {
            $this->validateAssignment($user, $role);
        }

        DB::transaction(function () use ($user, $roles) {
            $syncData = [];

            foreach ($roles as $role) {
                $syncData[$role->id] = [
                    'assigned_at' => now(),
                ];
            }

            $user->roles()->sync($syncData);
        });
    }
}
