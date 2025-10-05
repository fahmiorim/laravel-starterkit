<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Contracts\RoleServiceInterface;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function __construct(
        protected RoleServiceInterface $roleService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $roles = $this->roleService->getAllRoles(
            $request->only(['search']),
            10
        );

        return view('admin.roles.index', [
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $permissions = Permission::all();

        return view('admin.roles.create', [
            'permissions' => $permissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles'],
            'group' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['exists:permissions,id']
        ]);

        $roleData = [
            'name' => $request->name,
            'group' => $request->group,
            'description' => $request->description,
        ];

        $role = $this->roleService->createRole($roleData);

        // Assign permissions using service
        $this->roleService->assignPermissions($role->id, $request->permissions);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $role = $this->roleService->getRoleWithPermissions($id);

        if (!$role) {
            abort(404);
        }

        return view('admin.roles.show', [
            'role' => $role
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $role = $this->roleService->getRoleWithPermissions($id);

        if (!$role) {
            abort(404);
        }

        $permissions = Permission::all();

        return view('admin.roles.edit', [
            'role' => $role,
            'permissions' => $permissions
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $role = $this->roleService->getRoleById($id);

        if (!$role) {
            return back()->with('error', 'Role not found.');
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->ignore($role->id)
            ],
            'group' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['exists:permissions,id']
        ], [
            'permissions.required' => 'Paling tidak pilih satu permission.',
            'permissions.min' => 'Paling tidak pilih satu permission.'
        ]);

        $roleData = [
            'name' => $request->name,
            'group' => $request->group,
            'description' => $request->description,
        ];

        $success = $this->roleService->updateRole($id, $roleData);

        if (!$success) {
            return back()->with('error', 'Failed to update role.');
        }

        // Sync permissions using service
        $this->roleService->assignPermissions($id, $request->permissions);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $role = $this->roleService->getRoleById($id);

        if (!$role) {
            return back()->with('error', 'Role not found.');
        }

        // Prevent deleting admin role
        if ($role->name === 'admin') {
            return back()->with('error', 'Cannot delete admin role.');
        }

        // Check if role is assigned to any user
        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role assigned to users.');
        }

        $success = $this->roleService->deleteRole($id);

        if (!$success) {
            return back()->with('error', 'Failed to delete role.');
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
