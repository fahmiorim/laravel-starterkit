<?php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\ProfileServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileService implements ProfileServiceInterface
{
    /**
     * Get user profile
     */
    public function getUserProfile(int $userId): ?User
    {
        return User::with(['roles', 'permissions'])->find($userId);
    }

    /**
     * Update user profile
     */
    public function updateProfile(int $userId, array $data): bool
    {
        $user = User::find($userId);

        if (!$user) {
            return false;
        }

        if (isset($data['email']) && $user->email !== $data['email']) {
            $data['email_verified_at'] = null;
        }

        return $user->update($data);
    }

    /**
     * Update user password
     */
    public function updatePassword(int $userId, string $currentPassword, string $newPassword): bool
    {
        $user = User::find($userId);

        if (!$user) {
            return false;
        }

        if (!Hash::check($currentPassword, $user->password)) {
            return false;
        }

        $user->password = Hash::make($newPassword);
        return $user->save();
    }

    /**
     * Force update password without validating the current one.
     */
    public function forceUpdatePassword(int $userId, string $newPassword): bool
    {
        $user = User::find($userId);

        if (!$user) {
            return false;
        }

        $user->password = Hash::make($newPassword);
        return $user->save();
    }

    /**
     * Update user avatar
     */
    public function updateAvatar(int $userId, $avatarFile): bool
    {
        $user = User::find($userId);

        if (!$user) {
            return false;
        }

        try {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $avatarFile->store('avatars', 'public');
            $user->avatar = $path;

            return $user->save();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Delete user avatar
     */
    public function deleteAvatar(int $userId): bool
    {
        $user = User::find($userId);

        if (!$user || !$user->avatar) {
            return false;
        }

        try {
            if (Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = null;
            return $user->save();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get user activity (placeholder for future implementation)
     */
    public function getUserActivity(int $userId, int $limit = 10): Collection
    {
        return collect();
    }

    /**
     * Get user statistics
     */
    public function getUserStats(int $userId): array
    {
        $user = User::find($userId);

        if (!$user) {
            return [];
        }

        return [
            'total_logins' => 0,
            'last_login' => $user->last_activity,
            'account_created' => $user->created_at,
            'roles_count' => $user->roles()->count(),
            'permissions_count' => $user->permissions()->count(),
            'is_active' => $user->is_active,
        ];
    }

    /**
     * Update user preferences
     */
    public function updatePreferences(int $userId, array $preferences): bool
    {
        $user = User::find($userId);

        if (!$user) {
            return false;
        }

        $user->preferences = json_encode($preferences);
        return $user->save();
    }

    /**
     * Get user notifications (placeholder for future implementation)
     */
    public function getUserNotifications(int $userId, int $limit = 20): Collection
    {
        return collect();
    }

    /**
     * Check if current user can edit target user profile
     */
    public function canEditProfile(int $targetUserId): bool
    {
        $currentUser = Auth::user();

        if (!$currentUser) {
            return false;
        }

        if ($currentUser->id === $targetUserId) {
            return true;
        }

        if ($currentUser->hasRole('admin')) {
            return true;
        }

        if ($currentUser->can('manage_users')) {
            return true;
        }

        return false;
    }

    /**
     * Get profile completion percentage
     */
    public function getProfileCompletion(int $userId): int
    {
        $user = User::find($userId);

        if (!$user) {
            return 0;
        }

        $totalFields = 6;
        $completedFields = 0;

        if (!empty($user->name)) {
            $completedFields++;
        }
        if (!empty($user->email)) {
            $completedFields++;
        }
        if (!empty($user->phone)) {
            $completedFields++;
        }
        if (!empty($user->avatar)) {
            $completedFields++;
        }
        if ($user->last_activity) {
            $completedFields++;
        }
        if ($user->preferences) {
            $completedFields++;
        }

        return min(100, round(($completedFields / $totalFields) * 100));
    }

    /**
     * Delete user profile
     */
    public function deleteProfile(int $userId): bool
    {
        $user = User::find($userId);

        if (!$user) {
            return false;
        }

        return $user->delete();
    }
}
