<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Contracts\PermissionServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    public function __construct(
        protected PermissionServiceInterface $permissionService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $permissions = $this->permissionService->getAllPermissions(
            $request->only(['search', 'group']),
            10
        );

        return view('admin.permissions.index', [
            'permissions' => $permissions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions'],
            'group' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500']
        ]);

        $permissionData = [
            'name' => $request->name,
            'group' => $request->group,
            'description' => $request->description,
        ];

        $permission = $this->permissionService->createPermission($permissionData);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $permission = $this->permissionService->getPermissionWithRoles($id);

        if (!$permission) {
            abort(404);
        }

        return view('admin.permissions.show', [
            'permission' => $permission
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $permission = $this->permissionService->getPermissionById($id);

        if (!$permission) {
            abort(404);
        }

        return view('admin.permissions.edit', [
            'permission' => $permission
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $permission = $this->permissionService->getPermissionById($id);

        if (!$permission) {
            return back()->with('error', 'Permission not found.');
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions')->ignore($permission->id)
            ],
            'group' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500']
        ]);

        $permissionData = [
            'name' => $request->name,
            'group' => $request->group,
            'description' => $request->description,
        ];

        $success = $this->permissionService->updatePermission($id, $permissionData);

        if (!$success) {
            return back()->with('error', 'Failed to update permission.');
        }

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $permission = $this->permissionService->getPermissionById($id);

        if (!$permission) {
            return back()->with('error', 'Permission not found.');
        }

        // Check if permission is assigned to any role
        if ($permission->roles()->count() > 0) {
            return back()->with('error', 'Cannot delete permission assigned to roles.');
        }

        $success = $this->permissionService->deletePermission($id);

        if (!$success) {
            return back()->with('error', 'Failed to delete permission.');
        }

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}
