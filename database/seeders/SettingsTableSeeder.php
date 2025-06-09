<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'app_name',
                'value' => 'Laravel Starter Kit',
                'group' => 'general',
                'type' => 'text',
                'label' => 'Nama Aplikasi',
                'sort_order' => 1,
            ],
            [
                'key' => 'app_logo',
                'value' => 'logo.png',
                'group' => 'general',
                'type' => 'image',
                'label' => 'Logo Aplikasi',
                'sort_order' => 2,
            ],
            [
                'key' => 'app_favicon',
                'value' => 'favicon.ico',
                'group' => 'general',
                'type' => 'image',
                'label' => 'Favicon',
                'sort_order' => 3,
            ],
            [
                'key' => 'timezone',
                'value' => 'Asia/Jakarta',
                'group' => 'general',
                'type' => 'select',
                'label' => 'Zona Waktu',
                'options' => json_encode([
                    'Asia/Jakarta' => 'WIB (UTC+7)',
                    'Asia/Makassar' => 'WITA (UTC+8)',
                    'Asia/Jayapura' => 'WIT (UTC+9)',
                ]),
                'sort_order' => 4,
            ],

            // Company Information
            [
                'key' => 'company_name',
                'value' => 'PT. Perusahaan Anda',
                'group' => 'company',
                'type' => 'text',
                'label' => 'Nama Perusahaan',
                'sort_order' => 1,
            ],
            [
                'key' => 'company_address',
                'value' => 'Alamat Perusahaan',
                'group' => 'company',
                'type' => 'textarea',
                'label' => 'Alamat',
                'sort_order' => 2,
            ],
            [
                'key' => 'company_phone',
                'value' => '+62 123 4567 890',
                'group' => 'company',
                'type' => 'text',
                'label' => 'Telepon',
                'sort_order' => 3,
            ],
            [
                'key' => 'company_email',
                'value' => 'info@perusahaan.com',
                'group' => 'company',
                'type' => 'email',
                'label' => 'Email',
                'sort_order' => 4,
            ],
            [
                'key' => 'company_website',
                'value' => 'https://perusahaan.com',
                'group' => 'company',
                'type' => 'url',
                'label' => 'Website',
                'sort_order' => 5,
            ],

            // Social Media
            [
                'key' => 'facebook_url',
                'value' => 'https://facebook.com',
                'group' => 'social',
                'type' => 'url',
                'label' => 'Facebook',
                'sort_order' => 1,
            ],
            [
                'key' => 'twitter_url',
                'value' => 'https://twitter.com',
                'group' => 'social',
                'type' => 'url',
                'label' => 'Twitter',
                'sort_order' => 2,
            ],
            [
                'key' => 'instagram_url',
                'value' => 'https://instagram.com',
                'group' => 'social',
                'type' => 'url',
                'label' => 'Instagram',
                'sort_order' => 3,
            ],
            [
                'key' => 'linkedin_url',
                'value' => 'https://linkedin.com',
                'group' => 'social',
                'type' => 'url',
                'label' => 'LinkedIn',
                'sort_order' => 4,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
