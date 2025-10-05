<?php

namespace App\Services\Contracts;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function getAllUsers(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function getUserById(int $id): ?User;

    public function createUser(array $data): User;

    public function updateUser(int $id, array $data): bool;

    public function deleteUser(int $id): bool;

    public function toggleUserStatus(int $id): bool;

    public function assignRoles(int $userId, array $roleIds): bool;

    public function syncRoles(int $userId, array $roleIds): bool;

    public function getUsersByRole(string $roleName, int $perPage = 15): LengthAwarePaginator;

    public function getUserWithRolesAndPermissions(int $id): ?User;
}
