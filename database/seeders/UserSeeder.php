<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $userRole  = Role::where('name', 'user')->first();

        User::create([
            'name' => 'Admin Test',
            'email' => 'admin@reflecto.test',
            'password' => Hash::make('admin123'),
            'role_id' => $adminRole->id,
        ]);

        User::create([
            'name' => 'User Test',
            'email' => 'user@reflecto.test',
            'password' => Hash::make('user123'),
            'role_id' => $userRole->id,
        ]);
    }
}
