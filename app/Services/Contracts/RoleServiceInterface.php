<?php

namespace App\Services\Contracts;

use Spatie\Permission\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;

interface RoleServiceInterface
{
    public function getAllRoles(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function getRoleById(int $id): ?Role;

    public function createRole(array $data): Role;

    public function updateRole(int $id, array $data): bool;

    public function deleteRole(int $id): bool;

    public function assignPermissions(int $roleId, array $permissionIds): bool;

    public function syncPermissions(int $roleId, array $permissionIds): bool;

    public function getRoleWithPermissions(int $id): ?Role;

    public function getRolesByPermission(string $permissionName, int $perPage = 15): LengthAwarePaginator;
}
