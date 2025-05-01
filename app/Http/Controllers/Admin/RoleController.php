<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\CreateRoleRequest;
use App\Http\Requests\Admin\Role\UpdateRoleRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index',compact('roles'));
    }

    public function store(CreateRoleRequest $request){
        Role::create($request->validated());
        return back()->with('success', 'Role created successfully');
    }
    
    public function update(UpdateRoleRequest $request, Role $role){
        $role->update(['name' => $request->name]);
        return back()->with('success', 'Role updated successfully');
    }
    
    public function destroy(Role $role){
        $role->delete();
        return back()->with('success', 'Role deleted successfully');
    }

}
