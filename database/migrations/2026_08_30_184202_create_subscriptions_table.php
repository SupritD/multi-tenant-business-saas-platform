<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            /*
             * Tenant
             */
            $table
                ->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();

            /*
             * Plan
             */
            $table
                ->foreignId('plan_id')
                ->constrained('plans')
                ->restrictOnDelete();

            /*
             * Subscription Status
             */
            $table->enum('status', [
                'trial',
                'active',
                'past_due',
                'cancelled',
                'expired',
            ])->default('active');

            /*
             * Billing Cycle
             */
            $table->enum('billing_cycle', [
                'monthly',
                'yearly',
            ])->default('monthly');

            /*
             * Subscription Dates
             */
            $table->timestamp('starts_at')->nullable();

            $table->timestamp('ends_at')->nullable();

            $table->timestamp('trial_ends_at')->nullable();

            $table->timestamp('cancelled_at')->nullable();

            /*
             * Auto Renewal
             */
            $table
                ->boolean('auto_renew')
                ->default(true);

            /*
             * External Payment Provider Reference
             */
            $table->string('external_subscription_id')->nullable()->unique();

            /*
             * Timestamps
             */
            $table->timestamp('created_at')->useCurrent();

            $table
                ->timestamp('updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();

            /*
             * Indexes
             */
            $table->index([
                'tenant_id',
                'status',
            ]);

            $table->index([
                'plan_id',
                'status',
            ]);

            $table->index([
                'tenant_id',
                'status',
                'ends_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
