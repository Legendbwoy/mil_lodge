<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'], // your preferred admin email
            [
                'first_name' => 'Super',
                'last_name'  => 'Admin',
                'service_number' => 'ADM001',
                'phone' => '0000000000',
                'rank' => 'colonel',
                'branch' => 'hq',
                'role' => 'admin',
                'password' => Hash::make('password123'), // change to strong password
            ]
        );
    }
}
