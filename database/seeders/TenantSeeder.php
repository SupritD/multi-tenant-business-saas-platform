<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('tenants')->updateOrInsert(
            ['slug' => 'tenant-one'],
            [
                'name' => 'Tenant One',
                'email' => 'tenant1@example.com',
                'phone' => '9876543210',
                'status' => 'active',
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        DB::table('tenants')->updateOrInsert(
            ['slug' => 'tenant-two'],
            [
                'name' => 'Tenant Two',
                'email' => 'tenant2@example.com',
                'phone' => '9876543211',
                'status' => 'active',
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );
    }
}
