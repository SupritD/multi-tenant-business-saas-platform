<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();

            /*
             * |--------------------------------------------------------------------------
             * | User
             * |--------------------------------------------------------------------------
             */

            $table
                ->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
             * |--------------------------------------------------------------------------
             * | Role
             * |--------------------------------------------------------------------------
             */

            $table
                ->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnDelete();

            /*
             * |--------------------------------------------------------------------------
             * | Assignment Information
             * |--------------------------------------------------------------------------
             */

            $table
                ->timestamp('assigned_at')
                ->nullable();

            $table->timestamps();

            /*
             * |--------------------------------------------------------------------------
             * | Prevent Duplicate Role Assignment
             * |--------------------------------------------------------------------------
             */

            $table->unique([
                'user_id',
                'role_id',
            ]);

            /*
             * |--------------------------------------------------------------------------
             * | Indexes
             * |--------------------------------------------------------------------------
             */

            $table->index('user_id');
            $table->index('role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
