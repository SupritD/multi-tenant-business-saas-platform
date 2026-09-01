<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();

            /*
             * |--------------------------------------------------------------------------
             * | Permission Information
             * |--------------------------------------------------------------------------
             */

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('module');
            $table->string('action');

            $table->text('description')->nullable();

            /*
             * |--------------------------------------------------------------------------
             * | Permission Type
             * |--------------------------------------------------------------------------
             * |
             * | system = Built-in platform permission
             * | custom = Custom permission
             * |
             */

            $table
                ->string('permission_type')
                ->default('system');

            /*
             * |--------------------------------------------------------------------------
             * | Status
             * |--------------------------------------------------------------------------
             */

            $table
                ->boolean('is_system')
                ->default(true);

            $table
                ->boolean('is_active')
                ->default(true);

            $table
                ->integer('sort_order')
                ->default(0);

            $table->timestamps();

            /*
             * |--------------------------------------------------------------------------
             * | Indexes
             * |--------------------------------------------------------------------------
             */

            $table->index('module');
            $table->index('action');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
