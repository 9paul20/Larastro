<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Users
        $CUser = Permission::create([
            'name' => 'Create User',
            'description' => 'Allows user to create new users',
            'tags' => 'Create, Users, User'
        ]);

        $RUser = Permission::create([
            'name' => 'Read User',
            'description' => 'Allows user to view user details',
            'tags' => 'Read, Users, User'
        ]);

        $UUser = Permission::create([
            'name' => 'Update User',
            'description' => 'Allows user to edit user information',
            'tags' => 'Update, Users, User'
        ]);

        $DUser = Permission::create([
            'name' => 'Delete User',
            'description' => 'Allows user to delete users',
            'tags' => 'Delete, Users, User'
        ]);

        //Roles
        $CRole = Permission::create([
            'name' => 'Create Roles',
            'description' => 'Allows user to create new roles',
            'tags' => 'Create, Roles, Role'
        ]);

        $RRole = Permission::create([
            'name' => 'Read Roles',
            'description' => 'Allows user to view role details',
            'tags' => 'Read, Roles, Role'
        ]);

        $URole = Permission::create([
            'name' => 'Update Roles',
            'description' => 'Allows user to edit role information',
            'tags' => 'Update, Roles, Role'
        ]);

        $DRole = Permission::create([
            'name' => 'Delete Roles',
            'description' => 'Allows user to delete roles',
            'tags' => 'Delete, Roles, Role'
        ]);

        //Permissions
        $CPermission = Permission::create([
            'name' => 'Create Permissions',
            'description' => 'Allows user to create new permissions',
            'tags' => 'Create, Permissions, Permission'
        ]);

        $RPermission = Permission::create([
            'name' => 'Read Permissions',
            'description' => 'Allows user to view permission details',
            'tags' => 'Read, Permissions, Permission'
        ]);

        $UPermission = Permission::create([
            'name' => 'Update Permissions',
            'description' => 'Allows user to edit permission information',
            'tags' => 'Update, Permissions, Permission'
        ]);

        $DPermission = Permission::create([
            'name' => 'Delete Permissions',
            'description' => 'Allows user to delete permissions',
            'tags' => 'Delete, Permissions, Permission'
        ]);

        //Sync Permissions with Role Admin
        // $RAdmin = Role::where('name', 'Admin')->first();
        $RAdmin = Role::findByName('Admin')->first();
        if ($RAdmin) {
            $RAdmin->syncPermissions([
                $CUser,
                $RUser,
                $UUser,
                $DUser,
                $CRole,
                $RRole,
                $URole,
                $DRole,
                $CPermission,
                $RPermission,
                $UPermission,
                $DPermission
            ]);
        }
        // if ($RAdmin) {
        //     $RAdmin->givePermissionTo([
        //         $CUser,
        //         $RUser,
        //         $UUser,
        //         $DUser,
        //         $CRole,
        //         $RRole,
        //         $URole,
        //         $DRole,
        //         $CPermission,
        //         $RPermission,
        //         $UPermission,
        //         $DPermission
        //     ]);
        // }
    }
}