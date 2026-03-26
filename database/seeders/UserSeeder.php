<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['login_id' => 'admin'],
            [
                'email' => 'admin@example.com',
                'password' => Hash::make('password123!'),
                'global_role' => User::ROLE_SUPER_ADMIN,
            ]
        );
        
        User::query()->updateOrCreate(
            ['login_id' => 'user01'],
            [
                'email' => 'user01@example.com',
                'password' => Hash::make('password123!'),
                'global_role' => User::ROLE_SUPER_ADMIN,
            ]
        );
        
        User::query()->updateOrCreate(
            ['login_id' => 'user02'],
            [
                'email' => 'user02@example.com',
                'password' => Hash::make('password123!'),
                'global_role' => User::ROLE_USER,
            ]
        );
    }
}