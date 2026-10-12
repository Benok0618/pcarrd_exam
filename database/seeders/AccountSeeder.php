<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'view proposals',
            'create proposals',
            'update proposals',
            'delete proposals',
            'update proposal status',
            'add remarks',
            'view categories',
            'manage categories',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roles = [
            'Admin' => $permissions,
            'Reviewer' => [
                'view proposals',
                'update proposal status',
                'add remarks',
                'view categories',
            ],
            'Proponent' => [
                'view proposals',
                'create proposals',
                'update proposals',
                'delete proposals',
                'view categories',
                'manage categories',
            ],
        ];

        foreach ($roles as $name => $rolePermissions) {
            Role::firstOrCreate(['name' => $name])->syncPermissions($rolePermissions);
        }

        $accounts = [
            ['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'admin', 'role' => 'Admin'],
            ['name' => 'Rita Reviewer', 'email' => 'reviewer@example.com', 'password' => 'password', 'role' => 'Reviewer'],
            ['name' => 'Paolo Proponent', 'email' => 'proponent@example.com', 'password' => 'password', 'role' => 'Proponent'],
            ['name' => 'Maria Santos', 'email' => 'maria@example.com', 'password' => 'password', 'role' => 'Proponent'],
            ['name' => 'Jose Dela Cruz', 'email' => 'jose@example.com', 'password' => 'password', 'role' => 'Proponent'],
        ];

        foreach ($accounts as $account) {
            $user = User::firstOrCreate(
                ['email' => $account['email']],
                ['name' => $account['name'], 'password' => Hash::make($account['password'])],
            );

            $user->syncRoles([$account['role']]);
        }
    }
}
