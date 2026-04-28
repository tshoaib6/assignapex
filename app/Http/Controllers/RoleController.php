<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::get();
        return view('Roles.role_index', compact('roles'));
    }


// Create with permission

public function create()
{
    $roles = Role::all();
    $allPermissions = Permission::all();

    return view('Roles.role_create', compact('roles', 'allPermissions'));
}




// create role

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|unique:roles,name',
        'permissions' => 'required|array',
    ]);

    // ✅ Create Role
    $role = Role::create([
        'name' => $request->name,
    ]);

    // ✅ Assign Permissions
    $role->syncPermissions($request->permissions);

    return redirect('/roles')->with('status', 'Role created successfully');
}


public function edit($id)
{
    $role = Role::findOrFail($id);
    $allPermissions = Permission::all();
    $rolePermissions = $role->permissions->pluck('id')->toArray(); // pre-checked

    return view('Roles.role_edit', compact('role', 'allPermissions', 'rolePermissions'));
}



public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|unique:roles,name,' . $id,
        'permissions' => 'required|array'
    ]);

    $role = Role::findOrFail($id);
    $role->update(['name' => $request->name]);

    $role->syncPermissions($request->permissions);

    return redirect()->route('roles.index')->with('status', 'Role updated successfully!');
}




public function destroy($roleId)
{
    $role = Role::findOrFail($roleId);
    $role->delete();

    return redirect()->route('roles.index')->with('status', 'Role deleted successfully!');
}





}
