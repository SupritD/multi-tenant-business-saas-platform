<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $plans = DB::table('plans')
            ->get()
            ->keyBy('slug');

        $features = DB::table('features')
            ->get()
            ->keyBy('slug');

        /*
         * |--------------------------------------------------------------------------
         * | Free
         * |--------------------------------------------------------------------------
         */

        $freeFeatures = [
            'organization-management',
            'employee-management',
            'attendance',
            'customer-management',
            'product-management',
            'sales-management',
            'dashboard-reports',
        ];

        /*
         * |--------------------------------------------------------------------------
         * | Starter
         * |--------------------------------------------------------------------------
         */

        $starterFeatures = [
            ...$freeFeatures,
            'branch-management',
            'department-management',
            'team-management',
            'leave-management',
            'stock-management',
            'purchase-management',
            'supplier-management',
            'order-management',
            'invoice-management',
            'sales-reports',
            'notifications',
            'email-notifications',
        ];

        /*
         * |--------------------------------------------------------------------------
         * | Professional
         * |--------------------------------------------------------------------------
         */

        $professionalFeatures = [
            ...$starterFeatures,
            'payroll',
            'salary-management',
            'inventory-management',
            'crm',
            'customer-service',
            'dealer-management',
            'dealer-orders',
            'affiliate-management',
            'affiliate-products',
            'affiliate-commission',
            'category-management',
            'discount-management',
            'payment-management',
            'financial-reports',
            'employee-reports',
            'export-reports',
            'sms-notifications',
            'audit-logs',
        ];

        /*
         * |--------------------------------------------------------------------------
         * | Business
         * |--------------------------------------------------------------------------
         */

        $businessFeatures = [
            ...$professionalFeatures,
            'dealer-reports',
            'login-security',
            'api-access',
        ];

        /*
         * |--------------------------------------------------------------------------
         * | Enterprise
         * |--------------------------------------------------------------------------
         */

        $enterpriseFeatures = DB::table('features')
            ->pluck('slug')
            ->toArray();

        $planFeatures = [
            'free' => $freeFeatures,
            'starter' => $starterFeatures,
            'professional' => $professionalFeatures,
            'business' => $businessFeatures,
            'enterprise' => $enterpriseFeatures,
        ];

        $now = now();

        foreach ($planFeatures as $planSlug => $featureSlugs) {
            if (! isset($plans[$planSlug])) {
                continue;
            }

            foreach (array_unique($featureSlugs) as $featureSlug) {
                if (! isset($features[$featureSlug])) {
                    continue;
                }

                DB::table('plan_features')->updateOrInsert(
                    [
                        'plan_id' => $plans[$planSlug]->id,
                        'feature_id' => $features[$featureSlug]->id,
                    ],
                    [
                        'is_enabled' => true,
                        'limits' => null,
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                );
            }
        }
    }
}
