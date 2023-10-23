<?php

namespace Database\Seeders;

use App\Models\CustomPermission;
use App\Models\CustomRole;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $TCreate = Tag::create([
            'name' => 'Create',
            'description' => 'Refers to the action of generating or adding new content, records, or entities in a system. It is often associated with the ability to initiate something.',
        ]);

        $TRead = Tag::create([
            'name' => 'Read',
            'description' => 'Involves viewing or accessing existing data or content in a system without the ability to modify or alter it. It primarily pertains to retrieval of information.',
        ]);

        $TUpdate = Tag::create([
            'name' => 'Update',
            'description' => 'The act of modifying or changing existing data, records, or content within a system. It is usually linked with the ability to edit or revise information.',
        ]);

        $TDelete = Tag::create([
            'name' => 'Delete',
            'description' => 'Involves the action of permanently removing or erasing data, records, or content from a system. It is associated with the ability to eliminate something.',
        ]);

        $TAdmins = Tag::create([
            'name' => 'Admins',
            'description' => 'Refers to individuals or accounts with elevated privileges and full access to all features and functionalities within a system. Admins typically have administrative roles.',
        ]);

        $TAdmin = Tag::create([
            'name' => 'Admin',
            'description' => 'Represents an individual or account with administrative rights and full access to all features and functionalities within a system. Admins have the highest level of access.',
        ]);

        $TUsers = Tag::create([
            'name' => 'Users',
            'description' => 'Refers to individuals or accounts within a system who interact with and utilize its features, often possessing specific roles and permissions.',
        ]);

        $TUser = Tag::create([
            'name' => 'User',
            'description' => 'Represents a single individual or account registered or recognized within a system. Users are entities with specific profiles and access rights.',
        ]);

        $TTags = Tag::create([
            'name' => 'Tags',
            'description' => 'Refers to labels or markers used to categorize or identify specific content, entities, or records within a system. Tags facilitate organization and retrieval of information.',
        ]);

        $TTag = Tag::create([
            'name' => 'Tag',
            'description' => 'Denotes a single label or marker applied to categorize or classify specific content, entities, or records within a system. Tags aid in organizing and managing information.',
        ]);

        $TRoles = Tag::create([
            'name' => 'Roles',
            'description' => 'Denotes defined sets of permissions or responsibilities assigned to specific individuals or groups within a system, dictating their capabilities.',
        ]);

        $TRole = Tag::create([
            'name' => 'Role',
            'description' => 'Refers to a predefined category or set of permissions that define a user or a group of users\' access level or responsibilities within a system.',
        ]);

        $TPermissions = Tag::create([
            'name' => 'Permissions',
            'description' => 'Encompasses the specific authorizations or rights granted to users or roles within a system, allowing them to perform certain actions or operations.',
        ]);

        $TPermission = Tag::create([
            'name' => 'Permission',
            'description' => 'Denotes a particular authorization or privilege given to a user or a role, specifying what actions or operations they can undertake within a system.',
        ]);
    }
}