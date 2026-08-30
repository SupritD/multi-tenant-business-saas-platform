<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            /*
             * |--------------------------------------------------------------------------
             * | Organization
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Organization Management',
                'slug' => 'organization-management',
                'category' => 'Organization',
                'feature_type' => 'module',
                'description' => 'Manage organization and business information.',
            ],
            [
                'name' => 'Branch Management',
                'slug' => 'branch-management',
                'category' => 'Organization',
                'feature_type' => 'module',
                'description' => 'Manage multiple business branches.',
            ],
            [
                'name' => 'Department Management',
                'slug' => 'department-management',
                'category' => 'Organization',
                'feature_type' => 'module',
                'description' => 'Manage departments within the organization.',
            ],
            [
                'name' => 'Team Management',
                'slug' => 'team-management',
                'category' => 'Organization',
                'feature_type' => 'module',
                'description' => 'Manage teams and team structures.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Employees & HR
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Employee Management',
                'slug' => 'employee-management',
                'category' => 'HR',
                'feature_type' => 'module',
                'description' => 'Manage employees and employee information.',
            ],
            [
                'name' => 'Attendance',
                'slug' => 'attendance',
                'category' => 'HR',
                'feature_type' => 'module',
                'description' => 'Track employee attendance.',
            ],
            [
                'name' => 'Leave Management',
                'slug' => 'leave-management',
                'category' => 'HR',
                'feature_type' => 'module',
                'description' => 'Manage employee leave and approvals.',
            ],
            [
                'name' => 'Payroll',
                'slug' => 'payroll',
                'category' => 'HR',
                'feature_type' => 'module',
                'description' => 'Manage employee salary and payroll.',
            ],
            [
                'name' => 'Salary Management',
                'slug' => 'salary-management',
                'category' => 'HR',
                'feature_type' => 'feature',
                'description' => 'Manage employee salary structures.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Inventory
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Inventory Management',
                'slug' => 'inventory-management',
                'category' => 'Inventory',
                'feature_type' => 'module',
                'description' => 'Manage inventory and stock.',
            ],
            [
                'name' => 'Stock Management',
                'slug' => 'stock-management',
                'category' => 'Inventory',
                'feature_type' => 'module',
                'description' => 'Track stock quantities and movements.',
            ],
            [
                'name' => 'Purchase Management',
                'slug' => 'purchase-management',
                'category' => 'Inventory',
                'feature_type' => 'module',
                'description' => 'Manage stock purchases.',
            ],
            [
                'name' => 'Supplier Management',
                'slug' => 'supplier-management',
                'category' => 'Inventory',
                'feature_type' => 'module',
                'description' => 'Manage suppliers and vendors.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Sales
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Sales Management',
                'slug' => 'sales-management',
                'category' => 'Sales',
                'feature_type' => 'module',
                'description' => 'Manage sales transactions.',
            ],
            [
                'name' => 'Order Management',
                'slug' => 'order-management',
                'category' => 'Sales',
                'feature_type' => 'module',
                'description' => 'Manage customer orders.',
            ],
            [
                'name' => 'Invoice Management',
                'slug' => 'invoice-management',
                'category' => 'Sales',
                'feature_type' => 'module',
                'description' => 'Create and manage invoices.',
            ],
            [
                'name' => 'Sales Reports',
                'slug' => 'sales-reports',
                'category' => 'Sales',
                'feature_type' => 'report',
                'description' => 'View sales performance reports.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Customers
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Customer Management',
                'slug' => 'customer-management',
                'category' => 'CRM',
                'feature_type' => 'module',
                'description' => 'Manage customers.',
            ],
            [
                'name' => 'Customer Service',
                'slug' => 'customer-service',
                'category' => 'CRM',
                'feature_type' => 'module',
                'description' => 'Manage customer service requests.',
            ],
            [
                'name' => 'CRM',
                'slug' => 'crm',
                'category' => 'CRM',
                'feature_type' => 'module',
                'description' => 'Manage customer relationships.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Dealers
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Dealer Management',
                'slug' => 'dealer-management',
                'category' => 'Dealers',
                'feature_type' => 'module',
                'description' => 'Manage dealers and dealer information.',
            ],
            [
                'name' => 'Dealer Orders',
                'slug' => 'dealer-orders',
                'category' => 'Dealers',
                'feature_type' => 'feature',
                'description' => 'Manage dealer orders.',
            ],
            [
                'name' => 'Dealer Reports',
                'slug' => 'dealer-reports',
                'category' => 'Dealers',
                'feature_type' => 'report',
                'description' => 'View dealer performance reports.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Affiliate
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Affiliate Management',
                'slug' => 'affiliate-management',
                'category' => 'Affiliate',
                'feature_type' => 'module',
                'description' => 'Manage affiliate partners.',
            ],
            [
                'name' => 'Affiliate Products',
                'slug' => 'affiliate-products',
                'category' => 'Affiliate',
                'feature_type' => 'feature',
                'description' => 'Manage affiliate products.',
            ],
            [
                'name' => 'Affiliate Commission',
                'slug' => 'affiliate-commission',
                'category' => 'Affiliate',
                'feature_type' => 'feature',
                'description' => 'Manage affiliate commissions.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Ecommerce
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Product Management',
                'slug' => 'product-management',
                'category' => 'Ecommerce',
                'feature_type' => 'module',
                'description' => 'Manage products.',
            ],
            [
                'name' => 'Category Management',
                'slug' => 'category-management',
                'category' => 'Ecommerce',
                'feature_type' => 'module',
                'description' => 'Manage product categories.',
            ],
            [
                'name' => 'Discount Management',
                'slug' => 'discount-management',
                'category' => 'Ecommerce',
                'feature_type' => 'feature',
                'description' => 'Manage discounts and promotional offers.',
            ],
            [
                'name' => 'Payment Management',
                'slug' => 'payment-management',
                'category' => 'Ecommerce',
                'feature_type' => 'module',
                'description' => 'Manage payments and payment methods.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Reports
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Dashboard Reports',
                'slug' => 'dashboard-reports',
                'category' => 'Reports',
                'feature_type' => 'report',
                'description' => 'View dashboard reports and metrics.',
            ],
            [
                'name' => 'Financial Reports',
                'slug' => 'financial-reports',
                'category' => 'Reports',
                'feature_type' => 'report',
                'description' => 'View financial reports.',
            ],
            [
                'name' => 'Employee Reports',
                'slug' => 'employee-reports',
                'category' => 'Reports',
                'feature_type' => 'report',
                'description' => 'View employee reports.',
            ],
            [
                'name' => 'Export Reports',
                'slug' => 'export-reports',
                'category' => 'Reports',
                'feature_type' => 'feature',
                'description' => 'Export reports and business data.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Notifications
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Notifications',
                'slug' => 'notifications',
                'category' => 'Communication',
                'feature_type' => 'feature',
                'description' => 'Application notifications.',
            ],
            [
                'name' => 'Email Notifications',
                'slug' => 'email-notifications',
                'category' => 'Communication',
                'feature_type' => 'integration',
                'description' => 'Send email notifications.',
            ],
            [
                'name' => 'SMS Notifications',
                'slug' => 'sms-notifications',
                'category' => 'Communication',
                'feature_type' => 'integration',
                'description' => 'Send SMS notifications.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Security
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Audit Logs',
                'slug' => 'audit-logs',
                'category' => 'Security',
                'feature_type' => 'feature',
                'description' => 'Track important system activities.',
            ],
            [
                'name' => 'Login Security',
                'slug' => 'login-security',
                'category' => 'Security',
                'feature_type' => 'feature',
                'description' => 'Manage login and account security.',
            ],
            [
                'name' => 'API Access',
                'slug' => 'api-access',
                'category' => 'Developer',
                'feature_type' => 'integration',
                'description' => 'Allow API access to the platform.',
            ],
        ];

        $now = now();

        foreach ($features as $index => $feature) {
            DB::table('features')->updateOrInsert(
                [
                    'slug' => $feature['slug'],
                ],
                [
                    'name' => $feature['name'],
                    'category' => $feature['category'],
                    'description' => $feature['description'],
                    'feature_type' => $feature['feature_type'],
                    'is_system' => true,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }
}
