<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create permissions
        $permissions = [
            'manage_users',
            'manage_donors',
            'manage_blood_requests',
            'manage_blood_stocks',
            'view_reports',
            'manage_settings'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $adminRole->givePermissionTo($permission);
        }

        // Assign admin role to first user (or create an admin user)
        $adminUser = User::first();
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }

        $this->command->info('Admin role and permissions created successfully.');
    }
}
