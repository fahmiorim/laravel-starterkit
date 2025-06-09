<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    protected $cacheKey = 'app_settings';

    /**
     * Get all settings
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        \Log::debug('SettingService - Getting all settings from cache or database');
        
        return Cache::rememberForever($this->cacheKey, function () {
            \Log::debug('SettingService - Cache miss, loading settings from database');
            
            try {
                $settings = Setting::orderBy('group')
                    ->orderBy('sort_order')
                    ->get()
                    ->groupBy('group');
                
                \Log::debug('SettingService - Loaded settings from database', [
                    'count' => $settings->count(),
                    'groups' => $settings->keys()
                ]);
                
                return $settings;
                
            } catch (\Exception $e) {
                \Log::error('SettingService - Error loading settings from database: ' . $e->getMessage());
                return collect();
            }
        });
    }


    public function get($key, $default = null)
    {
        $settings = $this->all()->collapse();
        $setting = $settings->firstWhere('key', $key);
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @return Setting
     */
    public function set($key, $value)
    {
        $setting = Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        
        $this->clearCache();
        
        return $setting;
    }

    /**
     * Get settings by group
     *
     * @param string $group
     * @return \Illuminate\Support\Collection
     */
    public function getGroup($group)
    {
        $settings = $this->all();
        return $settings[$group] ?? collect();
    }

    /**
     * Clear settings cache
     *
     * @return void
     */
    public function clearCache()
    {
        Cache::forget($this->cacheKey);
    }

    /**
     * Get all settings as key-value pairs
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllSettings()
    {
        return $this->all()->mapWithKeys(function ($group) {
            return $group->pluck('value', 'key');
        })->collapse();
    }
}
