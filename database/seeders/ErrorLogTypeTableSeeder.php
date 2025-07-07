<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ErrorLogTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
            DB::table('error_log_types')->insert([

                // [
                //     'name' => 'Erro de Validação',
                //     'created_at' => now(),
                //     'updated_at' => now(),
                // ],
                [
                    "name" => 'Erro de Exceção',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            
            
            ]);
    }
}
