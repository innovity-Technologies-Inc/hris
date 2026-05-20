<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Services\Setting\RoleServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    protected $roleServices;

    public function __construct(RoleServices $roleServices)
    {
        $this->roleServices = $roleServices;
    }

    public function index()
    {
        $title = 'Role Management';
        $section = 'Settings';
        $sub_section = 'Roles';
        $roles = $this->roleServices->getRoles();
        
        return view('setting.roles.index', compact('title', 'section', 'sub_section', 'roles'));
    }

    public function create()
    {
        $title = 'Create Role';
        $section = 'Settings';
        $sub_section = 'Roles / Create';
        $menus = $this->roleServices->getMenus();
        $allPermissions = $this->roleServices->getAllPermissions();
        
        return view('setting.roles.form', compact('title', 'section', 'sub_section', 'menus', 'allPermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        try {
            $this->roleServices->saveRole($request);
            return redirect()->route('setting.roles.index')->with([
                'message' => 'Role created successfully',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Role store failed: ' . $e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something went wrong',
                'alert-type' => 'error'
            ]);
        }
    }

    public function edit($id)
    {
        $title = 'Edit Role';
        $section = 'Settings';
        $sub_section = 'Roles / Edit';
        $role = \Spatie\Permission\Models\Role::with('permissions')->findOrFail($id);
        $menus = $this->roleServices->getMenus();
        $allPermissions = $this->roleServices->getAllPermissions();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        
        return view('setting.roles.form', compact('title', 'section', 'sub_section', 'role', 'menus', 'allPermissions', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
        ]);

        try {
            $this->roleServices->saveRole($request, $id);
            return redirect()->route('setting.roles.index')->with([
                'message' => 'Role updated successfully',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Role update failed: ' . $e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something went wrong',
                'alert-type' => 'error'
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $this->roleServices->deleteRole($id);
            return redirect()->route('setting.roles.index')->with([
                'message' => 'Role deleted successfully',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
    }
}

