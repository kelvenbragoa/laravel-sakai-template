<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('gates')->insert([
            [
                'name' => 'Portao 8A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Portao 11A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Portao 5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Portao 4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Portao 6',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Saida 11A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
