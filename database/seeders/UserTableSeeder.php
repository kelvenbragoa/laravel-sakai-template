<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// class UserTableSeeder extends Seeder
// {
//     /**
//      * Run the database seeds.
//      */
//     public function run(): void
//     {
//         //
//         $users = [];
//         $numberOfUsers = 50; // Change this to the number of users you want to insert

//         for ($i = 1; $i <= $numberOfUsers; $i++) {
//             $users[] = [
//                 'name' => 'User ' . $i,
//                 'email' => 'user' . $i . '@test.com',
//                 'mobile' => '81123456' . $i,
//                 'is_active' => $i % 2, // Alternate between 0 and 1 for demo
//                 'password' => Hash::make('password'),
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ];
//         }

//         DB::table('users')->insert($users);
    
//     }
// }

use Spatie\Permission\Models\Role;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = [];
        $numberOfUsers = 50;

        for ($i = 1; $i <= $numberOfUsers; $i++) {
            $user = User::create([
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@test.com',
                'mobile' => '81123456' . $i,
                'is_active' => $i % 2,
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Atribuindo a role 'Admin' ao usuário
            $role = Role::findByName('Admin', 'web');
            $user->assignRole($role);
        }
    }
}

