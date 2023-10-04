<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Permissions
        $this->call(PermissionSeeder::class);
        // Roles
        $this->call(RoleSeeder::class);
        // Users
        $this->call(UserSeeder::class);
    }
}