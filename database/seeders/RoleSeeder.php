<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = \App\Models\Role::updateOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Administrator', 'description' => 'Akses penuh ke seluruh sistem']
        );

        $staffRole = \App\Models\Role::updateOrCreate(
            ['slug' => 'staff'],
            ['name' => 'Staff', 'description' => 'Akses terbatas untuk operasional harian']
        );

        // Default Permissions for Admin
        $allPermissions = [
            'dashboard.view', 'asset.view', 'asset.create', 'asset.edit', 'asset.delete', 'asset.bulk-delete',
            'loan.view', 'loan.create', 'loan.manage',
            'master-data.view', 'master-data.manage',
            'user.manage', 'report.export'
        ];

        foreach ($allPermissions as $perm) {
            \App\Models\RolePermission::updateOrCreate(
                ['role' => 'admin', 'permission' => $perm]
            );
        }

        // Default Permissions for Staff
        $staffPermissions = [
            'dashboard.view', 'asset.view', 'loan.view', 'loan.create'
        ];

        foreach ($staffPermissions as $perm) {
            \App\Models\RolePermission::updateOrCreate(
                ['role' => 'staff', 'permission' => $perm]
            );
        }
    }
}
