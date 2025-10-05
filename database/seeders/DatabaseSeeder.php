<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run seeders in the correct order
        $this->call([
            // 1. Create roles and permissions first
            RoleAndPermissionSeeder::class,
            // 2. Create users with assigned roles
            UserSeeder::class,
            // 3. Create system settings
            SettingsTableSeeder::class,
            // 4. Create donors
            DonorSeeder::class,
            // 5. Create donation schedules
            DonationScheduleSeeder::class,
            // 6. Create blood stocks
            BloodStockSeeder::class,
            // 7. Create donor histories
            DonorHistorySeeder::class,
            // 8. Create blood requests
            BloodRequestSeeder::class,
        ]);
    }
}
