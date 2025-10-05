<?php

namespace App\Services\Contracts;

use Spatie\Permission\Models\Permission;
use Illuminate\Pagination\LengthAwarePaginator;

interface PermissionServiceInterface
{
    public function getAllPermissions(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function getPermissionById(int $id): ?Permission;

    public function createPermission(array $data): Permission;

    public function updatePermission(int $id, array $data): bool;

    public function deletePermission(int $id): bool;

    public function getPermissionWithRoles(int $id): ?Permission;

    public function getPermissionsByGroup(string $group, int $perPage = 15): LengthAwarePaginator;
}
