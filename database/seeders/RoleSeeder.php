<?php

namespace Database\Seeders;

use App\Models\CustomRole;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $RAdmin = CustomRole::create([
            'name' => 'Admin',
            'description' => 'Administrators have full access to all features and functionalities',
            'tags' => 'Admin, Role'
        ]);

        $TAdmin = Tag::where('name', 'Admin')->first();
        $TRole = Tag::where('name', 'Role')->first();

        $RAdmin->tags()->sync([
            $TAdmin->id,
            $TRole->id,
        ]);

        $RUser = CustomRole::create([
            'name' => 'User',
            'description' => 'Standard users with limited privileges',
            'tags' => 'User, Role'
        ]);

        $TUser = Tag::where('name', 'User')->first();

        $RUser->tags()->sync([
            $TUser->id,
            $TRole->id
        ]);
    }
}