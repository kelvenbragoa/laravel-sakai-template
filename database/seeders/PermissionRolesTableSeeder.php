<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('roles')->insert(
            [
                ['name' => 'Super Admin', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Admin', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Manager', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Security', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],

                ['name' => 'CGate1x-Terminal', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'CGate1x-General Cargo', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'CGate2x-Terminal', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'CGate2x-General Cargo', 'guard_name' => 'web', 'created_at' => now(), 'updated_at' => now()],
            ]
        );

        DB::table('permissions')->insert(
            [
                ['name' => 'create user','guard_name'=>'web', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'read user','guard_name'=>'web', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'update user','guard_name'=>'web', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'delete user','guard_name'=>'web', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'list user','guard_name'=>'web', 'created_at' => now(), 'updated_at' => now()],

                ['name' => 'create roles','guard_name'=>'web', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'read roles','guard_name'=>'web', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'update roles','guard_name'=>'web', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'delete roles','guard_name'=>'web', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'list roles','guard_name'=>'web', 'created_at' => now(), 'updated_at' => now()],

                ['name' => 'create permissions','guard_name'=>'web', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'read permissions','guard_name'=>'web', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'update permissions','guard_name'=>'web', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'delete permissions','guard_name'=>'web', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'list permissions','guard_name'=>'web', 'created_at' => now(), 'updated_at' => now()],
            ]);
    }
}
