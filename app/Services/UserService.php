<?php

namespace App\Services;

use App\DTOs\UserData;
use App\Events\UserCreated;
use App\Events\UserStatusChanged;
use App\Models\User;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService implements UserServiceInterface
{
    public function getAllUsers(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = User::with('roles')->latest();

        if (isset($filters['role']) && !empty($filters['role'])) {
            $query->whereHas('roles', function ($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->paginate($perPage);
    }

    public function getUserById(int $id): ?User
    {
        return User::with('roles')->find($id);
    }

    public function createUser(array $data): User
    {
        $userData = UserData::fromRequest(request());

        return DB::transaction(function () use ($userData) {
            $user = User::create([
                'name' => $userData->name,
                'email' => $userData->email,
                'password' => Hash::make($userData->password),
                'phone' => $userData->phone,
                'is_active' => $userData->is_active,
            ]);

            // Dispatch event for user creation
            UserCreated::dispatch($user);

            return $user;
        });
    }

    public function updateUser(int $id, array $data): bool
    {
        $userData = UserData::fromUpdateRequest(request());
        $oldUser = User::find($id);

        return DB::transaction(function () use ($id, $userData, $oldUser) {
            $user = User::find($id);

            if (!$user) {
                return false;
            }

            $updateData = $userData->toArray();

            // Handle email verification reset if email changed
            if ($user->email !== $userData->email) {
                $updateData['email_verified_at'] = null;
            }

            $updated = $user->update($updateData);

            // Dispatch event for status change if status was changed
            if ($updated && $oldUser && $oldUser->is_active !== $userData->is_active) {
                UserStatusChanged::dispatch($user, $oldUser->is_active, $userData->is_active);
            }

            return $updated;
        });
    }

    public function deleteUser(int $id): bool
    {
        $user = User::find($id);

        if (!$user) {
            return false;
        }

        return $user->delete();
    }

    public function toggleUserStatus(int $id): bool
    {
        $user = User::find($id);

        if (!$user) {
            return false;
        }

        $oldStatus = $user->is_active;
        $updated = $user->update(['is_active' => !$user->is_active]);

        if ($updated) {
            UserStatusChanged::dispatch($user, $oldStatus, $user->is_active);
        }

        return $updated;
    }

    public function assignRoles(int $userId, array $roleIds): bool
    {
        $user = User::find($userId);

        if (!$user) {
            return false;
        }

        $roles = Role::whereIn('id', $roleIds)->get();
        $user->syncRoles($roles);

        return true;
    }

    public function syncRoles(int $userId, array $roleIds): bool
    {
        return $this->assignRoles($userId, $roleIds);
    }

    public function getUsersByRole(string $roleName, int $perPage = 15): LengthAwarePaginator
    {
        return User::with('roles')
            ->whereHas('roles', function ($query) use ($roleName) {
                $query->where('name', $roleName);
            })
            ->paginate($perPage);
    }

    public function getUserWithRolesAndPermissions(int $id): ?User
    {
        return User::with(['roles', 'permissions'])->find($id);
    }
}
