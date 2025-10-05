<?php

namespace App\Services\Contracts;

use App\Models\User;

interface ProfileServiceInterface
{
    public function getUserProfile(int $userId): ?User;

    public function updateProfile(int $userId, array $data): bool;

    public function updatePassword(int $userId, string $currentPassword, string $newPassword): bool;

    public function forceUpdatePassword(int $userId, string $newPassword): bool;

    public function updateAvatar(int $userId, $avatarFile): bool;

    public function deleteAvatar(int $userId): bool;

    public function getUserActivity(int $userId, int $limit = 10): \Illuminate\Database\Eloquent\Collection;

    public function getUserStats(int $userId): array;

    public function updatePreferences(int $userId, array $preferences): bool;

    public function getUserNotifications(int $userId, int $limit = 20): \Illuminate\Database\Eloquent\Collection;

    public function canEditProfile(int $targetUserId): bool;

    public function getProfileCompletion(int $userId): int;

    public function deleteProfile(int $userId): bool;
}
