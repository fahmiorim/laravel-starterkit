<?php

return [
    /*
    |--------------------------------------------------------------------------
    | General Settings
    |--------------------------------------------------------------------------
    */
    'app_name' => env('APP_NAME', 'Laravel Starter Kit'),
    'app_url' => env('APP_URL', 'http://localhost'),
    'timezone' => env('APP_TIMEZONE', 'UTC'),
    'locale' => env('APP_LOCALE', 'en'),
    
    /*
    |--------------------------------------------------------------------------
    | Company Settings
    |--------------------------------------------------------------------------
    */
    'company_name' => env('COMPANY_NAME', 'My Company'),
    'company_address' => env('COMPANY_ADDRESS', ''),
    'company_phone' => env('COMPANY_PHONE', ''),
    'company_email' => env('COMPANY_EMAIL', ''),
    'company_logo' => env('COMPANY_LOGO', ''),
    
    /*
    |--------------------------------------------------------------------------
    | Social Media Settings
    |--------------------------------------------------------------------------
    */
    'facebook_url' => env('FACEBOOK_URL', ''),
    'twitter_url' => env('TWITTER_URL', ''),
    'instagram_url' => env('INSTAGRAM_URL', ''),
    'linkedin_url' => env('LINKEDIN_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Settings Table Name
    |--------------------------------------------------------------------------
    |
    | This is the table name where all settings will be stored.
    |
    */
    'table' => 'settings',

    /*
    |--------------------------------------------------------------------------
    | Settings Cache Key
    |--------------------------------------------------------------------------
    |
    | This is the cache key that will be used to store all settings in the cache.
    |
    */
    'cache_key' => 'app_settings',

    /*
    |--------------------------------------------------------------------------
    | Settings Groups
    |--------------------------------------------------------------------------
    |
    | Define your settings groups here. Each group will be displayed as a tab
    | in the settings page.
    |
    */
    'groups' => [
        'general' => [
            'name' => 'General',
            'icon' => 'heroicon-o-cog',
            'sort_order' => 1,
        ],
        'company' => [
            'name' => 'Company',
            'icon' => 'heroicon-o-office-building',
            'sort_order' => 2,
        ],
        'social' => [
            'name' => 'Social Media',
            'icon' => 'heroicon-o-share',
            'sort_order' => 3,
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Settings Fields
    |--------------------------------------------------------------------------
    |
    | Define all of the settings fields that will be available in the application.
    | Each setting must have a unique key and can have the following properties:
    |
    | - type: The type of the field (text, textarea, email, number, select, checkbox, file, etc.)
    | - label: The display name of the field
    | - group: The group this setting belongs to
    | - options: For select fields, an array of options in key-value pairs
    | - validation: Validation rules for the field
    | - default: Default value for the field
    |
    */
    'fields' => [
        // General Settings
        'app_name' => [
            'type' => 'text',
            'label' => 'Application Name',
            'group' => 'general',
            'validation' => 'required|string|max:255',
            'sort_order' => 1,
        ],
        'app_logo' => [
            'type' => 'image',
            'label' => 'Application Logo',
            'group' => 'general',
            'validation' => 'nullable|image|max:2048',
            'sort_order' => 2,
        ],
        'app_favicon' => [
            'type' => 'image',
            'label' => 'Favicon',
            'group' => 'general',
            'validation' => 'nullable|image|max:1024',
            'sort_order' => 3,
        ],
        'timezone' => [
            'type' => 'select',
            'label' => 'Timezone',
            'group' => 'general',
            'options' => [
                'UTC' => 'UTC',
                'Asia/Jakarta' => 'WIB (UTC+7)',
                'Asia/Makassar' => 'WITA (UTC+8)',
                'Asia/Jayapura' => 'WIT (UTC+9)',
            ],
            'validation' => 'required|string',
            'sort_order' => 4,
        ],

        // Company Information
        'company_name' => [
            'type' => 'text',
            'label' => 'Company Name',
            'group' => 'company',
            'validation' => 'required|string|max:255',
            'sort_order' => 1,
        ],
        'company_address' => [
            'type' => 'textarea',
            'label' => 'Address',
            'group' => 'company',
            'validation' => 'nullable|string',
            'sort_order' => 2,
        ],
        'company_phone' => [
            'type' => 'text',
            'label' => 'Phone',
            'group' => 'company',
            'validation' => 'nullable|string|max:50',
            'sort_order' => 3,
        ],
        'company_email' => [
            'type' => 'email',
            'label' => 'Email',
            'group' => 'company',
            'validation' => 'nullable|email|max:255',
            'sort_order' => 4,
        ],
        'company_website' => [
            'type' => 'url',
            'label' => 'Website',
            'group' => 'company',
            'validation' => 'nullable|url|max:255',
            'sort_order' => 5,
        ],

        // Social Media
        'facebook_url' => [
            'type' => 'url',
            'label' => 'Facebook URL',
            'group' => 'social',
            'validation' => 'nullable|url|max:255',
            'sort_order' => 1,
        ],
        'twitter_url' => [
            'type' => 'url',
            'label' => 'Twitter URL',
            'group' => 'social',
            'validation' => 'nullable|url|max:255',
            'sort_order' => 2,
        ],
        'instagram_url' => [
            'type' => 'url',
            'label' => 'Instagram URL',
            'group' => 'social',
            'validation' => 'nullable|url|max:255',
            'sort_order' => 3,
        ],
        'linkedin_url' => [
            'type' => 'url',
            'label' => 'LinkedIn URL',
            'group' => 'social',
            'validation' => 'nullable|url|max:255',
            'sort_order' => 4,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Settings Page
    |--------------------------------------------------------------------------
    |
    | Configure the settings page.
    |
    */
    'page' => [
        'title' => 'Settings',
        'navigation_label' => 'Settings',
        'navigation_icon' => 'heroicon-o-cog',
        'navigation_group' => 'Settings',
        'navigation_sort' => 100,
    ],
];
