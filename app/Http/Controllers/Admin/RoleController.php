<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::with('permissions')->latest()->paginate(10);
        
        return view('admin.roles.index', [
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        
        return view('admin.roles.create', [
            'permissions' => $permissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['exists:permissions,id']
        ]);

        DB::beginTransaction();
        
        try {
            $role = Role::create(['name' => $request->name]);
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);
            
            DB::commit();
            
            return redirect()->route('admin.roles.index')
                ->with('success', 'Role created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create role. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        
        return view('admin.roles.show', [
            'role' => $role
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all();
        
        return view('admin.roles.edit', [
            'role' => $role,
            'permissions' => $permissions
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Log the incoming request data for debugging
        \Log::info('Updating role', [
            'role_id' => $id,
            'user_id' => auth()->id()
        ]);

        $role = Role::findOrFail($id);
        
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->ignore($role->id)
            ],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['exists:permissions,id']
        ], [
            'permissions.required' => 'Paling tidak pilih satu permission.',
            'permissions.min' => 'Paling tidak pilih satu permission.'
        ]);

        DB::beginTransaction();
        
        try {
            // Update role name
            $role->name = $validated['name'];
            $role->save();
            
            // Sync permissions
            $permissions = Permission::whereIn('id', $validated['permissions'])->pluck('name')->toArray();
            $role->syncPermissions($permissions);
            
            DB::commit();
            
            // Log successful update
            \Log::info('Role updated successfully', ['role_id' => $role->id]);
            
            return redirect()->route('admin.roles.index')
                ->with('success', 'Role berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update role. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        
        // Prevent deleting admin role
        if ($role->name === 'admin') {
            return redirect()->back()
                ->with('error', 'Cannot delete admin role.');
        }
        
        // Check if role is assigned to any user
        if ($role->users()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete role assigned to users.');
        }
        
        $role->delete();
        
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
