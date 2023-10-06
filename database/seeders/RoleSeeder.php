<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $RAdmin = Role::create([
            'name' => 'Admin',
            'description' => 'Administrators have full access to all features and functionalities',
            'tags' => 'Admin, Role'
        ]);
        $RUser = Role::create([
            'name' => 'User',
            'description' => 'Standard users with limited privileges',
            'tags' => 'User, Role'
        ]);
    }
}
