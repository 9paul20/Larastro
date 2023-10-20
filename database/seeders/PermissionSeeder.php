<?php

namespace Database\Seeders;

use App\Models\CustomRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
// use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Users
        $CUsers = Permission::create([
            'name' => 'Create Users',
            'description' => 'Allows user to create new users',
            'tags' => 'Create, Users, User'
        ]);

        $RUsers = Permission::create([
            'name' => 'Read Users',
            'description' => 'Allows user to view user details',
            'tags' => 'Read, Users, User'
        ]);

        $UUsers = Permission::create([
            'name' => 'Update Users',
            'description' => 'Allows user to edit user information',
            'tags' => 'Update, Users, User'
        ]);

        $DUsers = Permission::create([
            'name' => 'Delete Users',
            'description' => 'Allows user to delete users',
            'tags' => 'Delete, Users, User'
        ]);

        //Roles
        $CRoles = Permission::create([
            'name' => 'Create Roles',
            'description' => 'Allows user to create new roles',
            'tags' => 'Create, Roles, Role'
        ]);

        $RRoles = Permission::create([
            'name' => 'Read Roles',
            'description' => 'Allows user to view role details',
            'tags' => 'Read, Roles, Role'
        ]);

        $URoles = Permission::create([
            'name' => 'Update Roles',
            'description' => 'Allows user to edit role information',
            'tags' => 'Update, Roles, Role'
        ]);

        $DRoles = Permission::create([
            'name' => 'Delete Roles',
            'description' => 'Allows user to delete roles',
            'tags' => 'Delete, Roles, Role'
        ]);

        //Permissions
        $CPermissions = Permission::create([
            'name' => 'Create Permissions',
            'description' => 'Allows user to create new permissions',
            'tags' => 'Create, Permissions, Permission'
        ]);

        $RPermissions = Permission::create([
            'name' => 'Read Permissions',
            'description' => 'Allows user to view permission details',
            'tags' => 'Read, Permissions, Permission'
        ]);

        $UPermissions = Permission::create([
            'name' => 'Update Permissions',
            'description' => 'Allows user to edit permission information',
            'tags' => 'Update, Permissions, Permission'
        ]);

        $DPermissions = Permission::create([
            'name' => 'Delete Permissions',
            'description' => 'Allows user to delete permissions',
            'tags' => 'Delete, Permissions, Permission'
        ]);

        $RAdmin = CustomRole::findByName('Admin')->first();
        if ($RAdmin) {
            $RAdmin->syncPermissions([
                $CUsers,
                $RUsers,
                $UUsers,
                $DUsers,
                $CRoles,
                $RRoles,
                $URoles,
                $DRoles,
                $CPermissions,
                $RPermissions,
                $UPermissions,
                $DPermissions
            ]);
        }
    }
}