<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    // Show all permissions (index)
    public function index()
    {
        $permissions = Permission::orderBy('name')->paginate(15);
        return view('admin.permissions.index', compact('permissions'));
    }

    // Show create form
    public function create()
    {
        return view('admin.permissions.create');
    }

    // Store a new permission
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'slug' => 'required|string|unique:permissions,slug',
        ]);

        Permission::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
    }

    // Show edit form
    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    // Update existing permission
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
            'slug' => 'required|string|unique:permissions,slug,' . $permission->id,
        ]);

        $permission->update([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
    }

    // Delete permission
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
    