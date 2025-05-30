<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GatePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('gate_permissions')->insert([
            [
                'name' => 'Inserir e Escanear os Contentores',
                'description'=>'Inserção e Escaneamento',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Inserir e Escanear as Cartas de condução',
                'description'=>'Inserção e Escaneamento',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Inserir e Escanear as matrículas do camião',
                'description'=>'Inserção e Escaneamento',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Inserir e Escanear as matrículas dos atrelados',
                'description'=>'Inserção e Escaneamento',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Inserir e Escanear os tipos de carga',
                'description'=>'Inserção e Escaneamento',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Inserir e Escanear os números de selos',
                'description'=>'Inserção e Escaneamento',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Inserir e Escanear as Pesagens',
                'description'=>'Inserção e Escaneamento',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Verificar e Validar Contentor',
                'description'=>'Verificações e Validações',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Verificar e Validar Carta de condução',
                'description'=>'Verificações e Validações',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Verificar e Validar matrícula do camião',
                'description'=>'Verificações e Validações',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Verificar e Validar matrícula dos atrelados',
                'description'=>'Verificações e Validações',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Verificar e Validar o tipo de carga',
                'description'=>'Verificações e Validações',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Verificar e Validar os números de selos',
                'description'=>'Verificações e Validações',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Verificar e Validar Pesagens',
                'description'=>'Verificações e Validações',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'N4',
                'description'=>'Opções de plataforma de comparação de dados',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CDMS',
                'description'=>'Opções de plataforma de comparação de dados',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Neuralabs',
                'description'=>'Opções de plataforma de comparação de dados',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Verificação de segurança',
                'description'=>'Opções de segurança',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Verificação de Appointment',
                'description'=>'Opções de segurança',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pre-Check',
                'description'=>'Opções de segurança',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'After check',
                'description'=>'Opções de segurança',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Excepções',
                'description'=>'Opções de segurança',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Imprimir o TID',
                'description'=>'Opções de segurança',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Imprimir a nota de entrega',
                'description'=>'Opções de segurança',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
        ]);
    }
}
