<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = DB::table('permissions')
            ->where('is_active', true)
            ->get()
            ->keyBy('slug');

        $roles = DB::table('roles')
            ->where('is_active', true)
            ->get();

        $now = now();

        foreach ($roles as $role) {
            $permissionSlugs = $this->permissionsForRole(
                $role->slug,
                $role->role_type
            );

            foreach ($permissionSlugs as $permissionSlug) {
                if (! isset($permissions[$permissionSlug])) {
                    continue;
                }

                DB::table('role_permissions')->updateOrInsert(
                    [
                        'role_id' => $role->id,
                        'permission_id' => $permissions[$permissionSlug]->id,
                    ],
                    [
                        'is_allowed' => true,
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                );
            }
        }
    }

    /**
     * Return permissions assigned to a role.
     *
     * @return array<int, string>
     */
    private function permissionsForRole(
        string $roleSlug,
        string $roleType
    ): array {
        /*
         * |--------------------------------------------------------------------------
         * | Platform / Super Admin
         * |--------------------------------------------------------------------------
         */

        if ($roleType === 'platform') {
            return DB::table('permissions')
                ->where('is_active', true)
                ->pluck('slug')
                ->toArray();
        }

        /*
         * |--------------------------------------------------------------------------
         * | Tenant Admin
         * |--------------------------------------------------------------------------
         */

        if (in_array($roleSlug, [
            'tenant-admin',
            'admin',
            'administrator',
        ], true)) {
            return DB::table('permissions')
                ->where('is_active', true)
                ->pluck('slug')
                ->toArray();
        }

        /*
         * |--------------------------------------------------------------------------
         * | Management Roles
         * |--------------------------------------------------------------------------
         */

        if (str_contains($roleSlug, 'manager')) {
            return $this->managerPermissions();
        }

        /*
         * |--------------------------------------------------------------------------
         * | Sales Roles
         * |--------------------------------------------------------------------------
         */

        if (
            str_contains($roleSlug, 'sales') ||
            str_contains($roleSlug, 'sales-executive')
        ) {
            return [
                'customers.view',
                'customers.create',
                'customers.update',
                'sales.view',
                'sales.create',
                'sales.update',
                'orders.view',
                'orders.create',
                'orders.update',
                'orders.cancel',
                'invoices.view',
                'invoices.create',
                'products.view',
                'analytics.dashboard.view',
                'analytics.sales.view',
                'notifications.view',
            ];
        }

        /*
         * |--------------------------------------------------------------------------
         * | HR Roles
         * |--------------------------------------------------------------------------
         */

        if (
            str_contains($roleSlug, 'hr') ||
            str_contains($roleSlug, 'human-resource')
        ) {
            return [
                'employees.view',
                'employees.create',
                'employees.update',
                'attendance.view',
                'attendance.manage',
                'leaves.view',
                'leaves.create',
                'leaves.approve',
                'shifts.view',
                'shifts.manage',
                'overtime.view',
                'overtime.manage',
                'notifications.view',
                'analytics.dashboard.view',
                'analytics.employees.view',
            ];
        }

        /*
         * |--------------------------------------------------------------------------
         * | Finance Roles
         * |--------------------------------------------------------------------------
         */

        if (
            str_contains($roleSlug, 'finance') ||
            str_contains($roleSlug, 'account')
        ) {
            return [
                'sales.view',
                'invoices.view',
                'invoices.create',
                'invoices.update',
                'payments.view',
                'payments.manage',
                'refunds.process',
                'expenses.view',
                'expenses.manage',
                'income.view',
                'income.manage',
                'taxes.view',
                'taxes.manage',
                'analytics.dashboard.view',
                'reports.export',
            ];
        }

        /*
         * |--------------------------------------------------------------------------
         * | Inventory Roles
         * |--------------------------------------------------------------------------
         */

        if (
            str_contains($roleSlug, 'inventory') ||
            str_contains($roleSlug, 'warehouse') ||
            str_contains($roleSlug, 'stock')
        ) {
            return [
                'inventory.view',
                'inventory.manage',
                'warehouses.view',
                'warehouses.manage',
                'stock.view',
                'stock.adjust',
                'stock.transfer',
                'stock.history.view',
                'products.view',
                'products.create',
                'products.update',
                'suppliers.view',
                'suppliers.manage',
                'purchases.view',
                'purchases.create',
                'purchases.update',
                'notifications.view',
                'analytics.inventory.view',
            ];
        }

        /*
         * |--------------------------------------------------------------------------
         * | Customer Service
         * |--------------------------------------------------------------------------
         */

        if (
            str_contains($roleSlug, 'customer-service') ||
            str_contains($roleSlug, 'support')
        ) {
            return [
                'customers.view',
                'customers.create',
                'customers.update',
                'support-tickets.view',
                'support-tickets.create',
                'support-tickets.manage',
                'support-tickets.assign',
                'notifications.view',
                'analytics.dashboard.view',
            ];
        }

        /*
         * |--------------------------------------------------------------------------
         * | Dealer
         * |--------------------------------------------------------------------------
         */

        if (str_contains($roleSlug, 'dealer')) {
            return [
                'dealers.view',
                'dealers.create',
                'dealers.update',
                'dealers.orders.manage',
                'dealers.pricing.manage',
                'products.view',
                'orders.view',
                'notifications.view',
            ];
        }

        /*
         * |--------------------------------------------------------------------------
         * | Affiliate
         * |--------------------------------------------------------------------------
         */

        if (str_contains($roleSlug, 'affiliate')) {
            return [
                'affiliates.view',
                'affiliates.create',
                'affiliates.update',
                'affiliates.products.manage',
                'affiliates.links.manage',
                'products.view',
                'notifications.view',
            ];
        }

        /*
         * |--------------------------------------------------------------------------
         * | Default Employee/User
         * |--------------------------------------------------------------------------
         */

        return [
            'analytics.dashboard.view',
            'notifications.view',
        ];
    }

    /**
     * Permissions for management roles.
     *
     * @return array<int, string>
     */
    private function managerPermissions(): array
    {
        return [
            'analytics.dashboard.view',
            'analytics.sales.view',
            'analytics.customers.view',
            'analytics.inventory.view',
            'analytics.employees.view',
            'analytics.team.view',
            'analytics.branch.view',
            'employees.view',
            'employees.create',
            'employees.update',
            'attendance.view',
            'attendance.manage',
            'leaves.view',
            'leaves.create',
            'leaves.approve',
            'customers.view',
            'customers.create',
            'customers.update',
            'products.view',
            'sales.view',
            'sales.create',
            'sales.update',
            'orders.view',
            'orders.create',
            'orders.update',
            'orders.cancel',
            'invoices.view',
            'invoices.create',
            'notifications.view',
            'reports.export',
        ];
    }
}
