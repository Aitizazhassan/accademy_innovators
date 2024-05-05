<?php

namespace App\Repositories;

use DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Permission;

class PermissionsRepository
{
    public function updatePermissions($request, $id)
    {

        $role = Role::find($id);
        $role->name = $request->input('name');

        if (!$role->syncPermissions($request->input('permissions'))){
            return false;
        };
        return true;
    }
}
