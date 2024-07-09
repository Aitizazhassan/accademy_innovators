<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GenericRoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get already existing admin roles
        $adminRole = Role::where('name', 'admin')->first();

        // Permissions
        $permissions = [
            'user'    => ['create', 'view', 'edit', 'delete'],
            'role'    => ['create', 'view', 'edit', 'delete'],
            'profile' => ['create', 'view', 'edit', 'delete'],
            'country' => ['create', 'view', 'edit', 'delete'],
            'board' => ['create', 'view', 'edit', 'delete'],
            'class' => ['create', 'view', 'edit', 'delete'],
            'subject' => ['create', 'view', 'edit', 'delete'],
            'chapter' => ['create', 'view', 'edit', 'delete'],
            'topic' => ['create', 'view', 'edit', 'delete'],
            'mcqs' => ['create', 'view', 'edit', 'delete'],
        ];

        // assign permissions to user having role admin first created 
        foreach ($permissions as $module => $actions) {
            foreach ($actions as $action) {
                $permissionName = "{$module}.{$action}";
                $existingPermission = Permission::where('name', $permissionName)->first();

                if (!$existingPermission) {
                    $newPermission = Permission::create(['name' => $permissionName]);
                    if (!$adminRole->hasPermissionTo($newPermission)) {
                        $adminRole->givePermissionTo($newPermission);
                    }
                } else {
                    if (!$adminRole->hasPermissionTo($existingPermission)) {
                        $adminRole->givePermissionTo($existingPermission);
                    }
                }
            }
        }
    }
}
