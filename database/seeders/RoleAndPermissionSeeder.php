<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User Management
            'view_users', 'create_users', 'edit_users', 'delete_users',
            // Role Management
            'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
            // Permission Management
            'view_permissions', 'create_permissions', 'edit_permissions', 'delete_permissions',
            // Donor Management
            'view_donors', 'create_donors', 'edit_donors', 'delete_donors',
            // Donor History
            'view_donor_histories', 'create_donor_histories', 'edit_donor_histories', 'delete_donor_histories',
            // Donor Cards
            'view_donor_cards', 'create_donor_cards', 'edit_donor_cards', 'delete_donor_cards',
            // Donation Schedules
            'view_schedules', 'create_schedules', 'edit_schedules', 'delete_schedules', 'publish_schedules',
            // Blood Stocks
            'view_blood_stocks', 'update_blood_stocks',
            // Blood Requests
            'view_blood_requests', 'create_blood_requests', 'edit_blood_requests', 'delete_blood_requests', 'approve_blood_requests',
            // Reports
            'view_reports', 'generate_reports',
            // Settings
            'manage_settings',
            // Activity Logs
            'view_activity_logs'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($permissions);

        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $staffRole->syncPermissions([
            'view_donors', 'create_donors', 'edit_donors',
            'view_donor_histories', 'create_donor_histories',
            'view_donor_cards', 'create_donor_cards', 'edit_donor_cards',
            'view_schedules', 'create_schedules', 'edit_schedules',
            'view_blood_stocks', 'update_blood_stocks',
            'view_blood_requests', 'create_blood_requests', 'edit_blood_requests'
        ]);

        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->syncPermissions([
            'view_blood_stocks',
            'view_schedules'
        ]);

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
                'phone' => '1234567890',
            ]
        );
        $admin->assignRole($adminRole);

        // Create staff user
        $staff = User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Staff',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
                'phone' => '1234567891',
            ]
        );
        $staff->assignRole($staffRole);

        // Create regular user
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
                'phone' => '1234567892',
            ]
        );
        $user->assignRole($userRole);
    }
}
