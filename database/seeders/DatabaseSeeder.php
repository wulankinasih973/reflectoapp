<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'user']);

        // Buat user admin
        User::firstOrCreate([
            'email' => 'admin@reflecto.com'
        ], [
            'name' => 'Admin Reflecto',
            'password' => Hash::make('password123'),
            'role_id' => $adminRole->id,
        ]);

    }
}
