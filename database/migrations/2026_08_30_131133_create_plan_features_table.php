<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plan_features', function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId('plan_id')
                ->constrained('plans')
                ->cascadeOnDelete();

            $table
                ->foreignId('feature_id')
                ->constrained('features')
                ->cascadeOnDelete();

            /*
             * |--------------------------------------------------------------------------
             * | Feature Access
             * |--------------------------------------------------------------------------
             */

            $table->boolean('is_enabled')->default(true);

            /*
             * |--------------------------------------------------------------------------
             * | Limits
             * |--------------------------------------------------------------------------
             * |
             * | Examples:
             * |
             * | {"employees": 50}
             * | {"branches": 5}
             * | {"storage_gb": 20}
             * |
             */

            $table->json('limits')->nullable();

            $table->timestamps();

            $table->unique(
                ['plan_id', 'feature_id'],
                'plan_features_plan_feature_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_features');
    }
};
