<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        // $estimatorRole = Role::create(['name' => 'estimator ']);

        // Create Permissions
        $permissions = [
            'user'    => ['create', 'view', 'edit', 'delete'],
            'role'    => ['create', 'view', 'edit', 'delete'],
            'profile' => ['create', 'view', 'edit', 'delete'],
        ];

        // assign permission
        foreach ($permissions as $module => $actions) {
            foreach ($actions as $action) {
                $permissionName = "{$module}.{$action}";
                Permission::create(['name' => $permissionName]);
                $adminRole->givePermissionTo($permissionName);
                // Assign the "profile" and "parcel" permissions to the "estimator" role
                // if ($module === 'profile') {
                //     $estimatorRole->givePermissionTo($permissionName);
                // }
            }
        }
    }
}
