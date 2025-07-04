<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CGateV2MovementTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('c_gate_v2_movement_types')->insert([
            [
                'name' => 'Security Check In',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Security Check Out',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tally In',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tally Out',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}
