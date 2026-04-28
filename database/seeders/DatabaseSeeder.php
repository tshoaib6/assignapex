<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $this->call([
        // ReviewerRejectionSeeder::class,
        PostProcessorRejectionSeeder::class,
        ScenariosSeeder::class,
        // TeamDetailsSeeder::class,
        RoleSeeder::class,
        PermissionSeeder::class,
        AdminSeeder::class,
    ]);

    }
}
