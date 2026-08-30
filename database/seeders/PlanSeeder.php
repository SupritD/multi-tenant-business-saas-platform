<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Basic features for small businesses.',
                'monthly_price' => 0,
                'yearly_price' => 0,
                'trial_days' => 0,
                'is_free' => true,
                'is_popular' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Essential tools for growing businesses.',
                'monthly_price' => 999,
                'yearly_price' => 9990,
                'trial_days' => 14,
                'is_free' => false,
                'is_popular' => false,
                'sort_order' => 2,
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => 'Advanced business management features.',
                'monthly_price' => 2499,
                'yearly_price' => 24990,
                'trial_days' => 14,
                'is_free' => false,
                'is_popular' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Complete solution for established businesses.',
                'monthly_price' => 4999,
                'yearly_price' => 49990,
                'trial_days' => 14,
                'is_free' => false,
                'is_popular' => false,
                'sort_order' => 4,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'Full platform capabilities for large organizations.',
                'monthly_price' => 9999,
                'yearly_price' => 99990,
                'trial_days' => 30,
                'is_free' => false,
                'is_popular' => false,
                'sort_order' => 5,
            ],
        ];

        $now = now();

        foreach ($plans as $plan) {
            DB::table('plans')->updateOrInsert(
                [
                    'slug' => $plan['slug'],
                ],
                [
                    'name' => $plan['name'],
                    'description' => $plan['description'],
                    'monthly_price' => $plan['monthly_price'],
                    'yearly_price' => $plan['yearly_price'],
                    'trial_days' => $plan['trial_days'],
                    'is_free' => $plan['is_free'],
                    'is_popular' => $plan['is_popular'],
                    'is_active' => true,
                    'sort_order' => $plan['sort_order'],
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }
}
