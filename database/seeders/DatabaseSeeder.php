<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(PermissionRolesTableSeeder::class);
        // $this->call(CompanyTableSeeder::class);
        // $this->call(GateTableSeeder::class);
        // $this->call(UserTableSeeder::class);

        $this->call(ApplicationSeeder::class);
    }
}
