<?php

namespace Database\Seeders;

use App\Models\CustomPermission;
use App\Models\CustomRole;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Users
        $CUsers = CustomPermission::create([
            'name' => 'Create Users',
            'description' => 'Allows user to create new users',
            'tags' => 'Create, Users, User'
        ]);

        $TCreate = Tag::where('name', 'Create')->first();
        $TUsers = Tag::where('name', 'Users')->first();
        $TUser = Tag::where('name', 'User')->first();

        $CUsers->tags()->sync([
            $TCreate->id,
            $TUsers->id,
            $TUser->id,
        ]);

        $RUsers = CustomPermission::create([
            'name' => 'Read Users',
            'description' => 'Allows user to view user details',
            'tags' => 'Read, Users, User'
        ]);

        $TRead = Tag::where('name', 'Read')->first();

        $RUsers->tags()->sync([
            $TRead->id,
            $TUsers->id,
            $TUser->id,
        ]);

        $UUsers = CustomPermission::create([
            'name' => 'Update Users',
            'description' => 'Allows user to edit user information',
            'tags' => 'Update, Users, User'
        ]);

        $TUpdate = Tag::where('name', 'Update')->first();

        $UUsers->tags()->sync([
            $TUpdate->id,
            $TUsers->id,
            $TUser->id,
        ]);

        $DUsers = CustomPermission::create([
            'name' => 'Delete Users',
            'description' => 'Allows user to delete users',
            'tags' => 'Delete, Users, User'
        ]);

        $TDelete = Tag::where('name', 'Delete')->first();

        $DUsers->tags()->sync([
            $TDelete->id,
            $TUsers->id,
            $TUser->id,
        ]);

        //Tags
        $CTags = CustomPermission::create([
            'name' => 'Create Tags',
            'description' => 'Allows user to create new tags',
            'tags' => 'Create, Tags, Tag'
        ]);

        $TTags = Tag::where('name', 'Tags')->first();
        $TTag = Tag::where('name', 'Tag')->first();

        $CTags->tags()->sync([
            $TCreate->id,
            $TTags->id,
            $TTag->id,
        ]);

        $RTags = CustomPermission::create([
            'name' => 'Read Tags',
            'description' => 'Allows user to view tag details',
            'tags' => 'Read, Tags, Tag'
        ]);

        $RTags->tags()->sync([
            $TRead->id,
            $TTags->id,
            $TTag->id,
        ]);

        $UTags = CustomPermission::create([
            'name' => 'Update Tags',
            'description' => 'Allows user to edit tag information',
            'tags' => 'Update, Tags, Tag'
        ]);

        $UTags->tags()->sync([
            $TUpdate->id,
            $TTags->id,
            $TTag->id,
        ]);

        $DTags = CustomPermission::create([
            'name' => 'Delete Tags',
            'description' => 'Allows user to delete tags',
            'tags' => 'Delete, Tags, Tag'
        ]);

        $DTags->tags()->sync([
            $TDelete->id,
            $TTags->id,
            $TTag->id,
        ]);

        //Roles
        $CRoles = CustomPermission::create([
            'name' => 'Create Roles',
            'description' => 'Allows user to create new roles',
            'tags' => 'Create, Roles, Role'
        ]);

        $TRoles = Tag::where('name', 'Roles')->first();
        $TRole = Tag::where('name', 'Role')->first();

        $CRoles->tags()->sync([
            $TDelete->id,
            $TRoles->id,
            $TRole->id,
        ]);

        $RRoles = CustomPermission::create([
            'name' => 'Read Roles',
            'description' => 'Allows user to view role details',
            'tags' => 'Read, Roles, Role'
        ]);

        $RRoles->tags()->sync([
            $TRead->id,
            $TRoles->id,
            $TRole->id,
        ]);

        $URoles = CustomPermission::create([
            'name' => 'Update Roles',
            'description' => 'Allows user to edit role information',
            'tags' => 'Update, Roles, Role'
        ]);

        $URoles->tags()->sync([
            $TUpdate->id,
            $TRoles->id,
            $TRole->id,
        ]);

        $DRoles = CustomPermission::create([
            'name' => 'Delete Roles',
            'description' => 'Allows user to delete roles',
            'tags' => 'Delete, Roles, Role'
        ]);

        $DRoles->tags()->sync([
            $TDelete->id,
            $TRoles->id,
            $TRole->id,
        ]);

        //Permissions
        $CPermissions = CustomPermission::create([
            'name' => 'Create Permissions',
            'description' => 'Allows user to create new permissions',
            'tags' => 'Create, Permissions, Permission'
        ]);

        $TPermissions = Tag::where('name', 'Permissions')->first();
        $TPermission = Tag::where('name', 'Permission')->first();

        $CPermissions->tags()->sync([
            $TCreate->id,
            $TPermissions->id,
            $TPermission->id,
        ]);

        $RPermissions = CustomPermission::create([
            'name' => 'Read Permissions',
            'description' => 'Allows user to view permission details',
            'tags' => 'Read, Permissions, Permission'
        ]);

        $RPermissions->tags()->sync([
            $TRead->id,
            $TPermissions->id,
            $TPermission->id,
        ]);

        $UPermissions = CustomPermission::create([
            'name' => 'Update Permissions',
            'description' => 'Allows user to edit permission information',
            'tags' => 'Update, Permissions, Permission'
        ]);

        $UPermissions->tags()->sync([
            $TUpdate->id,
            $TPermissions->id,
            $TPermission->id,
        ]);

        $DPermissions = CustomPermission::create([
            'name' => 'Delete Permissions',
            'description' => 'Allows user to delete permissions',
            'tags' => 'Delete, Permissions, Permission'
        ]);

        $DPermissions->tags()->sync([
            $TDelete->id,
            $TPermissions->id,
            $TPermission->id,
        ]);

        $RAdmin = CustomRole::findByName('Admin')->first();
        if ($RAdmin) {
            $RAdmin->syncPermissions([
                $CUsers,
                $RUsers,
                $UUsers,
                $DUsers,
                $CTags,
                $RTags,
                $UTags,
                $DTags,
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