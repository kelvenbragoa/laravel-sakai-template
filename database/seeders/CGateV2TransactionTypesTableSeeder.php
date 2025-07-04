<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CGateV2TransactionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('c_gate_v2_transaction_types')->insert([
            [
                'name' => 'Export Full In',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Import Full Out',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Empty In',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Empty Out',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}
