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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();

            /*
             * |--------------------------------------------------------------------------
             * | Tenant
             * |--------------------------------------------------------------------------
             * |
             * | NULL = Platform role
             * | Value = Tenant-specific role
             * |
             */

            $table
                ->foreignId('tenant_id')
                ->nullable()
                ->constrained('tenants')
                ->nullOnDelete();

            /*
             * |--------------------------------------------------------------------------
             * | Role Information
             * |--------------------------------------------------------------------------
             */

            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();

            /*
             * |--------------------------------------------------------------------------
             * | Role Type
             * |--------------------------------------------------------------------------
             * |
             * | platform = Platform role
             * | tenant   = Predefined tenant role
             * | custom   = Custom tenant role
             * |
             */

            $table
                ->string('role_type')
                ->default('custom');

            /*
             * |--------------------------------------------------------------------------
             * | System Role
             * |--------------------------------------------------------------------------
             * |
             * | true  = System/predefined role
             * | false = Custom role
             * |
             */

            $table
                ->boolean('is_system')
                ->default(false);

            /*
             * |--------------------------------------------------------------------------
             * | Status
             * |--------------------------------------------------------------------------
             */

            $table
                ->boolean('is_active')
                ->default(true);

            /*
             * |--------------------------------------------------------------------------
             * | Audit
             * |--------------------------------------------------------------------------
             * |
             * | This will be connected to users.id later.
             * |
             */

            $table
                ->unsignedBigInteger('created_by')
                ->nullable();

            $table->timestamps();

            /*
             * |--------------------------------------------------------------------------
             * | Unique Role Slug Per Tenant
             * |--------------------------------------------------------------------------
             */

            $table->unique(
                ['tenant_id', 'slug'],
                'roles_tenant_id_slug_unique'
            );

            /*
             * |--------------------------------------------------------------------------
             * | Indexes
             * |--------------------------------------------------------------------------
             */

            $table->index('role_type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
