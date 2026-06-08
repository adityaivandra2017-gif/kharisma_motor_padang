<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => '123456',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'pimpinan@gmail.com'],
            [
                'name' => 'Pimpinan',
                'password' => '123456',
                'role' => 'pimpinan',
            ]
        );
    }
}
