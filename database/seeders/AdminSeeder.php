<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Define permissions
        $permissions = [
            "email.config",
            "form.config",
            "role.view",
            "role.edit",
            "role.create",
        ];

        // Create permissions if they don't exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create or get the 'Admin' role
        $role = Role::firstOrCreate(['name' => 'Admin']);

        // Assign all permissions to the Admin role
        $role->syncPermissions($permissions);

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin@gmail.com'),
            ]
        );

        // Assign the 'Admin' role to user
        if (!$admin->hasRole('Admin')) {
            $admin->assignRole($role);
        }
    }
}
