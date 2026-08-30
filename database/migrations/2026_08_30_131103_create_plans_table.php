<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->text('description')->nullable();

            /*
             * |--------------------------------------------------------------------------
             * | Pricing
             * |--------------------------------------------------------------------------
             */

            $table->decimal('monthly_price', 12, 2)->default(0);
            $table->decimal('yearly_price', 12, 2)->default(0);

            /*
             * |--------------------------------------------------------------------------
             * | Trial
             * |--------------------------------------------------------------------------
             */

            $table->unsignedInteger('trial_days')->default(0);

            /*
             * |--------------------------------------------------------------------------
             * | Plan Configuration
             * |--------------------------------------------------------------------------
             */

            $table->boolean('is_free')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
