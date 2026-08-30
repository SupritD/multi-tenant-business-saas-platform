<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            /*
             * |--------------------------------------------------------------------------
             * | Organization
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Organization',
                'slug' => 'organization.view',
                'module' => 'organization',
                'action' => 'view',
                'description' => 'View organization information.',
            ],
            [
                'name' => 'Manage Organization',
                'slug' => 'organization.manage',
                'module' => 'organization',
                'action' => 'manage',
                'description' => 'Create and update organization information.',
            ],
            [
                'name' => 'View Branches',
                'slug' => 'branches.view',
                'module' => 'branches',
                'action' => 'view',
                'description' => 'View business branches.',
            ],
            [
                'name' => 'Create Branches',
                'slug' => 'branches.create',
                'module' => 'branches',
                'action' => 'create',
                'description' => 'Create business branches.',
            ],
            [
                'name' => 'Update Branches',
                'slug' => 'branches.update',
                'module' => 'branches',
                'action' => 'update',
                'description' => 'Update business branches.',
            ],
            [
                'name' => 'Delete Branches',
                'slug' => 'branches.delete',
                'module' => 'branches',
                'action' => 'delete',
                'description' => 'Delete business branches.',
            ],
            [
                'name' => 'View Departments',
                'slug' => 'departments.view',
                'module' => 'departments',
                'action' => 'view',
                'description' => 'View departments.',
            ],
            [
                'name' => 'Manage Departments',
                'slug' => 'departments.manage',
                'module' => 'departments',
                'action' => 'manage',
                'description' => 'Create, update and delete departments.',
            ],
            [
                'name' => 'View Teams',
                'slug' => 'teams.view',
                'module' => 'teams',
                'action' => 'view',
                'description' => 'View teams.',
            ],
            [
                'name' => 'Manage Teams',
                'slug' => 'teams.manage',
                'module' => 'teams',
                'action' => 'manage',
                'description' => 'Create, update and manage teams.',
            ],
            [
                'name' => 'View Designations',
                'slug' => 'designations.view',
                'module' => 'designations',
                'action' => 'view',
                'description' => 'View employee designations.',
            ],
            [
                'name' => 'Manage Designations',
                'slug' => 'designations.manage',
                'module' => 'designations',
                'action' => 'manage',
                'description' => 'Manage employee designations.',
            ],
            [
                'name' => 'View Locations',
                'slug' => 'locations.view',
                'module' => 'locations',
                'action' => 'view',
                'description' => 'View business locations.',
            ],
            [
                'name' => 'Manage Locations',
                'slug' => 'locations.manage',
                'module' => 'locations',
                'action' => 'manage',
                'description' => 'Manage business locations.',
            ],
            [
                'name' => 'View Business Hours',
                'slug' => 'business-hours.view',
                'module' => 'business-hours',
                'action' => 'view',
                'description' => 'View business hours.',
            ],
            [
                'name' => 'Manage Business Hours',
                'slug' => 'business-hours.manage',
                'module' => 'business-hours',
                'action' => 'manage',
                'description' => 'Manage business hours.',
            ],
            [
                'name' => 'View Holidays',
                'slug' => 'holidays.view',
                'module' => 'holidays',
                'action' => 'view',
                'description' => 'View holidays.',
            ],
            [
                'name' => 'Manage Holidays',
                'slug' => 'holidays.manage',
                'module' => 'holidays',
                'action' => 'manage',
                'description' => 'Manage holidays.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Employees
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Employees',
                'slug' => 'employees.view',
                'module' => 'employees',
                'action' => 'view',
                'description' => 'View employees.',
            ],
            [
                'name' => 'Create Employees',
                'slug' => 'employees.create',
                'module' => 'employees',
                'action' => 'create',
                'description' => 'Create employees.',
            ],
            [
                'name' => 'Update Employees',
                'slug' => 'employees.update',
                'module' => 'employees',
                'action' => 'update',
                'description' => 'Update employee information.',
            ],
            [
                'name' => 'Delete Employees',
                'slug' => 'employees.delete',
                'module' => 'employees',
                'action' => 'delete',
                'description' => 'Delete employees.',
            ],
            [
                'name' => 'Manage Employee Documents',
                'slug' => 'employees.documents.manage',
                'module' => 'employees',
                'action' => 'documents.manage',
                'description' => 'Manage employee documents.',
            ],
            [
                'name' => 'Manage Employee Onboarding',
                'slug' => 'employees.onboarding.manage',
                'module' => 'employees',
                'action' => 'onboarding.manage',
                'description' => 'Manage employee onboarding.',
            ],
            [
                'name' => 'Manage Employee Offboarding',
                'slug' => 'employees.offboarding.manage',
                'module' => 'employees',
                'action' => 'offboarding.manage',
                'description' => 'Manage employee offboarding.',
            ],
            [
                'name' => 'Transfer Employees',
                'slug' => 'employees.transfer',
                'module' => 'employees',
                'action' => 'transfer',
                'description' => 'Transfer employees between branches or departments.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Attendance
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Attendance',
                'slug' => 'attendance.view',
                'module' => 'attendance',
                'action' => 'view',
                'description' => 'View employee attendance.',
            ],
            [
                'name' => 'Manage Attendance',
                'slug' => 'attendance.manage',
                'module' => 'attendance',
                'action' => 'manage',
                'description' => 'Create and update attendance records.',
            ],
            [
                'name' => 'Manage Attendance Rules',
                'slug' => 'attendance.rules.manage',
                'module' => 'attendance',
                'action' => 'rules.manage',
                'description' => 'Manage attendance rules.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Shifts
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Shifts',
                'slug' => 'shifts.view',
                'module' => 'shifts',
                'action' => 'view',
                'description' => 'View employee shifts.',
            ],
            [
                'name' => 'Manage Shifts',
                'slug' => 'shifts.manage',
                'module' => 'shifts',
                'action' => 'manage',
                'description' => 'Create and manage employee shifts.',
            ],
            [
                'name' => 'View Overtime',
                'slug' => 'overtime.view',
                'module' => 'overtime',
                'action' => 'view',
                'description' => 'View overtime records.',
            ],
            [
                'name' => 'Manage Overtime',
                'slug' => 'overtime.manage',
                'module' => 'overtime',
                'action' => 'manage',
                'description' => 'Manage employee overtime.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Leave
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Leaves',
                'slug' => 'leaves.view',
                'module' => 'leaves',
                'action' => 'view',
                'description' => 'View leave requests.',
            ],
            [
                'name' => 'Create Leave Request',
                'slug' => 'leaves.create',
                'module' => 'leaves',
                'action' => 'create',
                'description' => 'Create leave requests.',
            ],
            [
                'name' => 'Approve Leaves',
                'slug' => 'leaves.approve',
                'module' => 'leaves',
                'action' => 'approve',
                'description' => 'Approve or reject leave requests.',
            ],
            [
                'name' => 'Manage Leave Types',
                'slug' => 'leaves.types.manage',
                'module' => 'leaves',
                'action' => 'types.manage',
                'description' => 'Manage leave types.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Payroll
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Payroll',
                'slug' => 'payroll.view',
                'module' => 'payroll',
                'action' => 'view',
                'description' => 'View payroll information.',
            ],
            [
                'name' => 'Manage Payroll',
                'slug' => 'payroll.manage',
                'module' => 'payroll',
                'action' => 'manage',
                'description' => 'Process and manage payroll.',
            ],
            [
                'name' => 'Manage Salary Structures',
                'slug' => 'salary-structures.manage',
                'module' => 'payroll',
                'action' => 'salary-structures.manage',
                'description' => 'Manage salary structures.',
            ],
            [
                'name' => 'Manage Salary Components',
                'slug' => 'salary-components.manage',
                'module' => 'payroll',
                'action' => 'salary-components.manage',
                'description' => 'Manage salary components.',
            ],
            [
                'name' => 'Generate Payslips',
                'slug' => 'payslips.generate',
                'module' => 'payroll',
                'action' => 'generate',
                'description' => 'Generate employee payslips.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Inventory
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Inventory',
                'slug' => 'inventory.view',
                'module' => 'inventory',
                'action' => 'view',
                'description' => 'View inventory.',
            ],
            [
                'name' => 'Manage Inventory',
                'slug' => 'inventory.manage',
                'module' => 'inventory',
                'action' => 'manage',
                'description' => 'Manage inventory.',
            ],
            [
                'name' => 'View Warehouses',
                'slug' => 'warehouses.view',
                'module' => 'warehouses',
                'action' => 'view',
                'description' => 'View warehouses.',
            ],
            [
                'name' => 'Manage Warehouses',
                'slug' => 'warehouses.manage',
                'module' => 'warehouses',
                'action' => 'manage',
                'description' => 'Manage warehouses.',
            ],
            [
                'name' => 'View Stock',
                'slug' => 'stock.view',
                'module' => 'stock',
                'action' => 'view',
                'description' => 'View stock.',
            ],
            [
                'name' => 'Adjust Stock',
                'slug' => 'stock.adjust',
                'module' => 'stock',
                'action' => 'adjust',
                'description' => 'Adjust stock quantities.',
            ],
            [
                'name' => 'Transfer Stock',
                'slug' => 'stock.transfer',
                'module' => 'stock',
                'action' => 'transfer',
                'description' => 'Transfer stock between locations.',
            ],
            [
                'name' => 'View Stock History',
                'slug' => 'stock.history.view',
                'module' => 'stock',
                'action' => 'history.view',
                'description' => 'View stock movement history.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Products
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Products',
                'slug' => 'products.view',
                'module' => 'products',
                'action' => 'view',
                'description' => 'View products.',
            ],
            [
                'name' => 'Create Products',
                'slug' => 'products.create',
                'module' => 'products',
                'action' => 'create',
                'description' => 'Create products.',
            ],
            [
                'name' => 'Update Products',
                'slug' => 'products.update',
                'module' => 'products',
                'action' => 'update',
                'description' => 'Update products.',
            ],
            [
                'name' => 'Delete Products',
                'slug' => 'products.delete',
                'module' => 'products',
                'action' => 'delete',
                'description' => 'Delete products.',
            ],
            [
                'name' => 'Manage Product Variants',
                'slug' => 'products.variants.manage',
                'module' => 'products',
                'action' => 'variants.manage',
                'description' => 'Manage product variants.',
            ],
            [
                'name' => 'Manage Product Attributes',
                'slug' => 'products.attributes.manage',
                'module' => 'products',
                'action' => 'attributes.manage',
                'description' => 'Manage product attributes.',
            ],
            [
                'name' => 'Manage Product Categories',
                'slug' => 'products.categories.manage',
                'module' => 'products',
                'action' => 'categories.manage',
                'description' => 'Manage product categories.',
            ],
            [
                'name' => 'Manage Product Brands',
                'slug' => 'products.brands.manage',
                'module' => 'products',
                'action' => 'brands.manage',
                'description' => 'Manage product brands.',
            ],
            [
                'name' => 'Import Products',
                'slug' => 'products.import',
                'module' => 'products',
                'action' => 'import',
                'description' => 'Import products.',
            ],
            [
                'name' => 'Export Products',
                'slug' => 'products.export',
                'module' => 'products',
                'action' => 'export',
                'description' => 'Export products.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Suppliers & Purchasing
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Suppliers',
                'slug' => 'suppliers.view',
                'module' => 'suppliers',
                'action' => 'view',
                'description' => 'View suppliers.',
            ],
            [
                'name' => 'Manage Suppliers',
                'slug' => 'suppliers.manage',
                'module' => 'suppliers',
                'action' => 'manage',
                'description' => 'Manage suppliers.',
            ],
            [
                'name' => 'View Purchases',
                'slug' => 'purchases.view',
                'module' => 'purchases',
                'action' => 'view',
                'description' => 'View purchases.',
            ],
            [
                'name' => 'Create Purchases',
                'slug' => 'purchases.create',
                'module' => 'purchases',
                'action' => 'create',
                'description' => 'Create purchases.',
            ],
            [
                'name' => 'Update Purchases',
                'slug' => 'purchases.update',
                'module' => 'purchases',
                'action' => 'update',
                'description' => 'Update purchases.',
            ],
            [
                'name' => 'Delete Purchases',
                'slug' => 'purchases.delete',
                'module' => 'purchases',
                'action' => 'delete',
                'description' => 'Delete purchases.',
            ],
            [
                'name' => 'Manage Purchase Orders',
                'slug' => 'purchase-orders.manage',
                'module' => 'purchases',
                'action' => 'purchase-orders.manage',
                'description' => 'Manage purchase orders.',
            ],
            [
                'name' => 'Manage Purchase Invoices',
                'slug' => 'purchase-invoices.manage',
                'module' => 'purchases',
                'action' => 'purchase-invoices.manage',
                'description' => 'Manage purchase invoices.',
            ],
            [
                'name' => 'Manage Purchase Returns',
                'slug' => 'purchase-returns.manage',
                'module' => 'purchases',
                'action' => 'purchase-returns.manage',
                'description' => 'Manage purchase returns.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Sales & Orders
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Sales',
                'slug' => 'sales.view',
                'module' => 'sales',
                'action' => 'view',
                'description' => 'View sales.',
            ],
            [
                'name' => 'Create Sales',
                'slug' => 'sales.create',
                'module' => 'sales',
                'action' => 'create',
                'description' => 'Create sales.',
            ],
            [
                'name' => 'Update Sales',
                'slug' => 'sales.update',
                'module' => 'sales',
                'action' => 'update',
                'description' => 'Update sales.',
            ],
            [
                'name' => 'Delete Sales',
                'slug' => 'sales.delete',
                'module' => 'sales',
                'action' => 'delete',
                'description' => 'Delete sales.',
            ],
            [
                'name' => 'View Orders',
                'slug' => 'orders.view',
                'module' => 'orders',
                'action' => 'view',
                'description' => 'View customer orders.',
            ],
            [
                'name' => 'Create Orders',
                'slug' => 'orders.create',
                'module' => 'orders',
                'action' => 'create',
                'description' => 'Create orders.',
            ],
            [
                'name' => 'Update Orders',
                'slug' => 'orders.update',
                'module' => 'orders',
                'action' => 'update',
                'description' => 'Update orders.',
            ],
            [
                'name' => 'Cancel Orders',
                'slug' => 'orders.cancel',
                'module' => 'orders',
                'action' => 'cancel',
                'description' => 'Cancel customer orders.',
            ],
            [
                'name' => 'Return Orders',
                'slug' => 'orders.return',
                'module' => 'orders',
                'action' => 'return',
                'description' => 'Process order returns.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Invoices & Payments
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Invoices',
                'slug' => 'invoices.view',
                'module' => 'invoices',
                'action' => 'view',
                'description' => 'View invoices.',
            ],
            [
                'name' => 'Create Invoices',
                'slug' => 'invoices.create',
                'module' => 'invoices',
                'action' => 'create',
                'description' => 'Create invoices.',
            ],
            [
                'name' => 'Update Invoices',
                'slug' => 'invoices.update',
                'module' => 'invoices',
                'action' => 'update',
                'description' => 'Update invoices.',
            ],
            [
                'name' => 'Delete Invoices',
                'slug' => 'invoices.delete',
                'module' => 'invoices',
                'action' => 'delete',
                'description' => 'Delete invoices.',
            ],
            [
                'name' => 'View Payments',
                'slug' => 'payments.view',
                'module' => 'payments',
                'action' => 'view',
                'description' => 'View payments.',
            ],
            [
                'name' => 'Manage Payments',
                'slug' => 'payments.manage',
                'module' => 'payments',
                'action' => 'manage',
                'description' => 'Manage payments.',
            ],
            [
                'name' => 'Process Refunds',
                'slug' => 'refunds.process',
                'module' => 'payments',
                'action' => 'refund',
                'description' => 'Process payment refunds.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Customers & CRM
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Customers',
                'slug' => 'customers.view',
                'module' => 'customers',
                'action' => 'view',
                'description' => 'View customers.',
            ],
            [
                'name' => 'Create Customers',
                'slug' => 'customers.create',
                'module' => 'customers',
                'action' => 'create',
                'description' => 'Create customers.',
            ],
            [
                'name' => 'Update Customers',
                'slug' => 'customers.update',
                'module' => 'customers',
                'action' => 'update',
                'description' => 'Update customers.',
            ],
            [
                'name' => 'Delete Customers',
                'slug' => 'customers.delete',
                'module' => 'customers',
                'action' => 'delete',
                'description' => 'Delete customers.',
            ],
            [
                'name' => 'Manage Customer Groups',
                'slug' => 'customers.groups.manage',
                'module' => 'customers',
                'action' => 'groups.manage',
                'description' => 'Manage customer groups.',
            ],
            [
                'name' => 'Manage Customer Notes',
                'slug' => 'customers.notes.manage',
                'module' => 'customers',
                'action' => 'notes.manage',
                'description' => 'Manage customer notes.',
            ],
            [
                'name' => 'View Support Tickets',
                'slug' => 'support-tickets.view',
                'module' => 'support',
                'action' => 'view',
                'description' => 'View support tickets.',
            ],
            [
                'name' => 'Create Support Tickets',
                'slug' => 'support-tickets.create',
                'module' => 'support',
                'action' => 'create',
                'description' => 'Create support tickets.',
            ],
            [
                'name' => 'Manage Support Tickets',
                'slug' => 'support-tickets.manage',
                'module' => 'support',
                'action' => 'manage',
                'description' => 'Manage support tickets.',
            ],
            [
                'name' => 'Assign Support Tickets',
                'slug' => 'support-tickets.assign',
                'module' => 'support',
                'action' => 'assign',
                'description' => 'Assign support tickets to users.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | CRM Leads
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Leads',
                'slug' => 'leads.view',
                'module' => 'leads',
                'action' => 'view',
                'description' => 'View CRM leads.',
            ],
            [
                'name' => 'Create Leads',
                'slug' => 'leads.create',
                'module' => 'leads',
                'action' => 'create',
                'description' => 'Create CRM leads.',
            ],
            [
                'name' => 'Update Leads',
                'slug' => 'leads.update',
                'module' => 'leads',
                'action' => 'update',
                'description' => 'Update CRM leads.',
            ],
            [
                'name' => 'Delete Leads',
                'slug' => 'leads.delete',
                'module' => 'leads',
                'action' => 'delete',
                'description' => 'Delete CRM leads.',
            ],
            [
                'name' => 'Manage Lead Pipeline',
                'slug' => 'leads.pipeline.manage',
                'module' => 'leads',
                'action' => 'pipeline.manage',
                'description' => 'Manage lead pipelines.',
            ],
            [
                'name' => 'Manage Follow-ups',
                'slug' => 'followups.manage',
                'module' => 'leads',
                'action' => 'followups.manage',
                'description' => 'Manage CRM follow-ups.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Dealers
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Dealers',
                'slug' => 'dealers.view',
                'module' => 'dealers',
                'action' => 'view',
                'description' => 'View dealers.',
            ],
            [
                'name' => 'Create Dealers',
                'slug' => 'dealers.create',
                'module' => 'dealers',
                'action' => 'create',
                'description' => 'Create dealers.',
            ],
            [
                'name' => 'Update Dealers',
                'slug' => 'dealers.update',
                'module' => 'dealers',
                'action' => 'update',
                'description' => 'Update dealers.',
            ],
            [
                'name' => 'Delete Dealers',
                'slug' => 'dealers.delete',
                'module' => 'dealers',
                'action' => 'delete',
                'description' => 'Delete dealers.',
            ],
            [
                'name' => 'Manage Dealer Pricing',
                'slug' => 'dealers.pricing.manage',
                'module' => 'dealers',
                'action' => 'pricing.manage',
                'description' => 'Manage dealer pricing.',
            ],
            [
                'name' => 'Manage Dealer Orders',
                'slug' => 'dealers.orders.manage',
                'module' => 'dealers',
                'action' => 'orders.manage',
                'description' => 'Manage dealer orders.',
            ],
            [
                'name' => 'Manage Dealer Commission',
                'slug' => 'dealers.commission.manage',
                'module' => 'dealers',
                'action' => 'commission.manage',
                'description' => 'Manage dealer commissions.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Affiliates
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Affiliates',
                'slug' => 'affiliates.view',
                'module' => 'affiliates',
                'action' => 'view',
                'description' => 'View affiliates.',
            ],
            [
                'name' => 'Create Affiliates',
                'slug' => 'affiliates.create',
                'module' => 'affiliates',
                'action' => 'create',
                'description' => 'Create affiliates.',
            ],
            [
                'name' => 'Update Affiliates',
                'slug' => 'affiliates.update',
                'module' => 'affiliates',
                'action' => 'update',
                'description' => 'Update affiliates.',
            ],
            [
                'name' => 'Delete Affiliates',
                'slug' => 'affiliates.delete',
                'module' => 'affiliates',
                'action' => 'delete',
                'description' => 'Delete affiliates.',
            ],
            [
                'name' => 'Manage Affiliate Products',
                'slug' => 'affiliates.products.manage',
                'module' => 'affiliates',
                'action' => 'products.manage',
                'description' => 'Manage affiliate products.',
            ],
            [
                'name' => 'Manage Affiliate Links',
                'slug' => 'affiliates.links.manage',
                'module' => 'affiliates',
                'action' => 'links.manage',
                'description' => 'Manage affiliate links.',
            ],
            [
                'name' => 'Manage Affiliate Commission',
                'slug' => 'affiliates.commission.manage',
                'module' => 'affiliates',
                'action' => 'commission.manage',
                'description' => 'Manage affiliate commissions.',
            ],
            [
                'name' => 'Manage Affiliate Payouts',
                'slug' => 'affiliates.payouts.manage',
                'module' => 'affiliates',
                'action' => 'payouts.manage',
                'description' => 'Manage affiliate payouts.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Discounts
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Discounts',
                'slug' => 'discounts.view',
                'module' => 'discounts',
                'action' => 'view',
                'description' => 'View discounts.',
            ],
            [
                'name' => 'Manage Discounts',
                'slug' => 'discounts.manage',
                'module' => 'discounts',
                'action' => 'manage',
                'description' => 'Manage discounts.',
            ],
            [
                'name' => 'Manage Coupons',
                'slug' => 'coupons.manage',
                'module' => 'coupons',
                'action' => 'manage',
                'description' => 'Manage coupons.',
            ],
            [
                'name' => 'Manage Promotional Campaigns',
                'slug' => 'campaigns.manage',
                'module' => 'campaigns',
                'action' => 'manage',
                'description' => 'Manage promotional campaigns.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Finance
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Taxes',
                'slug' => 'taxes.view',
                'module' => 'finance',
                'action' => 'taxes.view',
                'description' => 'View tax configuration.',
            ],
            [
                'name' => 'Manage Taxes',
                'slug' => 'taxes.manage',
                'module' => 'finance',
                'action' => 'taxes.manage',
                'description' => 'Manage taxes.',
            ],
            [
                'name' => 'View Expenses',
                'slug' => 'expenses.view',
                'module' => 'finance',
                'action' => 'expenses.view',
                'description' => 'View expenses.',
            ],
            [
                'name' => 'Manage Expenses',
                'slug' => 'expenses.manage',
                'module' => 'finance',
                'action' => 'expenses.manage',
                'description' => 'Manage expenses.',
            ],
            [
                'name' => 'View Income',
                'slug' => 'income.view',
                'module' => 'finance',
                'action' => 'income.view',
                'description' => 'View income.',
            ],
            [
                'name' => 'Manage Income',
                'slug' => 'income.manage',
                'module' => 'finance',
                'action' => 'income.manage',
                'description' => 'Manage income.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Reports
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Dashboard Analytics',
                'slug' => 'analytics.dashboard.view',
                'module' => 'analytics',
                'action' => 'dashboard.view',
                'description' => 'View dashboard analytics.',
            ],
            [
                'name' => 'View Sales Analytics',
                'slug' => 'analytics.sales.view',
                'module' => 'analytics',
                'action' => 'sales.view',
                'description' => 'View sales analytics.',
            ],
            [
                'name' => 'View Customer Analytics',
                'slug' => 'analytics.customers.view',
                'module' => 'analytics',
                'action' => 'customers.view',
                'description' => 'View customer analytics.',
            ],
            [
                'name' => 'View Inventory Analytics',
                'slug' => 'analytics.inventory.view',
                'module' => 'analytics',
                'action' => 'inventory.view',
                'description' => 'View inventory analytics.',
            ],
            [
                'name' => 'View Employee Analytics',
                'slug' => 'analytics.employees.view',
                'module' => 'analytics',
                'action' => 'employees.view',
                'description' => 'View employee analytics.',
            ],
            [
                'name' => 'View Team Performance',
                'slug' => 'analytics.team.view',
                'module' => 'analytics',
                'action' => 'team.view',
                'description' => 'View team performance.',
            ],
            [
                'name' => 'View Branch Performance',
                'slug' => 'analytics.branch.view',
                'module' => 'analytics',
                'action' => 'branch.view',
                'description' => 'View branch performance.',
            ],
            [
                'name' => 'Create Custom Reports',
                'slug' => 'reports.custom.create',
                'module' => 'reports',
                'action' => 'custom.create',
                'description' => 'Create custom reports.',
            ],
            [
                'name' => 'Export Reports',
                'slug' => 'reports.export',
                'module' => 'reports',
                'action' => 'export',
                'description' => 'Export reports.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Communication
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Notifications',
                'slug' => 'notifications.view',
                'module' => 'notifications',
                'action' => 'view',
                'description' => 'View notifications.',
            ],
            [
                'name' => 'Manage Notifications',
                'slug' => 'notifications.manage',
                'module' => 'notifications',
                'action' => 'manage',
                'description' => 'Manage notifications.',
            ],
            [
                'name' => 'Manage Notification Templates',
                'slug' => 'notifications.templates.manage',
                'module' => 'notifications',
                'action' => 'templates.manage',
                'description' => 'Manage notification templates.',
            ],
            [
                'name' => 'Send Announcements',
                'slug' => 'announcements.send',
                'module' => 'announcements',
                'action' => 'send',
                'description' => 'Send announcements.',
            ],
            [
                'name' => 'Use Internal Messaging',
                'slug' => 'messaging.use',
                'module' => 'messaging',
                'action' => 'use',
                'description' => 'Use internal messaging.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Security & Access
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'View Users',
                'slug' => 'users.view',
                'module' => 'users',
                'action' => 'view',
                'description' => 'View users.',
            ],
            [
                'name' => 'Create Users',
                'slug' => 'users.create',
                'module' => 'users',
                'action' => 'create',
                'description' => 'Create users.',
            ],
            [
                'name' => 'Update Users',
                'slug' => 'users.update',
                'module' => 'users',
                'action' => 'update',
                'description' => 'Update users.',
            ],
            [
                'name' => 'Delete Users',
                'slug' => 'users.delete',
                'module' => 'users',
                'action' => 'delete',
                'description' => 'Delete users.',
            ],
            [
                'name' => 'View Roles',
                'slug' => 'roles.view',
                'module' => 'roles',
                'action' => 'view',
                'description' => 'View roles.',
            ],
            [
                'name' => 'Create Roles',
                'slug' => 'roles.create',
                'module' => 'roles',
                'action' => 'create',
                'description' => 'Create roles.',
            ],
            [
                'name' => 'Update Roles',
                'slug' => 'roles.update',
                'module' => 'roles',
                'action' => 'update',
                'description' => 'Update roles.',
            ],
            [
                'name' => 'Delete Roles',
                'slug' => 'roles.delete',
                'module' => 'roles',
                'action' => 'delete',
                'description' => 'Delete roles.',
            ],
            [
                'name' => 'Manage Permissions',
                'slug' => 'permissions.manage',
                'module' => 'permissions',
                'action' => 'manage',
                'description' => 'Manage role permissions.',
            ],
            [
                'name' => 'View Audit Logs',
                'slug' => 'audit-logs.view',
                'module' => 'security',
                'action' => 'audit-logs.view',
                'description' => 'View audit logs.',
            ],
            [
                'name' => 'View Activity Logs',
                'slug' => 'activity-logs.view',
                'module' => 'security',
                'action' => 'activity-logs.view',
                'description' => 'View activity logs.',
            ],
            [
                'name' => 'Manage Sessions',
                'slug' => 'sessions.manage',
                'module' => 'security',
                'action' => 'sessions.manage',
                'description' => 'Manage user sessions.',
            ],
            [
                'name' => 'Manage Two Factor Authentication',
                'slug' => 'security.2fa.manage',
                'module' => 'security',
                'action' => '2fa.manage',
                'description' => 'Manage two-factor authentication.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | Developer / API
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Use API',
                'slug' => 'api.use',
                'module' => 'api',
                'action' => 'use',
                'description' => 'Use platform API.',
            ],
            [
                'name' => 'Manage API Keys',
                'slug' => 'api.keys.manage',
                'module' => 'api',
                'action' => 'keys.manage',
                'description' => 'Manage API keys.',
            ],
            [
                'name' => 'Manage Webhooks',
                'slug' => 'api.webhooks.manage',
                'module' => 'api',
                'action' => 'webhooks.manage',
                'description' => 'Manage webhooks.',
            ],
            [
                'name' => 'Import Data',
                'slug' => 'data.import',
                'module' => 'data',
                'action' => 'import',
                'description' => 'Import business data.',
            ],

            /*
             * |--------------------------------------------------------------------------
             * | System
             * |--------------------------------------------------------------------------
             */
            [
                'name' => 'Manage System Settings',
                'slug' => 'system.settings.manage',
                'module' => 'system',
                'action' => 'settings.manage',
                'description' => 'Manage system settings.',
            ],
            [
                'name' => 'Manage Business Settings',
                'slug' => 'business.settings.manage',
                'module' => 'system',
                'action' => 'business.settings.manage',
                'description' => 'Manage business settings.',
            ],
            [
                'name' => 'Manage Email Settings',
                'slug' => 'email.settings.manage',
                'module' => 'system',
                'action' => 'email.settings.manage',
                'description' => 'Manage email settings.',
            ],
            [
                'name' => 'Manage Payment Gateway Settings',
                'slug' => 'payment-gateway.settings.manage',
                'module' => 'system',
                'action' => 'payment-gateway.settings.manage',
                'description' => 'Manage payment gateway settings.',
            ],
        ];

        $now = now();

        foreach ($permissions as $index => $permission) {
            DB::table('permissions')->updateOrInsert(
                [
                    'slug' => $permission['slug'],
                ],
                [
                    'name' => $permission['name'],
                    'module' => $permission['module'],
                    'action' => $permission['action'],
                    'description' => $permission['description'],
                    'permission_type' => 'system',
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
