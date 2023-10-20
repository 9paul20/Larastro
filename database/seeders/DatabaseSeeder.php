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
        //Tags
        $this->call(TagSeeder::class);
        // Roles
        $this->call(RoleSeeder::class);
        // Permissions
        $this->call(PermissionSeeder::class);
        // Users
        $this->call(UserSeeder::class);
    }
}