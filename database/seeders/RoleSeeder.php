<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            /*
             * |--------------------------------------------------------------------------
             * | PLATFORM ROLES
             * |--------------------------------------------------------------------------
             */
            [
                'tenant_id' => null,
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full access to the entire SaaS platform and all tenants.',
                'role_type' => 'platform',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Platform Admin',
                'slug' => 'platform-admin',
                'description' => 'Manages platform operations, tenants and platform configuration.',
                'role_type' => 'platform',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Platform Support',
                'slug' => 'platform-support',
                'description' => 'Provides technical and operational support to tenants.',
                'role_type' => 'platform',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Platform Finance',
                'slug' => 'platform-finance',
                'description' => 'Manages SaaS subscriptions, billing, invoices and platform payments.',
                'role_type' => 'platform',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Platform Sales',
                'slug' => 'platform-sales',
                'description' => 'Manages platform customer acquisition and sales activities.',
                'role_type' => 'platform',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Platform Operations',
                'slug' => 'platform-operations',
                'description' => 'Manages tenant onboarding and platform operational activities.',
                'role_type' => 'platform',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Platform Analyst',
                'slug' => 'platform-analyst',
                'description' => 'Read-only access to platform analytics and reports.',
                'role_type' => 'platform',
                'is_system' => true,
                'is_active' => true,
            ],

            /*
             * |--------------------------------------------------------------------------
             * | TENANT / COMPANY ROLES
             * |--------------------------------------------------------------------------
             */
            [
                'tenant_id' => null,
                'name' => 'Tenant Admin',
                'slug' => 'tenant-admin',
                'description' => 'Full administrative access within a tenant/company.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Company Owner',
                'slug' => 'company-owner',
                'description' => 'Business owner with access to company-wide operations and reports.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'General Manager',
                'slug' => 'general-manager',
                'description' => 'Manages overall business operations across assigned areas.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Branch Manager',
                'slug' => 'branch-manager',
                'description' => 'Manages operations and employees within an assigned branch.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Department Manager',
                'slug' => 'department-manager',
                'description' => 'Manages an assigned department and its employees.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Team Leader',
                'slug' => 'team-leader',
                'description' => 'Manages an assigned team and monitors team performance.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],

            /*
             * |--------------------------------------------------------------------------
             * | HR ROLES
             * |--------------------------------------------------------------------------
             */
            [
                'tenant_id' => null,
                'name' => 'HR Manager',
                'slug' => 'hr-manager',
                'description' => 'Manages employees, attendance, leave, recruitment and HR operations.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'HR Executive',
                'slug' => 'hr-executive',
                'description' => 'Handles day-to-day HR and employee operations.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],

            /*
             * |--------------------------------------------------------------------------
             * | SALES ROLES
             * |--------------------------------------------------------------------------
             */
            [
                'tenant_id' => null,
                'name' => 'Sales Manager',
                'slug' => 'sales-manager',
                'description' => 'Manages sales teams, targets, customers, leads and sales performance.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Sales Executive',
                'slug' => 'sales-executive',
                'description' => 'Handles leads, customers, quotations and sales orders.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],

            /*
             * |--------------------------------------------------------------------------
             * | FINANCE ROLES
             * |--------------------------------------------------------------------------
             */
            [
                'tenant_id' => null,
                'name' => 'Finance Manager',
                'slug' => 'finance-manager',
                'description' => 'Manages company finance, accounting, payments and financial reports.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Finance Executive',
                'slug' => 'finance-executive',
                'description' => 'Handles payments, receipts, expenses and financial transactions.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Accountant',
                'slug' => 'accountant',
                'description' => 'Handles accounting entries, ledgers, invoices and financial records.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],

            /*
             * |--------------------------------------------------------------------------
             * | INVENTORY ROLES
             * |--------------------------------------------------------------------------
             */
            [
                'tenant_id' => null,
                'name' => 'Inventory Manager',
                'slug' => 'inventory-manager',
                'description' => 'Manages inventory, warehouses, stock movement and inventory reports.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Inventory Executive',
                'slug' => 'inventory-executive',
                'description' => 'Handles stock receiving, transfers, adjustments and inventory operations.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Warehouse Manager',
                'slug' => 'warehouse-manager',
                'description' => 'Manages warehouse operations and warehouse employees.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Store Keeper',
                'slug' => 'store-keeper',
                'description' => 'Handles physical stock receiving, issuing and storage operations.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],

            /*
             * |--------------------------------------------------------------------------
             * | PURCHASE ROLES
             * |--------------------------------------------------------------------------
             */
            [
                'tenant_id' => null,
                'name' => 'Purchase Manager',
                'slug' => 'purchase-manager',
                'description' => 'Manages procurement, suppliers, purchase orders and purchasing operations.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Purchase Executive',
                'slug' => 'purchase-executive',
                'description' => 'Handles suppliers, purchase orders, goods receipts and purchase transactions.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],

            /*
             * |--------------------------------------------------------------------------
             * | CUSTOMER SERVICE
             * |--------------------------------------------------------------------------
             */
            [
                'tenant_id' => null,
                'name' => 'Customer Service Manager',
                'slug' => 'customer-service-manager',
                'description' => 'Manages customer support teams, tickets, SLA and service performance.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Support Agent',
                'slug' => 'support-agent',
                'description' => 'Handles customer support tickets and service requests.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],

            /*
             * |--------------------------------------------------------------------------
             * | DEALER
             * |--------------------------------------------------------------------------
             */
            [
                'tenant_id' => null,
                'name' => 'Dealer Manager',
                'slug' => 'dealer-manager',
                'description' => 'Manages dealers, dealer orders, pricing, credit and dealer performance.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],

            /*
             * |--------------------------------------------------------------------------
             * | AFFILIATE
             * |--------------------------------------------------------------------------
             */
            [
                'tenant_id' => null,
                'name' => 'Affiliate Manager',
                'slug' => 'affiliate-manager',
                'description' => 'Manages affiliates, referrals, commissions and affiliate performance.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],

            /*
             * |--------------------------------------------------------------------------
             * | EMPLOYEE
             * |--------------------------------------------------------------------------
             */
            [
                'tenant_id' => null,
                'name' => 'Employee',
                'slug' => 'employee',
                'description' => 'Standard employee access based on assigned permissions and data scope.',
                'role_type' => 'tenant',
                'is_system' => true,
                'is_active' => true,
            ],

            /*
             * |--------------------------------------------------------------------------
             * | EXTERNAL ROLES
             * |--------------------------------------------------------------------------
             */
            [
                'tenant_id' => null,
                'name' => 'Customer',
                'slug' => 'customer',
                'description' => 'External customer with access to customer-facing services and portal.',
                'role_type' => 'external',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Dealer',
                'slug' => 'dealer',
                'description' => 'External dealer with access to dealer portal and dealer operations.',
                'role_type' => 'external',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Affiliate',
                'slug' => 'affiliate',
                'description' => 'External affiliate with access to referral, commission and performance information.',
                'role_type' => 'external',
                'is_system' => true,
                'is_active' => true,
            ],
            [
                'tenant_id' => null,
                'name' => 'Supplier',
                'slug' => 'supplier',
                'description' => 'External supplier with access to supplier-facing operations.',
                'role_type' => 'external',
                'is_system' => true,
                'is_active' => true,
            ],
        ];

        $now = now();

        foreach ($roles as &$role) {
            $role['created_at'] = $now;
            $role['updated_at'] = $now;
        }

        DB::table('roles')->upsert(
            $roles,
            ['tenant_id', 'slug'],
            [
                'name',
                'description',
                'role_type',
                'is_system',
                'is_active',
                'updated_at',
            ]
        );
    }
}
