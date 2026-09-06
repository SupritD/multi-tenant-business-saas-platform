<?php

use App\Http\Controllers\SuperAdmin\AuditLogController;
use App\Http\Controllers\SuperAdmin\FeatureController;
use App\Http\Controllers\SuperAdmin\NotificationController as SuperAdminNotificationController;
use App\Http\Controllers\SuperAdmin\PlanController;
use App\Http\Controllers\SuperAdmin\ReportController as SuperAdminReportController;
use App\Http\Controllers\SuperAdmin\SettingsController as SuperAdminSettingsController;
use App\Http\Controllers\SuperAdmin\SubscriptionController;
use App\Http\Controllers\SuperAdmin\SystemController;
use App\Http\Controllers\SuperAdmin\TenantController;
use App\Http\Controllers\SuperAdmin\UsageController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\AutomationController;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\HrController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TenantUserController;
use App\Http\Controllers\WorkflowController;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | Public
 * |--------------------------------------------------------------------------
 */

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
 * |--------------------------------------------------------------------------
 * | Authenticated Application
 * |--------------------------------------------------------------------------
 */

Route::middleware(['auth', 'verified'])->group(function () {
    /*
     * |--------------------------------------------------------------------------
     * | Dashboard
     * |--------------------------------------------------------------------------
     */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
     * |--------------------------------------------------------------------------
     * | Tenant Application
     * |--------------------------------------------------------------------------
     * |
     * | Every tenant module must pass:
     * |
     * | auth
     * | verified
     * | tenant.user
     * |
     */

    Route::middleware(['tenant.user'])->group(function () {
        /*
         * |--------------------------------------------------------------------------
         * | Customers
         * |--------------------------------------------------------------------------
         */

        Route::prefix('customers')
            ->middleware('feature:customer-management')
            ->name('customers.')
            ->group(function () {
                Route::get('/', [CustomerController::class, 'index'])
                    ->middleware('permission:customers.view')
                    ->name('index');

                Route::get('/create', [CustomerController::class, 'create'])
                    ->middleware('permission:customers.create')
                    ->name('create');

                Route::post('/', [CustomerController::class, 'store'])
                    ->middleware('permission:customers.create')
                    ->name('store');

                Route::get('/{customer}', [CustomerController::class, 'show'])
                    ->middleware('permission:customers.view')
                    ->whereNumber('customer')
                    ->name('show');

                Route::get('/{customer}/edit', [CustomerController::class, 'edit'])
                    ->middleware('permission:customers.update')
                    ->whereNumber('customer')
                    ->name('edit');

                Route::put('/{customer}', [CustomerController::class, 'update'])
                    ->middleware('permission:customers.update')
                    ->whereNumber('customer')
                    ->name('update');

                Route::delete('/{customer}', [CustomerController::class, 'destroy'])
                    ->middleware('permission:customers.delete')
                    ->whereNumber('customer')
                    ->name('destroy');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Users
         * |--------------------------------------------------------------------------
         */

        Route::prefix('users')
            ->name('users.')
            ->group(function () {
                Route::get('/', [TenantUserController::class, 'index'])
                    ->middleware('permission:users.view')
                    ->name('index');

                Route::get('/create', [TenantUserController::class, 'create'])
                    ->middleware('permission:users.create')
                    ->name('create');

                Route::post('/', [TenantUserController::class, 'store'])
                    ->middleware('permission:users.create')
                    ->name('store');

                Route::get('/{user}', [TenantUserController::class, 'show'])
                    ->middleware('permission:users.view')
                    ->whereNumber('user')
                    ->name('show');

                Route::get('/{user}/edit', [TenantUserController::class, 'edit'])
                    ->middleware('permission:users.update')
                    ->whereNumber('user')
                    ->name('edit');

                Route::put('/{user}', [TenantUserController::class, 'update'])
                    ->middleware('permission:users.update')
                    ->whereNumber('user')
                    ->name('update');

                Route::delete('/{user}', [TenantUserController::class, 'destroy'])
                    ->middleware('permission:users.delete')
                    ->whereNumber('user')
                    ->name('destroy');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Roles
         * |--------------------------------------------------------------------------
         */

        Route::prefix('roles')
            ->name('roles.')
            ->group(function () {
                Route::get('/', [RoleController::class, 'index'])
                    ->middleware('permission:roles.view')
                    ->name('index');

                Route::get('/create', [RoleController::class, 'create'])
                    ->middleware('permission:roles.create')
                    ->name('create');

                Route::post('/', [RoleController::class, 'store'])
                    ->middleware('permission:roles.create')
                    ->name('store');

                Route::get('/{role}', [RoleController::class, 'show'])
                    ->middleware('permission:roles.view')
                    ->whereNumber('role')
                    ->name('show');

                Route::get('/{role}/edit', [RoleController::class, 'edit'])
                    ->middleware('permission:roles.update')
                    ->whereNumber('role')
                    ->name('edit');

                Route::put('/{role}', [RoleController::class, 'update'])
                    ->middleware('permission:roles.update')
                    ->whereNumber('role')
                    ->name('update');

                Route::delete('/{role}', [RoleController::class, 'destroy'])
                    ->middleware('permission:roles.delete')
                    ->whereNumber('role')
                    ->name('destroy');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Organization
         * |--------------------------------------------------------------------------
         */

        Route::prefix('organization')
            ->middleware('feature:organization-management')
            ->name('organization.')
            ->group(function () {
                Route::get('/', [OrganizationController::class, 'index'])
                    ->middleware('permission:organization.view')
                    ->name('index');

                Route::get('/edit', [OrganizationController::class, 'edit'])
                    ->middleware('permission:organization.manage')
                    ->name('edit');

                Route::put('/', [OrganizationController::class, 'update'])
                    ->middleware('permission:organization.manage')
                    ->name('update');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Branches
         * |--------------------------------------------------------------------------
         */

        Route::prefix('branches')
            ->middleware('feature:branch-management')
            ->name('branches.')
            ->group(function () {
                Route::get('/', [OrganizationController::class, 'branches'])
                    ->middleware('permission:branches.view')
                    ->name('index');

                Route::get('/create', [OrganizationController::class, 'createBranch'])
                    ->middleware('permission:branches.create')
                    ->name('create');

                Route::post('/', [OrganizationController::class, 'storeBranch'])
                    ->middleware('permission:branches.create')
                    ->name('store');

                Route::get('/{branch}', [OrganizationController::class, 'showBranch'])
                    ->middleware('permission:branches.view')
                    ->whereNumber('branch')
                    ->name('show');

                Route::get('/{branch}/edit', [OrganizationController::class, 'editBranch'])
                    ->middleware('permission:branches.update')
                    ->whereNumber('branch')
                    ->name('edit');

                Route::put('/{branch}', [OrganizationController::class, 'updateBranch'])
                    ->middleware('permission:branches.update')
                    ->whereNumber('branch')
                    ->name('update');

                Route::delete('/{branch}', [OrganizationController::class, 'destroyBranch'])
                    ->middleware('permission:branches.delete')
                    ->whereNumber('branch')
                    ->name('destroy');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Departments
         * |--------------------------------------------------------------------------
         */

        Route::prefix('departments')
            ->middleware('feature:department-management')
            ->name('departments.')
            ->group(function () {
                Route::get('/', [OrganizationController::class, 'departments'])
                    ->middleware('permission:departments.view')
                    ->name('index');

                Route::get('/create', [OrganizationController::class, 'createDepartment'])
                    ->middleware('permission:departments.manage')
                    ->name('create');

                Route::post('/', [OrganizationController::class, 'storeDepartment'])
                    ->middleware('permission:departments.manage')
                    ->name('store');

                Route::get('/{department}', [OrganizationController::class, 'showDepartment'])
                    ->middleware('permission:departments.view')
                    ->whereNumber('department')
                    ->name('show');

                Route::get('/{department}/edit', [OrganizationController::class, 'editDepartment'])
                    ->middleware('permission:departments.manage')
                    ->whereNumber('department')
                    ->name('edit');

                Route::put('/{department}', [OrganizationController::class, 'updateDepartment'])
                    ->middleware('permission:departments.manage')
                    ->whereNumber('department')
                    ->name('update');

                Route::delete('/{department}', [OrganizationController::class, 'destroyDepartment'])
                    ->middleware('permission:departments.manage')
                    ->whereNumber('department')
                    ->name('destroy');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Teams
         * |--------------------------------------------------------------------------
         */

        Route::prefix('teams')
            ->middleware('feature:team-management')
            ->name('teams.')
            ->group(function () {
                Route::get('/', [OrganizationController::class, 'teams'])
                    ->middleware('permission:teams.view')
                    ->name('index');

                Route::get('/create', [OrganizationController::class, 'createTeam'])
                    ->middleware('permission:teams.manage')
                    ->name('create');

                Route::post('/', [OrganizationController::class, 'storeTeam'])
                    ->middleware('permission:teams.manage')
                    ->name('store');

                Route::get('/{team}', [OrganizationController::class, 'showTeam'])
                    ->middleware('permission:teams.view')
                    ->whereNumber('team')
                    ->name('show');

                Route::get('/{team}/edit', [OrganizationController::class, 'editTeam'])
                    ->middleware('permission:teams.manage')
                    ->whereNumber('team')
                    ->name('edit');

                Route::put('/{team}', [OrganizationController::class, 'updateTeam'])
                    ->middleware('permission:teams.manage')
                    ->whereNumber('team')
                    ->name('update');

                Route::delete('/{team}', [OrganizationController::class, 'destroyTeam'])
                    ->middleware('permission:teams.manage')
                    ->whereNumber('team')
                    ->name('destroy');
            });

        /*
         * |--------------------------------------------------------------------------
         * | HR
         * |--------------------------------------------------------------------------
         */

        Route::prefix('hr')
            ->name('hr.')
            ->group(function () {
                Route::get('/', [HrController::class, 'dashboard'])
                    ->middleware([
                        'feature:employee-management',
                        'permission:employees.view',
                    ])
                    ->name('dashboard');

                /*
                 * Employees
                 */
                Route::get('/employees', [HrController::class, 'employees'])
                    ->middleware([
                        'feature:employee-management',
                        'permission:employees.view',
                    ])
                    ->name('employees.index');

                Route::get('/employees/create', [HrController::class, 'createEmployee'])
                    ->middleware([
                        'feature:employee-management',
                        'permission:employees.create',
                    ])
                    ->name('employees.create');

                Route::post('/employees', [HrController::class, 'storeEmployee'])
                    ->middleware([
                        'feature:employee-management',
                        'permission:employees.create',
                    ])
                    ->name('employees.store');

                Route::get('/employees/{employee}', [HrController::class, 'showEmployee'])
                    ->middleware([
                        'feature:employee-management',
                        'permission:employees.view',
                    ])
                    ->whereNumber('employee')
                    ->name('employees.show');

                Route::get('/employees/{employee}/edit', [HrController::class, 'editEmployee'])
                    ->middleware([
                        'feature:employee-management',
                        'permission:employees.update',
                    ])
                    ->whereNumber('employee')
                    ->name('employees.edit');

                Route::put('/employees/{employee}', [HrController::class, 'updateEmployee'])
                    ->middleware([
                        'feature:employee-management',
                        'permission:employees.update',
                    ])
                    ->whereNumber('employee')
                    ->name('employees.update');

                Route::delete('/employees/{employee}', [HrController::class, 'destroyEmployee'])
                    ->middleware([
                        'feature:employee-management',
                        'permission:employees.delete',
                    ])
                    ->whereNumber('employee')
                    ->name('employees.destroy');

                /*
                 * Attendance
                 */
                Route::get('/attendance', [HrController::class, 'attendance'])
                    ->middleware([
                        'feature:attendance',
                        'permission:attendance.view',
                    ])
                    ->name('attendance.index');

                Route::post('/attendance', [HrController::class, 'manageAttendance'])
                    ->middleware([
                        'feature:attendance',
                        'permission:attendance.manage',
                    ])
                    ->name('attendance.manage');

                /*
                 * Leave
                 */
                Route::get('/leave', [HrController::class, 'leave'])
                    ->middleware([
                        'feature:leave-management',
                        'permission:leaves.view',
                    ])
                    ->name('leave.index');

                Route::post('/leave', [HrController::class, 'createLeave'])
                    ->middleware([
                        'feature:leave-management',
                        'permission:leaves.create',
                    ])
                    ->name('leave.store');

                Route::post('/leave/{leave}/approve', [HrController::class, 'approveLeave'])
                    ->middleware([
                        'feature:leave-management',
                        'permission:leaves.approve',
                    ])
                    ->whereNumber('leave')
                    ->name('leave.approve');

                /*
                 * Payroll
                 */
                Route::get('/payroll', [HrController::class, 'payroll'])
                    ->middleware([
                        'feature:payroll',
                        'permission:payroll.view',
                    ])
                    ->name('payroll.index');

                Route::post('/payroll', [HrController::class, 'managePayroll'])
                    ->middleware([
                        'feature:payroll',
                        'permission:payroll.manage',
                    ])
                    ->name('payroll.manage');

                /*
                 * Salary
                 */
                Route::get('/salary', [HrController::class, 'salary'])
                    ->middleware([
                        'feature:salary-management',
                        'permission:salary-structures.manage',
                    ])
                    ->name('salary.index');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Inventory
         * |--------------------------------------------------------------------------
         */

        Route::prefix('inventory')
            ->name('inventory.')
            ->group(function () {
                Route::get('/', [InventoryController::class, 'dashboard'])
                    ->middleware([
                        'feature:inventory-management',
                        'permission:inventory.view',
                    ])
                    ->name('dashboard');

                Route::get('/products', [InventoryController::class, 'products'])
                    ->middleware([
                        'feature:product-management',
                        'permission:products.view',
                    ])
                    ->name('products.index');

                Route::get('/products/create', [InventoryController::class, 'createProduct'])
                    ->middleware([
                        'feature:product-management',
                        'permission:products.create',
                    ])
                    ->name('products.create');

                Route::post('/products', [InventoryController::class, 'storeProduct'])
                    ->middleware([
                        'feature:product-management',
                        'permission:products.create',
                    ])
                    ->name('products.store');

                Route::get('/products/{product}', [InventoryController::class, 'showProduct'])
                    ->middleware([
                        'feature:product-management',
                        'permission:products.view',
                    ])
                    ->whereNumber('product')
                    ->name('products.show');

                Route::get('/products/{product}/edit', [InventoryController::class, 'editProduct'])
                    ->middleware([
                        'feature:product-management',
                        'permission:products.update',
                    ])
                    ->whereNumber('product')
                    ->name('products.edit');

                Route::put('/products/{product}', [InventoryController::class, 'updateProduct'])
                    ->middleware([
                        'feature:product-management',
                        'permission:products.update',
                    ])
                    ->whereNumber('product')
                    ->name('products.update');

                Route::delete('/products/{product}', [InventoryController::class, 'destroyProduct'])
                    ->middleware([
                        'feature:product-management',
                        'permission:products.delete',
                    ])
                    ->whereNumber('product')
                    ->name('products.destroy');

                Route::get('/categories', [InventoryController::class, 'categories'])
                    ->middleware([
                        'feature:category-management',
                        'permission:products.categories.manage',
                    ])
                    ->name('categories.index');

                Route::get('/warehouses', [InventoryController::class, 'warehouses'])
                    ->middleware([
                        'feature:inventory-management',
                        'permission:warehouses.view',
                    ])
                    ->name('warehouses.index');

                Route::get('/stock', [InventoryController::class, 'stock'])
                    ->middleware([
                        'feature:stock-management',
                        'permission:stock.view',
                    ])
                    ->name('stock.index');

                Route::post('/stock/adjust', [InventoryController::class, 'adjustStock'])
                    ->middleware([
                        'feature:stock-management',
                        'permission:stock.adjust',
                    ])
                    ->name('stock.adjust');

                Route::post('/stock/transfer', [InventoryController::class, 'transferStock'])
                    ->middleware([
                        'feature:stock-management',
                        'permission:stock.transfer',
                    ])
                    ->name('stock.transfer');

                Route::get('/movements', [InventoryController::class, 'movements'])
                    ->middleware([
                        'feature:stock-management',
                        'permission:stock.history.view',
                    ])
                    ->name('movements.index');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Purchase
         * |--------------------------------------------------------------------------
         */

        Route::prefix('purchase')
            ->middleware('feature:purchase-management')
            ->name('purchase.')
            ->group(function () {
                Route::get('/', [PurchaseController::class, 'dashboard'])
                    ->middleware('permission:purchases.view')
                    ->name('dashboard');

                Route::get('/requests', [PurchaseController::class, 'requests'])
                    ->middleware('permission:purchases.view')
                    ->name('requests.index');

                Route::post('/requests', [PurchaseController::class, 'createRequest'])
                    ->middleware('permission:purchases.create')
                    ->name('requests.store');

                Route::get('/orders', [PurchaseController::class, 'orders'])
                    ->middleware('permission:purchase-orders.manage')
                    ->name('orders.index');

                Route::post('/orders', [PurchaseController::class, 'manageOrder'])
                    ->middleware('permission:purchase-orders.manage')
                    ->name('orders.store');

                Route::get('/invoices', [PurchaseController::class, 'invoices'])
                    ->middleware('permission:purchase-invoices.manage')
                    ->name('invoices.index');

                Route::get('/returns', [PurchaseController::class, 'returns'])
                    ->middleware('permission:purchase-returns.manage')
                    ->name('returns.index');

                Route::get('/suppliers', [PurchaseController::class, 'suppliers'])
                    ->middleware([
                        'feature:supplier-management',
                        'permission:suppliers.view',
                    ])
                    ->name('suppliers.index');

                Route::get('/suppliers/create', [PurchaseController::class, 'createSupplier'])
                    ->middleware([
                        'feature:supplier-management',
                        'permission:suppliers.manage',
                    ])
                    ->name('suppliers.create');

                Route::post('/suppliers', [PurchaseController::class, 'storeSupplier'])
                    ->middleware([
                        'feature:supplier-management',
                        'permission:suppliers.manage',
                    ])
                    ->name('suppliers.store');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Sales
         * |--------------------------------------------------------------------------
         */

        Route::prefix('sales')
            ->middleware('feature:sales-management')
            ->name('sales.')
            ->group(function () {
                Route::get('/', [SalesController::class, 'dashboard'])
                    ->middleware('permission:sales.view')
                    ->name('dashboard');

                Route::get('/quotations', [SalesController::class, 'quotations'])
                    ->middleware('permission:sales.view')
                    ->name('quotations.index');

                Route::get('/orders', [SalesController::class, 'orders'])
                    ->middleware([
                        'feature:order-management',
                        'permission:orders.view',
                    ])
                    ->name('orders.index');

                Route::post('/orders', [SalesController::class, 'createOrder'])
                    ->middleware([
                        'feature:order-management',
                        'permission:orders.create',
                    ])
                    ->name('orders.store');

                Route::get('/invoices', [SalesController::class, 'invoices'])
                    ->middleware([
                        'feature:invoice-management',
                        'permission:invoices.view',
                    ])
                    ->name('invoices.index');

                Route::post('/invoices', [SalesController::class, 'createInvoice'])
                    ->middleware([
                        'feature:invoice-management',
                        'permission:invoices.create',
                    ])
                    ->name('invoices.store');

                Route::get('/payments', [SalesController::class, 'payments'])
                    ->middleware([
                        'feature:payment-management',
                        'permission:payments.view',
                    ])
                    ->name('payments.index');

                Route::post('/payments', [SalesController::class, 'managePayment'])
                    ->middleware([
                        'feature:payment-management',
                        'permission:payments.manage',
                    ])
                    ->name('payments.store');

                Route::get('/discounts', [SalesController::class, 'discounts'])
                    ->middleware([
                        'feature:discount-management',
                        'permission:discounts.view',
                    ])
                    ->name('discounts.index');

                Route::post('/discounts', [SalesController::class, 'manageDiscount'])
                    ->middleware([
                        'feature:discount-management',
                        'permission:discounts.manage',
                    ])
                    ->name('discounts.store');

                Route::get('/returns', [SalesController::class, 'returns'])
                    ->middleware([
                        'feature:order-management',
                        'permission:orders.return',
                    ])
                    ->name('returns.index');
            });

        /*
         * |--------------------------------------------------------------------------
         * | CRM
         * |--------------------------------------------------------------------------
         */

        Route::prefix('crm')
            ->middleware('feature:crm')
            ->name('crm.')
            ->group(function () {
                Route::get('/', [CrmController::class, 'dashboard'])
                    ->middleware('permission:leads.view')
                    ->name('dashboard');

                Route::get('/leads', [CrmController::class, 'leads'])
                    ->middleware('permission:leads.view')
                    ->name('leads.index');

                Route::get('/leads/create', [CrmController::class, 'createLead'])
                    ->middleware('permission:leads.create')
                    ->name('leads.create');

                Route::post('/leads', [CrmController::class, 'storeLead'])
                    ->middleware('permission:leads.create')
                    ->name('leads.store');

                Route::get('/leads/{lead}', [CrmController::class, 'showLead'])
                    ->middleware('permission:leads.view')
                    ->whereNumber('lead')
                    ->name('leads.show');

                Route::get('/leads/{lead}/edit', [CrmController::class, 'editLead'])
                    ->middleware('permission:leads.update')
                    ->whereNumber('lead')
                    ->name('leads.edit');

                Route::put('/leads/{lead}', [CrmController::class, 'updateLead'])
                    ->middleware('permission:leads.update')
                    ->whereNumber('lead')
                    ->name('leads.update');

                Route::delete('/leads/{lead}', [CrmController::class, 'destroyLead'])
                    ->middleware('permission:leads.delete')
                    ->whereNumber('lead')
                    ->name('leads.destroy');

                Route::get('/follow-ups', [CrmController::class, 'followUps'])
                    ->middleware('permission:followups.manage')
                    ->name('follow-ups.index');

                Route::get('/contacts', [CrmController::class, 'contacts'])
                    ->middleware('permission:customers.view')
                    ->name('contacts.index');

                Route::get('/activities', [CrmController::class, 'activities'])
                    ->middleware('permission:followups.manage')
                    ->name('activities.index');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Dealer
         * |--------------------------------------------------------------------------
         */

        Route::prefix('dealer')
            ->middleware('feature:dealer-management')
            ->name('dealer.')
            ->group(function () {
                Route::get('/', [DealerController::class, 'dashboard'])
                    ->middleware('permission:dealers.view')
                    ->name('dashboard');

                Route::get('/dealers', [DealerController::class, 'dealers'])
                    ->middleware('permission:dealers.view')
                    ->name('dealers.index');

                Route::get('/dealers/create', [DealerController::class, 'createDealer'])
                    ->middleware('permission:dealers.create')
                    ->name('dealers.create');

                Route::post('/dealers', [DealerController::class, 'storeDealer'])
                    ->middleware('permission:dealers.create')
                    ->name('dealers.store');

                Route::get('/dealers/{dealer}', [DealerController::class, 'showDealer'])
                    ->middleware('permission:dealers.view')
                    ->whereNumber('dealer')
                    ->name('dealers.show');

                Route::get('/dealers/{dealer}/edit', [DealerController::class, 'editDealer'])
                    ->middleware('permission:dealers.update')
                    ->whereNumber('dealer')
                    ->name('dealers.edit');

                Route::put('/dealers/{dealer}', [DealerController::class, 'updateDealer'])
                    ->middleware('permission:dealers.update')
                    ->whereNumber('dealer')
                    ->name('dealers.update');

                Route::delete('/dealers/{dealer}', [DealerController::class, 'destroyDealer'])
                    ->middleware('permission:dealers.delete')
                    ->whereNumber('dealer')
                    ->name('dealers.destroy');

                Route::get('/credits', [DealerController::class, 'credits'])
                    ->middleware('permission:dealers.orders.manage')
                    ->name('credits.index');

                Route::get('/transactions', [DealerController::class, 'transactions'])
                    ->middleware('permission:dealers.orders.manage')
                    ->name('transactions.index');

                Route::get('/statements', [DealerController::class, 'statements'])
                    ->middleware('permission:dealers.orders.manage')
                    ->name('statements.index');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Affiliate
         * |--------------------------------------------------------------------------
         */

        Route::prefix('affiliate')
            ->middleware('feature:affiliate-management')
            ->name('affiliate.')
            ->group(function () {
                Route::get('/', [AffiliateController::class, 'dashboard'])
                    ->middleware('permission:affiliates.view')
                    ->name('dashboard');

                Route::get('/affiliates', [AffiliateController::class, 'affiliates'])
                    ->middleware('permission:affiliates.view')
                    ->name('affiliates.index');

                Route::get('/affiliates/create', [AffiliateController::class, 'createAffiliate'])
                    ->middleware('permission:affiliates.create')
                    ->name('affiliates.create');

                Route::post('/affiliates', [AffiliateController::class, 'storeAffiliate'])
                    ->middleware('permission:affiliates.create')
                    ->name('affiliates.store');

                Route::get('/referrals', [AffiliateController::class, 'referrals'])
                    ->middleware('permission:affiliates.links.manage')
                    ->name('referrals.index');

                Route::get('/commissions', [AffiliateController::class, 'commissions'])
                    ->middleware([
                        'feature:affiliate-commission',
                        'permission:affiliates.commission.manage',
                    ])
                    ->name('commissions.index');

                Route::get('/payouts', [AffiliateController::class, 'payouts'])
                    ->middleware('permission:affiliates.payouts.manage')
                    ->name('payouts.index');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Finance
         * |--------------------------------------------------------------------------
         * |
         * | No generic "finance" feature exists in FeatureSeeder.
         * | Finance routes therefore use the individual feature that
         * | actually protects the underlying capability.
         * |
         */

        Route::prefix('finance')
            ->name('finance.')
            ->group(function () {
                Route::get('/', [FinanceController::class, 'dashboard'])
                    ->middleware([
                        'feature:dashboard-reports',
                        'permission:analytics.dashboard.view',
                    ])
                    ->name('dashboard');

                Route::get('/accounts', [FinanceController::class, 'accounts'])
                    ->middleware('permission:analytics.dashboard.view')
                    ->name('accounts.index');

                Route::get('/expenses', [FinanceController::class, 'expenses'])
                    ->middleware([
                        'feature:financial-reports',
                        'permission:expenses.view',
                    ])
                    ->name('expenses.index');

                Route::post('/expenses', [FinanceController::class, 'storeExpense'])
                    ->middleware([
                        'feature:financial-reports',
                        'permission:expenses.manage',
                    ])
                    ->name('expenses.store');

                Route::get('/income', [FinanceController::class, 'income'])
                    ->middleware([
                        'feature:financial-reports',
                        'permission:income.view',
                    ])
                    ->name('income.index');

                Route::post('/income', [FinanceController::class, 'storeIncome'])
                    ->middleware([
                        'feature:financial-reports',
                        'permission:income.manage',
                    ])
                    ->name('income.store');

                Route::get('/payments', [FinanceController::class, 'payments'])
                    ->middleware([
                        'feature:payment-management',
                        'permission:payments.view',
                    ])
                    ->name('payments.index');

                Route::get('/transactions', [FinanceController::class, 'transactions'])
                    ->middleware([
                        'feature:financial-reports',
                        'permission:analytics.dashboard.view',
                    ])
                    ->name('transactions.index');

                Route::get('/reports', [FinanceController::class, 'reports'])
                    ->middleware([
                        'feature:financial-reports',
                        'permission:analytics.dashboard.view',
                    ])
                    ->name('reports.index');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Notifications
         * |--------------------------------------------------------------------------
         */

        Route::prefix('notifications')
            ->middleware('feature:notifications')
            ->name('notifications.')
            ->group(function () {
                Route::get('/', [NotificationController::class, 'index'])
                    ->middleware('permission:notifications.view')
                    ->name('index');

                Route::post('/read/{notification}', [NotificationController::class, 'markAsRead'])
                    ->middleware('permission:notifications.view')
                    ->whereNumber('notification')
                    ->name('read');

                Route::get('/preferences', [NotificationController::class, 'preferences'])
                    ->middleware('permission:notifications.manage')
                    ->name('preferences');

                Route::put('/preferences', [NotificationController::class, 'updatePreferences'])
                    ->middleware('permission:notifications.manage')
                    ->name('preferences.update');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Workflows
         * |--------------------------------------------------------------------------
         * |
         * | Static routes MUST come before /{workflow}.
         * |
         */

        Route::prefix('workflows')
            ->name('workflows.')
            ->group(function () {
                Route::get('/approvals', [WorkflowController::class, 'approvals'])
                    ->middleware('permission:organization.manage')
                    ->name('approvals');

                Route::get('/my-approvals', [WorkflowController::class, 'myApprovals'])
                    ->middleware('permission:organization.manage')
                    ->name('my-approvals');

                Route::get('/', [WorkflowController::class, 'index'])
                    ->middleware('permission:organization.manage')
                    ->name('index');

                Route::get('/create', [WorkflowController::class, 'create'])
                    ->middleware('permission:organization.manage')
                    ->name('create');

                Route::post('/', [WorkflowController::class, 'store'])
                    ->middleware('permission:organization.manage')
                    ->name('store');

                Route::get('/{workflow}', [WorkflowController::class, 'show'])
                    ->middleware('permission:organization.manage')
                    ->whereNumber('workflow')
                    ->name('show');

                Route::get('/{workflow}/edit', [WorkflowController::class, 'edit'])
                    ->middleware('permission:organization.manage')
                    ->whereNumber('workflow')
                    ->name('edit');

                Route::put('/{workflow}', [WorkflowController::class, 'update'])
                    ->middleware('permission:organization.manage')
                    ->whereNumber('workflow')
                    ->name('update');

                Route::delete('/{workflow}', [WorkflowController::class, 'destroy'])
                    ->middleware('permission:organization.manage')
                    ->whereNumber('workflow')
                    ->name('destroy');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Documents
         * |--------------------------------------------------------------------------
         * |
         * | There is no documents feature in FeatureSeeder.
         * | Keep authorization at permission level until a dedicated
         * | documents feature is introduced.
         * |
         */

        Route::prefix('documents')
            ->name('documents.')
            ->group(function () {
                Route::get('/', [DocumentController::class, 'index'])
                    ->middleware('permission:employees.documents.manage')
                    ->name('index');

                Route::get('/{document}', [DocumentController::class, 'show'])
                    ->middleware('permission:employees.documents.manage')
                    ->whereNumber('document')
                    ->name('show');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Reports
         * |--------------------------------------------------------------------------
         */

        Route::prefix('reports')
            ->name('reports.')
            ->group(function () {
                Route::get('/', [ReportController::class, 'index'])
                    ->middleware([
                        'feature:dashboard-reports',
                        'permission:analytics.dashboard.view',
                    ])
                    ->name('index');

                Route::get('/sales', [ReportController::class, 'sales'])
                    ->middleware([
                        'feature:sales-reports',
                        'permission:analytics.sales.view',
                    ])
                    ->name('sales');

                Route::get('/purchase', [ReportController::class, 'purchase'])
                    ->middleware([
                        'feature:purchase-management',
                        'permission:purchases.view',
                    ])
                    ->name('purchase');

                Route::get('/inventory', [ReportController::class, 'inventory'])
                    ->middleware([
                        'feature:inventory-management',
                        'permission:analytics.inventory.view',
                    ])
                    ->name('inventory');

                Route::get('/finance', [ReportController::class, 'finance'])
                    ->middleware([
                        'feature:financial-reports',
                        'permission:analytics.dashboard.view',
                    ])
                    ->name('finance');

                Route::get('/hr', [ReportController::class, 'hr'])
                    ->middleware([
                        'feature:employee-reports',
                        'permission:analytics.employees.view',
                    ])
                    ->name('hr');

                Route::post('/export', [ReportController::class, 'export'])
                    ->middleware([
                        'feature:export-reports',
                        'permission:reports.export',
                    ])
                    ->name('export');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Support
         * |--------------------------------------------------------------------------
         */

        Route::prefix('support')
            ->middleware('feature:customer-service')
            ->name('support.')
            ->group(function () {
                Route::get('/', [SupportController::class, 'index'])
                    ->middleware('permission:support-tickets.view')
                    ->name('index');

                Route::get('/knowledge-base', [SupportController::class, 'knowledgeBase'])
                    ->middleware('permission:support-tickets.view')
                    ->name('knowledge-base');

                Route::get('/tickets', [SupportController::class, 'tickets'])
                    ->middleware('permission:support-tickets.view')
                    ->name('tickets.index');

                Route::get('/tickets/create', [SupportController::class, 'createTicket'])
                    ->middleware('permission:support-tickets.create')
                    ->name('tickets.create');

                Route::post('/tickets', [SupportController::class, 'storeTicket'])
                    ->middleware('permission:support-tickets.create')
                    ->name('tickets.store');

                Route::get('/tickets/{ticket}', [SupportController::class, 'showTicket'])
                    ->middleware('permission:support-tickets.view')
                    ->whereNumber('ticket')
                    ->name('tickets.show');

                Route::get('/tickets/{ticket}/edit', [SupportController::class, 'editTicket'])
                    ->middleware('permission:support-tickets.manage')
                    ->whereNumber('ticket')
                    ->name('tickets.edit');

                Route::put('/tickets/{ticket}', [SupportController::class, 'updateTicket'])
                    ->middleware('permission:support-tickets.manage')
                    ->whereNumber('ticket')
                    ->name('tickets.update');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Automation
         * |--------------------------------------------------------------------------
         * |
         * | No automation feature currently exists in FeatureSeeder.
         * |
         */

        Route::prefix('automation')
            ->name('automation.')
            ->group(function () {
                Route::get('/', [AutomationController::class, 'index'])
                    ->middleware('permission:organization.manage')
                    ->name('index');

                Route::get('/create', [AutomationController::class, 'create'])
                    ->middleware('permission:organization.manage')
                    ->name('create');

                Route::post('/', [AutomationController::class, 'store'])
                    ->middleware('permission:organization.manage')
                    ->name('store');

                Route::get('/logs', [AutomationController::class, 'logs'])
                    ->middleware('permission:activity-logs.view')
                    ->name('logs');

                Route::get('/{automation}/edit', [AutomationController::class, 'edit'])
                    ->middleware('permission:organization.manage')
                    ->whereNumber('automation')
                    ->name('edit');

                Route::put('/{automation}', [AutomationController::class, 'update'])
                    ->middleware('permission:organization.manage')
                    ->whereNumber('automation')
                    ->name('update');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Settings
         * |--------------------------------------------------------------------------
         * |
         * | No generic settings feature exists.
         * |
         */

        Route::prefix('settings')
            ->name('settings.')
            ->group(function () {
                Route::get('/', [SettingsController::class, 'index'])
                    ->middleware('permission:business.settings.manage')
                    ->name('index');

                Route::get('/company', [SettingsController::class, 'company'])
                    ->middleware('permission:business.settings.manage')
                    ->name('company');

                Route::put('/company', [SettingsController::class, 'updateCompany'])
                    ->middleware('permission:business.settings.manage')
                    ->name('company.update');

                Route::get('/branches', [SettingsController::class, 'branches'])
                    ->middleware('permission:branches.view')
                    ->name('branches');

                Route::get('/users', [SettingsController::class, 'users'])
                    ->middleware('permission:users.view')
                    ->name('users');

                Route::get('/notifications', [SettingsController::class, 'notifications'])
                    ->middleware('permission:notifications.manage')
                    ->name('notifications');

                Route::get('/security', [SettingsController::class, 'security'])
                    ->middleware('permission:security.2fa.manage')
                    ->name('security');

                Route::get('/integrations', [SettingsController::class, 'integrations'])
                    ->middleware('permission:api.keys.manage')
                    ->name('integrations');
            });
    });

    /*
     * |--------------------------------------------------------------------------
     * | Profile
     * |--------------------------------------------------------------------------
     */

    Route::prefix('profile')
        ->name('profile.')
        ->group(function () {
            Route::get('/', [ProfileController::class, 'edit'])
                ->name('edit');

            Route::patch('/', [ProfileController::class, 'update'])
                ->name('update');

            Route::delete('/', [ProfileController::class, 'destroy'])
                ->name('destroy');
        });
});

/*
 * |--------------------------------------------------------------------------
 * | Super Admin / Platform
 * |--------------------------------------------------------------------------
 * |
 * | IMPORTANT:
 * | EnsurePlatformUser will be implemented next.
 * |
 */

Route::middleware([
    'auth',
    'verified',
    'platform.user',
])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        /*
         * |--------------------------------------------------------------------------
         * | Dashboard
         * |--------------------------------------------------------------------------
         */

        Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])
            ->name('dashboard');

        /*
         * |--------------------------------------------------------------------------
         * | Tenants
         * |--------------------------------------------------------------------------
         */

        Route::middleware('platform.user:super-admin')
            ->prefix('tenants')
            ->name('tenants.')
            ->group(function () {
                Route::get('/', [TenantController::class, 'index'])
                    ->name('index');

                Route::get('/create', [TenantController::class, 'create'])
                    ->name('create');

                Route::post('/', [TenantController::class, 'store'])
                    ->name('store');

                Route::get('/{tenant}', [TenantController::class, 'show'])
                    ->whereNumber('tenant')
                    ->name('show');

                Route::get('/{tenant}/edit', [TenantController::class, 'edit'])
                    ->whereNumber('tenant')
                    ->name('edit');

                Route::get('/{tenant}/plan', [TenantController::class, 'editPlan'])
                    ->whereNumber('tenant')
                    ->name('plan.edit');

                Route::put('/{tenant}/plan', [TenantController::class, 'updatePlan'])
                    ->whereNumber('tenant')
                    ->name('plan.update');

                Route::put('/{tenant}', [TenantController::class, 'update'])
                    ->whereNumber('tenant')
                    ->name('update');

                Route::delete('/{tenant}', [TenantController::class, 'destroy'])
                    ->whereNumber('tenant')
                    ->name('destroy');

                Route::get('/{plan}', [PlanController::class, 'show'])
                    ->name('plans.show');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Plans
         * |--------------------------------------------------------------------------
         */

        Route::middleware('platform.user:super-admin')
            ->prefix('plans')
            ->name('plans.')
            ->group(function () {
                Route::get('/', [PlanController::class, 'index'])
                    ->name('index');

                Route::get('/create', [PlanController::class, 'create'])
                    ->name('create');

                Route::post('/', [PlanController::class, 'store'])
                    ->name('store');

                Route::get('/{plan}/features/edit', [PlanController::class, 'editFeatures'])
                    ->whereNumber('plan')
                    ->name('features.edit');

                Route::put('/{plan}/features', [PlanController::class, 'updateFeatures'])
                    ->whereNumber('plan')
                    ->name('features.update');

                Route::get('/{plan}', [PlanController::class, 'show'])
                    ->whereNumber('plan')
                    ->name('show');

                Route::get('/{plan}/edit', [PlanController::class, 'edit'])
                    ->whereNumber('plan')
                    ->name('edit');

                Route::put('/{plan}', [PlanController::class, 'update'])
                    ->whereNumber('plan')
                    ->name('update');

                Route::delete('/{plan}', [PlanController::class, 'destroy'])
                    ->whereNumber('plan')
                    ->name('destroy');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Subscriptions
         * |--------------------------------------------------------------------------
         */

        Route::middleware('platform.user:super-admin')
            ->prefix('subscriptions')
            ->name('subscriptions.')
            ->group(function () {
                Route::get('/', [SubscriptionController::class, 'index'])
                    ->name('index');

                Route::get('/{subscription}', [SubscriptionController::class, 'show'])
                    ->whereNumber('subscription')
                    ->name('show');

                Route::post('/{subscription}/suspend', [SubscriptionController::class, 'suspend'])
                    ->whereNumber('subscription')
                    ->name('suspend');

                Route::post('/{subscription}/activate', [SubscriptionController::class, 'activate'])
                    ->whereNumber('subscription')
                    ->name('activate');

                Route::post('/{subscription}/cancel', [SubscriptionController::class, 'cancel'])
                    ->whereNumber('subscription')
                    ->name('cancel');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Features
         * |--------------------------------------------------------------------------
         */

        Route::middleware('platform.user:super-admin')
            ->prefix('features')
            ->name('features.')
            ->group(function () {
                Route::get('/', [FeatureController::class, 'index'])
                    ->name('index');

                Route::get('/create', [FeatureController::class, 'create'])
                    ->name('create');

                Route::post('/', [FeatureController::class, 'store'])
                    ->name('store');

                Route::get('/tenant-overrides', [FeatureController::class, 'tenantOverrides'])
                    ->name('tenant-overrides');

                Route::get('/{feature}', [FeatureController::class, 'show'])
                    ->whereNumber('feature')
                    ->name('show');

                Route::get('/{feature}/edit', [FeatureController::class, 'edit'])
                    ->whereNumber('feature')
                    ->name('edit');

                Route::put('/{feature}', [FeatureController::class, 'update'])
                    ->whereNumber('feature')
                    ->name('update');

                Route::delete('/{feature}', [FeatureController::class, 'destroy'])
                    ->whereNumber('feature')
                    ->name('destroy');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Usage
         * |--------------------------------------------------------------------------
         */

        Route::middleware('platform.user:super-admin')
            ->prefix('usage')
            ->name('usage.')
            ->group(function () {
                Route::get('/', [UsageController::class, 'index'])
                    ->name('index');

                Route::get('/api', [UsageController::class, 'api'])
                    ->name('api');

                Route::get('/orders', [UsageController::class, 'orders'])
                    ->name('orders');

                Route::get('/storage', [UsageController::class, 'storage'])
                    ->name('storage');

                Route::get('/users', [UsageController::class, 'users'])
                    ->name('users');
            });

        /*
         * |--------------------------------------------------------------------------
         * | Audit Logs
         * |--------------------------------------------------------------------------
         */

        Route::get('/audit-logs', [AuditLogController::class, 'index'])
            ->name('audit-logs.index');

        /*
         * |--------------------------------------------------------------------------
         * | Notifications
         * |--------------------------------------------------------------------------
         */

        Route::get('/notifications', [SuperAdminNotificationController::class, 'index'])
            ->name('notifications.index');

        /*
         * |--------------------------------------------------------------------------
         * | Reports
         * |--------------------------------------------------------------------------
         */

        Route::get('/reports', [SuperAdminReportController::class, 'index'])
            ->name('reports.index');

        /*
         * |--------------------------------------------------------------------------
         * | Settings
         * |--------------------------------------------------------------------------
         */

        Route::middleware('platform.user:super-admin')
            ->group(function () {
                Route::get('/settings', [SuperAdminSettingsController::class, 'index'])
                    ->name('settings.index');
            });

        /*
         * |--------------------------------------------------------------------------
         * | System
         * |--------------------------------------------------------------------------
         */

        Route::middleware('platform.user:super-admin')
            ->prefix('system')
            ->name('system.')
            ->group(function () {
                Route::get('/health', [SystemController::class, 'health'])
                    ->name('health');

                Route::get('/queue', [SystemController::class, 'queue'])
                    ->name('queue');

                Route::get('/scheduled-jobs', [SystemController::class, 'scheduledJobs'])
                    ->name('scheduled-jobs');
            });
    });

/*
 * |--------------------------------------------------------------------------
 * | Authentication
 * |--------------------------------------------------------------------------
 */

require __DIR__ . '/auth.php';
