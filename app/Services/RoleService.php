<?php

namespace App\Services;

use App\Events\RoleCreated;
use App\Events\RoleDeleted;
use App\Events\RoleUpdated;
use Spatie\Permission\Models\Role;
use App\Services\Contracts\RoleServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RoleService implements RoleServiceInterface
{
    public function getAllRoles(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Role::with('permissions')->latest();

        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        return $query->paginate($perPage);
    }

    public function getRoleById(int $id): ?Role
    {
        return Role::with('permissions')->find($id);
    }

    public function createRole(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            $role = Role::create([
                'name' => $data['name'],
                'group' => $data['group'] ?? null,
                'description' => $data['description'] ?? null,
            ]);

            // Dispatch event for role creation
            RoleCreated::dispatch($role);

            return $role;
        });
    }

    public function updateRole(int $id, array $data): bool
    {
        $role = Role::find($id);

        if (!$role) {
            return false;
        }

        $oldData = $role->toArray();

        $updated = $role->update([
            'name' => $data['name'],
            'group' => $data['group'] ?? $role->group,
            'description' => $data['description'] ?? $role->description,
        ]);

        if ($updated) {
            RoleUpdated::dispatch($role, $oldData);
        }

        return $updated;
    }

    public function deleteRole(int $id): bool
    {
        $role = Role::find($id);

        if (!$role) {
            return false;
        }

        return DB::transaction(function () use ($role) {
            $deleted = $role->delete();

            if ($deleted) {
                RoleDeleted::dispatch($role);
            }

            return $deleted;
        });
    }

    public function assignPermissions(int $roleId, array $permissionIds): bool
    {
        $role = Role::find($roleId);

        if (!$role) {
            return false;
        }

        $permissions = \Spatie\Permission\Models\Permission::whereIn('id', $permissionIds)->get();
        $role->syncPermissions($permissions);

        return true;
    }

    public function syncPermissions(int $roleId, array $permissionIds): bool
    {
        return $this->assignPermissions($roleId, $permissionIds);
    }

    public function getRoleWithPermissions(int $id): ?Role
    {
        return Role::with('permissions')->find($id);
    }

    public function getRolesByPermission(string $permissionName, int $perPage = 15): LengthAwarePaginator
    {
        return Role::with('permissions')
            ->whereHas('permissions', function ($query) use ($permissionName) {
                $query->where('name', $permissionName);
            })
            ->paginate($perPage);
    }
}
