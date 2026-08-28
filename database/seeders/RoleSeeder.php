<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's roles.
     */
    public function run(): void
    {
        $now = now();

        /*
         * |--------------------------------------------------------------------------
         * | Platform Roles
         * |--------------------------------------------------------------------------
         * |
         * | These roles belong to the SaaS platform itself.
         * | They are not associated with any tenant.
         * |
         */

        $platformRoles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full access to the entire SaaS platform and all tenants.',
            ],
            [
                'name' => 'Platform Admin',
                'slug' => 'platform-admin',
                'description' => 'Manages platform configuration, tenants and platform operations.',
            ],
            [
                'name' => 'Platform Support',
                'slug' => 'platform-support',
                'description' => 'Provides technical and operational support to tenants.',
            ],
            [
                'name' => 'Platform Finance',
                'slug' => 'platform-finance',
                'description' => 'Manages SaaS subscriptions, billing, invoices and platform payments.',
            ],
            [
                'name' => 'Platform Sales',
                'slug' => 'platform-sales',
                'description' => 'Manages SaaS customer acquisition and sales activities.',
            ],
            [
                'name' => 'Platform Operations',
                'slug' => 'platform-operations',
                'description' => 'Manages tenant onboarding and platform operational activities.',
            ],
            [
                'name' => 'Platform Analyst',
                'slug' => 'platform-analyst',
                'description' => 'Read-only access to platform analytics and reports.',
            ],
        ];

        foreach ($platformRoles as $role) {
            DB::table('roles')->updateOrInsert(
                [
                    'tenant_id' => null,
                    'slug' => $role['slug'],
                ],
                [
                    'name' => $role['name'],
                    'description' => $role['description'],
                    'role_type' => 'platform',
                    'is_system' => true,
                    'is_active' => true,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        /*
         * |--------------------------------------------------------------------------
         * | Tenant Roles
         * |--------------------------------------------------------------------------
         * |
         * | These roles are created for every tenant.
         * |
         */

        $tenantRoles = [
            /*
             * |--------------------------------------------------------------------------
             * | Management
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Tenant Admin',
                'slug' => 'tenant-admin',
                'description' => 'Full administrative access within the tenant.',
            ],
            [
                'name' => 'Company Owner',
                'slug' => 'company-owner',
                'description' => 'Business owner with company-wide access.',
            ],
            [
                'name' => 'General Manager',
                'slug' => 'general-manager',
                'description' => 'Manages overall business operations.',
            ],
            [
                'name' => 'Branch Manager',
                'slug' => 'branch-manager',
                'description' => 'Manages operations within an assigned branch.',
            ],
            [
                'name' => 'Department Manager',
                'slug' => 'department-manager',
                'description' => 'Manages an assigned department and its employees.',
            ],
            [
                'name' => 'Team Leader',
                'slug' => 'team-leader',
                'description' => 'Manages an assigned team and monitors team performance.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | HR
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'HR Manager',
                'slug' => 'hr-manager',
                'description' => 'Manages employees, attendance, leave, recruitment and HR operations.',
            ],
            [
                'name' => 'HR Executive',
                'slug' => 'hr-executive',
                'description' => 'Handles day-to-day HR and employee operations.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Sales
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Sales Manager',
                'slug' => 'sales-manager',
                'description' => 'Manages sales teams, targets, customers, leads and sales performance.',
            ],
            [
                'name' => 'Sales Executive',
                'slug' => 'sales-executive',
                'description' => 'Handles leads, customers, quotations and sales orders.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | CRM
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'CRM Manager',
                'slug' => 'crm-manager',
                'description' => 'Manages CRM operations, leads, customers and customer relationships.',
            ],
            [
                'name' => 'CRM Executive',
                'slug' => 'crm-executive',
                'description' => 'Handles CRM activities, leads and customer records.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Finance
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Finance Manager',
                'slug' => 'finance-manager',
                'description' => 'Manages company finance, accounting, payments and financial reports.',
            ],
            [
                'name' => 'Finance Executive',
                'slug' => 'finance-executive',
                'description' => 'Handles payments, receipts, expenses and financial transactions.',
            ],
            [
                'name' => 'Accountant',
                'slug' => 'accountant',
                'description' => 'Handles accounting entries, ledgers, invoices and financial records.',
            ],
            [
                'name' => 'Cashier',
                'slug' => 'cashier',
                'description' => 'Handles cash transactions, collections and cash records.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Inventory
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Inventory Manager',
                'slug' => 'inventory-manager',
                'description' => 'Manages inventory, warehouses, stock movement and inventory reports.',
            ],
            [
                'name' => 'Inventory Executive',
                'slug' => 'inventory-executive',
                'description' => 'Handles stock receiving, transfers, adjustments and inventory operations.',
            ],
            [
                'name' => 'Warehouse Manager',
                'slug' => 'warehouse-manager',
                'description' => 'Manages warehouse operations and warehouse employees.',
            ],
            [
                'name' => 'Store Keeper',
                'slug' => 'store-keeper',
                'description' => 'Handles physical stock receiving, issuing and storage operations.',
            ],
            [
                'name' => 'Stock Auditor',
                'slug' => 'stock-auditor',
                'description' => 'Performs stock verification and inventory audits.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Purchase / Procurement
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Purchase Manager',
                'slug' => 'purchase-manager',
                'description' => 'Manages procurement, suppliers, purchase orders and purchasing operations.',
            ],
            [
                'name' => 'Purchase Executive',
                'slug' => 'purchase-executive',
                'description' => 'Handles suppliers, purchase orders and purchase transactions.',
            ],
            [
                'name' => 'Procurement Officer',
                'slug' => 'procurement-officer',
                'description' => 'Handles procurement activities and supplier coordination.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Customer Service
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Customer Service Manager',
                'slug' => 'customer-service-manager',
                'description' => 'Manages customer support teams, tickets, SLA and service performance.',
            ],
            [
                'name' => 'Support Team Leader',
                'slug' => 'support-team-leader',
                'description' => 'Manages customer support agents and team performance.',
            ],
            [
                'name' => 'Support Agent',
                'slug' => 'support-agent',
                'description' => 'Handles customer support tickets and service requests.',
            ],
            [
                'name' => 'Technical Support Agent',
                'slug' => 'technical-support-agent',
                'description' => 'Handles technical customer support issues.',
            ],
            [
                'name' => 'Warranty Executive',
                'slug' => 'warranty-executive',
                'description' => 'Handles warranty claims and warranty-related customer service.',
            ],
            [
                'name' => 'Returns Executive',
                'slug' => 'returns-executive',
                'description' => 'Handles product returns, exchanges and related operations.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Dealer
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Dealer Manager',
                'slug' => 'dealer-manager',
                'description' => 'Manages dealers, dealer orders, pricing and dealer performance.',
            ],
            [
                'name' => 'Dealer Executive',
                'slug' => 'dealer-executive',
                'description' => 'Handles dealer operations, orders and dealer communication.',
            ],
            [
                'name' => 'Dealer Support Executive',
                'slug' => 'dealer-support-executive',
                'description' => 'Provides support to dealers and handles dealer service requests.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Affiliate
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Affiliate Manager',
                'slug' => 'affiliate-manager',
                'description' => 'Manages affiliates, referrals, commissions and affiliate performance.',
            ],
            [
                'name' => 'Affiliate Executive',
                'slug' => 'affiliate-executive',
                'description' => 'Handles affiliate operations and affiliate communication.',
            ],
            [
                'name' => 'Affiliate Finance',
                'slug' => 'affiliate-finance',
                'description' => 'Manages affiliate commissions, payouts and financial records.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | E-Commerce
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'E-Commerce Manager',
                'slug' => 'e-commerce-manager',
                'description' => 'Manages overall e-commerce operations.',
            ],
            [
                'name' => 'Catalog Manager',
                'slug' => 'catalog-manager',
                'description' => 'Manages products, categories, attributes and catalog information.',
            ],
            [
                'name' => 'Product Manager',
                'slug' => 'product-manager',
                'description' => 'Manages product information, pricing and product lifecycle.',
            ],
            [
                'name' => 'Order Manager',
                'slug' => 'order-manager',
                'description' => 'Manages customer orders and order processing.',
            ],
            [
                'name' => 'Order Executive',
                'slug' => 'order-executive',
                'description' => 'Handles order processing and order operations.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Logistics / Delivery
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Logistics Manager',
                'slug' => 'logistics-manager',
                'description' => 'Manages logistics, shipping and delivery operations.',
            ],
            [
                'name' => 'Shipping Manager',
                'slug' => 'shipping-manager',
                'description' => 'Manages shipping operations and shipment processing.',
            ],
            [
                'name' => 'Delivery Manager',
                'slug' => 'delivery-manager',
                'description' => 'Manages delivery operations and delivery teams.',
            ],
            [
                'name' => 'Delivery Coordinator',
                'slug' => 'delivery-coordinator',
                'description' => 'Coordinates deliveries and delivery schedules.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Marketing
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Marketing Manager',
                'slug' => 'marketing-manager',
                'description' => 'Manages marketing campaigns and marketing operations.',
            ],
            [
                'name' => 'Marketing Executive',
                'slug' => 'marketing-executive',
                'description' => 'Handles marketing activities and campaign execution.',
            ],
            [
                'name' => 'Campaign Manager',
                'slug' => 'campaign-manager',
                'description' => 'Manages marketing and promotional campaigns.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Operations
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Operations Manager',
                'slug' => 'operations-manager',
                'description' => 'Manages day-to-day business operations.',
            ],
            [
                'name' => 'Operations Executive',
                'slug' => 'operations-executive',
                'description' => 'Handles daily operational activities.',
            ],
            [
                'name' => 'Field Supervisor',
                'slug' => 'field-supervisor',
                'description' => 'Manages field employees and field operations.',
            ],
            [
                'name' => 'Field Executive',
                'slug' => 'field-executive',
                'description' => 'Handles assigned field activities and tasks.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Assets
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Asset Manager',
                'slug' => 'asset-manager',
                'description' => 'Manages company assets and asset lifecycle.',
            ],
            [
                'name' => 'Asset Executive',
                'slug' => 'asset-executive',
                'description' => 'Handles asset assignment, tracking and records.',
            ],
            [
                'name' => 'Maintenance Manager',
                'slug' => 'maintenance-manager',
                'description' => 'Manages asset maintenance and maintenance operations.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Reports / Analytics
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Business Analyst',
                'slug' => 'business-analyst',
                'description' => 'Analyzes business performance and operational data.',
            ],
            [
                'name' => 'Data Analyst',
                'slug' => 'data-analyst',
                'description' => 'Analyzes business and operational datasets.',
            ],
            [
                'name' => 'Report Manager',
                'slug' => 'report-manager',
                'description' => 'Manages reports and reporting operations.',
            ],
            [
                'name' => 'Report Viewer',
                'slug' => 'report-viewer',
                'description' => 'Read-only access to permitted business reports.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | General Employee
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Employee',
                'slug' => 'employee',
                'description' => 'Standard employee access based on assigned permissions and data scope.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | External Users
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Customer',
                'slug' => 'customer',
                'description' => 'External customer access to customer-facing services and portal.',
            ],
            [
                'name' => 'Dealer',
                'slug' => 'dealer',
                'description' => 'External dealer access to dealer portal and dealer operations.',
            ],
            [
                'name' => 'Affiliate',
                'slug' => 'affiliate',
                'description' => 'External affiliate access to referrals, commissions and performance.',
            ],
            [
                'name' => 'Supplier',
                'slug' => 'supplier',
                'description' => 'External supplier access to supplier-facing operations.',
            ],
        ];

        /*
         * |--------------------------------------------------------------------------
         * | Development Tenants
         * |--------------------------------------------------------------------------
         * |
         * | Create the same default tenant roles for Tenant 1 and Tenant 2.
         * |
         */

        $tenantIds = [1, 2];

        foreach ($tenantIds as $tenantId) {
            foreach ($tenantRoles as $role) {
                DB::table('roles')->updateOrInsert(
                    [
                        'tenant_id' => $tenantId,
                        'slug' => $role['slug'],
                    ],
                    [
                        'name' => $role['name'],
                        'description' => $role['description'],
                        'role_type' => 'tenant',
                        'is_system' => true,
                        'is_active' => true,
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                );
            }
        }
    }
}
