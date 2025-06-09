<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'label',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue($key, $value)
    {
        try {
            self::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'label' => ucwords(str_replace('_', ' ', $key))
                ]
            );
            return true;
        } catch (\Exception $e) {
            \Log::error("Error saving setting: " . $e->getMessage());
            return false;
        }
    }
}
