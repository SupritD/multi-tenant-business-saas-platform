<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $freePlan = DB::table('plans')
            ->where('slug', 'free')
            ->first();

        $professionalPlan = DB::table('plans')
            ->where('slug', 'professional')
            ->first();

        if (! $freePlan || ! $professionalPlan) {
            throw new \RuntimeException(
                'Required plans were not found. Run PlanSeeder first.'
            );
        }

        DB::table('subscriptions')->updateOrInsert(
            [
                'tenant_id' => 1,
            ],
            [
                'plan_id' => $freePlan->id,
                'status' => 'active',
                'billing_cycle' => 'monthly',
                'starts_at' => $now,
                'ends_at' => null,
                'trial_ends_at' => null,
                'cancelled_at' => null,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        DB::table('subscriptions')->updateOrInsert(
            [
                'tenant_id' => 2,
            ],
            [
                'plan_id' => $professionalPlan->id,
                'status' => 'active',
                'billing_cycle' => 'monthly',
                'starts_at' => $now,
                'ends_at' => null,
                'trial_ends_at' => null,
                'cancelled_at' => null,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );
    }
}
