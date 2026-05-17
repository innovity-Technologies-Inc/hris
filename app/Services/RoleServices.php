<?php

namespace App\Services;

use App\Models\Menu;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleServices
{
    public function getRoles()
    {
        return Role::with('permissions')->get();
    }

    public function getMenus()
    {
        return Menu::with('submenus')->whereNull('parent_id')->orderBy('order')->get();
    }

    public function getAllPermissions()
    {
        return Permission::all();
    }

    public function saveRole($request, $id = null)
    {
        return DB::transaction(function () use ($request, $id) {
            if ($id) {
                $role = Role::findOrFail($id);
                $role->update(['name' => $request->name]);
            } else {
                $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
            }

            // Sync Permissions
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            } else {
                $role->syncPermissions([]);
            }

            return $role;
        });
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        if ($role->name === 'Super Admin') {
            throw new \Exception('Super Admin role cannot be deleted.');
        }
        return $role->delete();
    }
}
