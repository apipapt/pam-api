<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create perissions
        $arrayOfPermissionNames = [
            // users permission
            'users-list',
            'users-add',
            'users-edit',
            'users-delete',
            // roles permission
            'roles-list',
            'roles-add',
            'roles-edit',
            'roles-delete',
            // permissions
            'permissions-list',
            'permissions-add',
            'permissions-edit',
            'permissions-delete',

            'members-list',
            'members-add',
            'members-edit',
            'members-delete',

            // water data
            'water-data-list',
            'water-data-add',
            'water-data-edit',
            'water-data-delete'
        ];
 
        $permissions = collect($arrayOfPermissionNames)->map(function ($permission) {
            return ['name' => $permission, 'guard_name' => 'api'];
        });

        Permission::insert($permissions->toArray());


        // create role

        // # admin role
        $role = Role::create(['name' => 'ADMINISTRATOR', 'guard_name' => 'api'])
            ->syncPermissions([
                // users permission
                'users-list',
                'users-add',
                'users-edit',
                'users-delete',
                // roles permission
                'roles-list',
                'roles-add',
                'roles-edit',
                'roles-delete',
                // permissions
                'permissions-list',
                'permissions-add',
                'permissions-edit',
                'permissions-delete',

                //members
                'members-list',
                'members-add',
                'members-edit',
                'members-delete',

                // water data
                'water-data-list',
                'water-data-add',
                'water-data-edit',
                'water-data-delete'
            ]);

        // # school role
        $role = Role::create(['name' => 'ADMIN',    'guard_name' => 'api']);
        $role = Role::create(['name' => 'CHECKER',  'guard_name' => 'api']);
        $role = Role::create(['name' => 'USER',     'guard_name' => 'api']);
    }
}
