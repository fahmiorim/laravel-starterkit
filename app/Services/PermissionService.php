<?php

namespace App\Services;

use App\Events\PermissionCreated;
use App\Events\PermissionDeleted;
use App\Events\PermissionUpdated;
use Spatie\Permission\Models\Permission;
use App\Services\Contracts\PermissionServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PermissionService implements PermissionServiceInterface
{
    public function getAllPermissions(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Permission::with('roles')->latest();

        if (isset($filters['group']) && !empty($filters['group'])) {
            $query->where('group', $filters['group']);
        }

        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        return $query->paginate($perPage);
    }

    public function getPermissionById(int $id): ?Permission
    {
        return Permission::with('roles')->find($id);
    }

    public function createPermission(array $data): Permission
    {
        return DB::transaction(function () use ($data) {
            $permission = Permission::create([
                'name' => $data['name'],
                'group' => $data['group'] ?? null,
                'description' => $data['description'] ?? null,
            ]);

            // Dispatch event for permission creation
            PermissionCreated::dispatch($permission);

            return $permission;
        });
    }

    public function updatePermission(int $id, array $data): bool
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return false;
        }

        $oldData = $permission->toArray();

        $updated = $permission->update([
            'name' => $data['name'],
            'group' => $data['group'] ?? $permission->group,
            'description' => $data['description'] ?? $permission->description,
        ]);

        if ($updated) {
            PermissionUpdated::dispatch($permission, $oldData);
        }

        return $updated;
    }

    public function deletePermission(int $id): bool
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return false;
        }

        return DB::transaction(function () use ($permission) {
            $deleted = $permission->delete();

            if ($deleted) {
                PermissionDeleted::dispatch($permission);
            }

            return $deleted;
        });
    }

    public function getPermissionWithRoles(int $id): ?Permission
    {
        return Permission::with('roles')->find($id);
    }

    public function getPermissionsByGroup(string $group, int $perPage = 15): LengthAwarePaginator
    {
        return Permission::with('roles')
            ->where('group', $group)
            ->paginate($perPage);
    }
}
