<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $UAdmin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ])->assignRole('Admin');
        $UAdmin->syncPermissions(['Create Users', 'Read Users', 'Update Users', 'Delete Users', 'Create Roles', 'Read Roles', 'Update Roles', 'Delete Roles', 'Create Permissions', 'Read Permissions', 'Update Permissions', 'Delete Permissions']);

        $usersSeeder = User::factory(10)
            ->create()
            ->each(function ($user) {
                $user->assignRole('User');
                $user->save();
            });
    }
}