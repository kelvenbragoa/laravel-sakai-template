<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('applications')->truncate();
        DB::table('application_permissions')->truncate();
        DB::table('application_has_permissions')->truncate();

        DB::table('applications')->insert([
            [
                // 'id' => 1,
                'name' => 'C-Gate 1.2',
                'version'=>'v1.2',
                'description'=>'C-Gate 1.2',
                'created_by'=>'Admin',
                'updated_by'=>'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 2,
                'name' => 'C-Gate 2.0 Container Terminal',
                'version'=>'v2.0',
                'description'=>'C-Gate 2.0',
                'created_by'=>'Admin',
                'updated_by'=>'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 3,
                'name' => 'C-Gate 2.0 General Cargo',
                'version'=>'v2.0',
                'description'=>'C-Gate 2.0',
                'created_by'=>'Admin',
                'updated_by'=>'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 4,
                'name' => 'Pre-Check',
                'version'=>'v1.0',
                'description'=>'Pre-Check 1.0',
                'created_by'=>'Admin',
                'updated_by'=>'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
           
            
        ]);

        DB::table('application_permissions')->insert([
            [
                // 'id' => 1,
                'name' => 'Security Check In',
                'created_by'=>'Admin',
                'updated_by'=>'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 2,
                'name' => 'Security Check Out',
                'created_by'=>'Admin',
                'updated_by'=>'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 3,
                'name' => 'Tally In',
                'created_by'=>'Admin',
                'updated_by'=>'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 4,
                'name' => 'Tally Out',
                'created_by'=>'Admin',
                'updated_by'=>'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 5,
                'name' => 'Create General Cargo Transaction',
                'created_by'=>'Admin',
                'updated_by'=>'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 6,
                'name' => 'Check Appointment',
                'created_by'=>'Admin',
                'updated_by'=>'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
  
        ]);


        DB::table('application_has_permissions')->insert([
            [
                // 'id' => 1,
                'application_id' => 1,
                'application_permission_id' => 1,
                'created_by'=>'Admin',
                'updated_by'=>'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 2,
                'application_id' => 1,
                'application_permission_id' => 2,
                'created_by'=>'Admin',
                'updated_by'=>'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 3,
                'application_id' => 2,
                'application_permission_id' => 1,
                'created_by'=>'Admin',
                'updated_by'=>'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 4,
                'application_id' => 2,
                'application_permission_id' => 2,
                'created_by'=>'Admin',
                'updated_by'=>'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 5,
                'application_id' => 2,
                'application_permission_id' => 3,
                'created_by'=>'Admin',
                'updated_by'=>'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 6,
                'application_id' => 2,
                'application_permission_id' => 4,
                'created_by'=>'Admin',
                'updated_by'=>'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 7,
                'application_id' => 3,
                'application_permission_id' => 5,
                'created_by'=>'Admin',
                'updated_by'=>'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 8,
                'application_id' => 4,
                'application_permission_id' => 6,
                'created_by'=>'Admin',
                'updated_by'=>'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
           
        ]);

        
    }
}
