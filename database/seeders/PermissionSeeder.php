<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            "email.config",
            "form.config",
            "todo.list",
            "redo.list",
            "role.view",
            "role.edit",
            "role.create",
            "cst.view",
            "cst.create",
            "cst.edit",
            "cst.delete"
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
