<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tenant_features', function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();

            $table
                ->foreignId('feature_id')
                ->constrained('features')
                ->cascadeOnDelete();

            /*
             * |--------------------------------------------------------------------------
             * | Override
             * |--------------------------------------------------------------------------
             * |
             * | NULL  = follow plan
             * | true  = force enable
             * | false = force disable
             * |
             */

            $table->boolean('is_enabled')->nullable();

            /*
             * |--------------------------------------------------------------------------
             * | Custom Tenant Limits
             * |--------------------------------------------------------------------------
             */

            $table->json('limits')->nullable();

            $table->text('reason')->nullable();

            $table->timestamps();

            $table->unique(
                ['tenant_id', 'feature_id'],
                'tenant_features_tenant_feature_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_features');
    }
};
