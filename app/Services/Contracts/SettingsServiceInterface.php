<?php

namespace App\Services\Contracts;

use Illuminate\Http\UploadedFile;

interface SettingsServiceInterface
{
    public function getAllSettings(): array;

    public function getSettingsByGroup(string $group): array;

    public function getSetting(string $key, $default = null);

    public function setSetting(string $key, $value): bool;

    public function setSettings(array $settings): bool;

    public function deleteSetting(string $key): bool;

    public function getGroupedSettings(): array;

    public function updateSettingsByGroup(string $group, array $settings): bool;

    public function clearCache(): void;

    public function updateFileSetting(string $key, UploadedFile $file, string $directory = 'settings', string $disk = 'public'): string;

    public function deleteFileSetting(string $key, string $disk = 'public'): bool;
}
