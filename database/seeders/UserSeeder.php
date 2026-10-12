<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'admin'],
            ['name' => 'Rita Reviewer', 'email' => 'reviewer@example.com', 'password' => 'password'],
            ['name' => 'Paolo Proponent', 'email' => 'proponent@example.com', 'password' => 'password'],
            ['name' => 'Maria Santos', 'email' => 'maria@example.com', 'password' => 'password'],
            ['name' => 'Jose Dela Cruz', 'email' => 'jose@example.com', 'password' => 'password'],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                ['name' => $user['name'], 'password' => Hash::make($user['password'])],
            );
        }
    }
}
