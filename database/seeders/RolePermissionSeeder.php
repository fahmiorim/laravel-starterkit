<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_users',
            'manage_users',
            'view_roles',
            'manage_roles',
            'view_permissions',
            'manage_permissions',
            'view_donors',
            'manage_donors',
            'print_kta',
            'view_schedules',
            'manage_schedules',
            'publish_schedules',
            'view_reports',
            'export_reports',
            'view_blood_stocks',
            'manage_blood_stocks',
            'view_blood_requests',
            'manage_blood_requests',
            'view_settings',
            'manage_settings',
            'view_own_history',
            'register_for_donation',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission], ['guard_name' => 'web']);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        $petugasRole = Role::firstOrCreate(['name' => 'petugas'], ['guard_name' => 'web']);
        $petugasPermissions = [
            'view_users',
            'view_donors',
            'manage_donors',
            'print_kta',
            'view_schedules',
            'manage_schedules',
            'publish_schedules',
            'view_reports',
            'export_reports',
            'view_blood_stocks',
            'manage_blood_stocks',
            'view_blood_requests',
            'manage_blood_requests',
        ];
        $petugasRole->syncPermissions($petugasPermissions);

        $donorRole = Role::firstOrCreate(['name' => 'pendonor'], ['guard_name' => 'web']);
        $donorPermissions = [
            'view_own_history',
            'register_for_donation',
            'view_schedules',
        ];
        $donorRole->syncPermissions($donorPermissions);
    }
}
