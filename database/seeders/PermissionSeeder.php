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
        $CUser = Permission::create(['name' => 'Create User']);
        $RUser = Permission::create(['name' => 'Read User']);
        $UUser = Permission::create(['name' => 'Update User']);
        $DUser = Permission::create(['name' => 'Delete User']);

        //Roles
        $CRole = Permission::create(['name' => 'Create Roles']);
        $RRole = Permission::create(['name' => 'Read Roles']);
        $URole = Permission::create(['name' => 'Update Roles']);
        $DRole = Permission::create(['name' => 'Delete Roles']);

        //Permissions
        $CPermission = Permission::create(['name' => 'Create Permissions']);
        $RPermission = Permission::create(['name' => 'Read Permissions']);
        $UPermission = Permission::create(['name' => 'Update Permissions']);
        $DPermission = Permission::create(['name' => 'Delete Permissions']);

        //Sync Permissions with Role Admin
        $RAdmin = Role::where('name','Admin')->first();
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
    }
}
