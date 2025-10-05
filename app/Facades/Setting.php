<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get(string $key, mixed $default = null)
 * @method static mixed set(string $key, mixed $value = null)
 * @method static bool has(string $key)
 * @method static array all()
 * @method static void forget(string $key)
 * @method static void save()
 *
 * @see \App\Services\SettingsService
 */
class Setting extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'setting';
    }
}
