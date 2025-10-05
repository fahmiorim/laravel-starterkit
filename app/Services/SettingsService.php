<?php

namespace App\Services;

use App\Models\Setting;
use App\Services\Contracts\SettingsServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SettingsService implements SettingsServiceInterface
{
    protected $cacheKey = 'app_settings';

    public function all()
    {
        return Cache::rememberForever($this->cacheKey, function () {
            return Setting::orderBy('group')
                ->orderBy('sort_order')
                ->get()
                ->groupBy('group');
        });
    }

    public function get($key, $default = null)
    {
        $settings = $this->all()->collapse();
        $setting = $settings->firstWhere('key', $key);
        return $setting ? $setting->value : $default;
    }

    public function set($key, $value)
    {
        $setting = Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        $this->clearCache();

        return $setting;
    }

    public function has($key): bool
    {
        return Setting::where('key', $key)->exists();
    }

    public function forget($key): bool
    {
        $deleted = Setting::where('key', $key)->delete();

        if ($deleted) {
            $this->clearCache();
        }

        return $deleted > 0;
    }

    public function save(): bool
    {
        $this->clearCache();
        return true;
    }

    public function getAllSettings(): array
    {
        return Cache::rememberForever($this->cacheKey, function () {
            return Setting::all()->pluck('value', 'key')->toArray();
        });
    }

    public function getSettingsByGroup(string $group): array
    {
        $allSettings = $this->getAllSettings();
        $groupedSettings = [];

        foreach ($allSettings as $key => $value) {
            if (str_starts_with($key, $group . '_')) {
                $settingKey = str_replace($group . '_', '', $key);
                $groupedSettings[$settingKey] = $value;
            }
        }

        return $groupedSettings;
    }

    public function getSetting(string $key, $default = null)
    {
        $settings = $this->getAllSettings();

        return $settings[$key] ?? $default;
    }

    public function setSetting(string $key, $value): bool
    {
        try {
            $this->set($key, $value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function setSettings(array $settings): bool
    {
        try {
            DB::transaction(function () use ($settings) {
                foreach ($settings as $key => $value) {
                    $this->set($key, $value);
                }
            });

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function deleteSetting(string $key): bool
    {
        return $this->forget($key);
    }

    public function getGroupedSettings(): array
    {
        $settings = $this->getAllSettings();

        return [
            'general' => [
                'app_name' => $settings['app_name'] ?? 'StarterKit',
                'app_url' => $settings['app_url'] ?? url('/'),
                'timezone' => $settings['timezone'] ?? 'UTC',
                'locale' => $settings['locale'] ?? 'en',
            ],
            'company' => [
                'company_name' => $settings['company_name'] ?? 'My Company',
                'company_address' => $settings['company_address'] ?? '',
                'company_phone' => $settings['company_phone'] ?? '',
                'company_email' => $settings['company_email'] ?? '',
                'company_logo' => $settings['company_logo'] ?? '',
            ],
            'social' => [
                'facebook_url' => $settings['facebook_url'] ?? '',
                'twitter_url' => $settings['twitter_url'] ?? '',
                'instagram_url' => $settings['instagram_url'] ?? '',
                'linkedin_url' => $settings['linkedin_url'] ?? '',
            ]
        ];
    }

    public function updateSettingsByGroup(string $group, array $settings): bool
    {
        $settingsWithPrefix = [];
        foreach ($settings as $key => $value) {
            $settingsWithPrefix[$group . '_' . $key] = $value;
        }

        return $this->setSettings($settingsWithPrefix);
    }

    public function clearCache(): void
    {
        Cache::forget($this->cacheKey);
    }

    public function getGroup($group)
    {
        $settings = $this->all();
        return $settings[$group] ?? collect();
    }

    public function getAllSettingsKeyValue()
    {
        return $this->all()->mapWithKeys(function ($group) {
            return $group->pluck('value', 'key');
        })->collapse();
    }

    public function updateFileSetting(string $key, UploadedFile $file, string $directory = 'settings', string $disk = 'public'): string
    {
        $oldPath = $this->getSetting($key);
        $path = $file->store($directory, $disk);

        try {
            if (!$this->setSetting($key, $path)) {
                throw new \RuntimeException('Unable to persist setting value.');
            }

            if ($oldPath && Storage::disk($disk)->exists($oldPath)) {
                Storage::disk($disk)->delete($oldPath);
            }

            return $path;
        } catch (Throwable $exception) {
            if ($path && Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);
            }

            throw $exception;
        }
    }

    public function deleteFileSetting(string $key, string $disk = 'public'): bool
    {
        $path = $this->getSetting($key);

        if ($path && Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }

        return $this->setSetting($key, '');
    }
}
