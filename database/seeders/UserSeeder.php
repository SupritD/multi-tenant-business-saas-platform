<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        /*
         * |--------------------------------------------------------------------------
         * | Platform Users
         * |--------------------------------------------------------------------------
         */

        $platformRoles = DB::table('roles')
            ->where('role_type', 'platform')
            ->whereNull('tenant_id')
            ->orderBy('id')
            ->get();

        foreach ($platformRoles as $role) {
            $email = $this->generateEmail(
                'platform',
                $role->slug
            );

            $userId = DB::table('users')->updateOrInsert(
                [
                    'email' => $email,
                ],
                [
                    'tenant_id' => null,
                    'name' => $role->name.' User',
                    'password' => Hash::make('Password@123'),
                    'status' => 'active',
                    'email_verified_at' => $now,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );

            /*
             * updateOrInsert() does not return the ID.
             * Fetch the user after inserting/updating.
             */

            $user = DB::table('users')
                ->where('email', $email)
                ->first();

            DB::table('user_roles')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'role_id' => $role->id,
                ],
                [
                    'assigned_at' => $now,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        /*
         * |--------------------------------------------------------------------------
         * | Tenant Users
         * |--------------------------------------------------------------------------
         */

        $tenantIds = [1, 2];

        foreach ($tenantIds as $tenantId) {
            $roles = DB::table('roles')
                ->where('tenant_id', $tenantId)
                ->where('role_type', 'tenant')
                ->orderBy('id')
                ->get();

            foreach ($roles as $role) {
                $email = $this->generateEmail(
                    "tenant{$tenantId}",
                    $role->slug
                );

                DB::table('users')->updateOrInsert(
                    [
                        'email' => $email,
                    ],
                    [
                        'tenant_id' => $tenantId,
                        'name' => $role->name.' User',
                        'password' => Hash::make('Password@123'),
                        'status' => 'active',
                        'email_verified_at' => $now,
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                );

                $user = DB::table('users')
                    ->where('email', $email)
                    ->first();

                DB::table('user_roles')->updateOrInsert(
                    [
                        'user_id' => $user->id,
                        'role_id' => $role->id,
                    ],
                    [
                        'assigned_at' => $now,
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                );
            }
        }
    }

    /**
     * Generate unique development email.
     */
    private function generateEmail(
        string $prefix,
        string $roleSlug
    ): string {
        return "{$prefix}.{$roleSlug}@example.com";
    }
}
