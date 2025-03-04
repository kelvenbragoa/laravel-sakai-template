<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('companies')->insert([
            [
                'name' => 'Cornelder de Mocambique',
                'mobile'=>'000000',
                'email'=>'cornelder@cornelder.co.mz',
                'address'=>'Beira',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ForteSeguro',
                'mobile'=>'00000',
                'email'=>'forteseguro@forteseguro.co.mz',
                'address'=>'Beira',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}
