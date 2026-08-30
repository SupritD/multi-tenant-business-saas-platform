<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('category');
            $table->text('description')->nullable();

            /*
             * |--------------------------------------------------------------------------
             * | Feature Type
             * |--------------------------------------------------------------------------
             * |
             * | module      = Complete business module
             * | feature     = Individual functionality
             * | integration = External integration
             * | report      = Reporting functionality
             * |
             */

            $table->string('feature_type')->default('feature');

            /*
             * |--------------------------------------------------------------------------
             * | System Feature
             * |--------------------------------------------------------------------------
             */

            $table->boolean('is_system')->default(true);

            /*
             * |--------------------------------------------------------------------------
             * | Status
             * |--------------------------------------------------------------------------
             */

            $table->boolean('is_active')->default(true);

            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->index('category');
            $table->index('feature_type');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
