<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        $adminRole = \App\Models\Role::updateOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrator',
                'description' => 'Full access to all system modules and settings.'
            ]
        );

        $staffRole = \App\Models\Role::updateOrCreate(
            ['slug' => 'staff'],
            [
                'name' => 'Staff',
                'description' => 'Limited access for asset management and loan operations.'
            ]
        );

        // 2. Seed Permissions
        $permissions = [
            'Ringkasan & Dashboard' => [
                'dashboard.view' => 'Lihat Dashboard',
            ],
            'Manajemen Aset' => [
                'asset.view' => 'Lihat Aset',
                'asset.create' => 'Tambah Aset',
                'asset.edit' => 'Edit Aset',
                'asset.delete' => 'Hapus Aset',
                'asset.bulk-delete' => 'Hapus Massal (Bulk)',
            ],
            'Alur Peminjaman' => [
                'loan.view' => 'Lihat Riwayat Peminjaman',
                'loan.create' => 'Ajukan Peminjaman',
                'loan.manage' => 'Persetujuan (Approve/Reject)',
            ],
            'Data Master' => [
                'master-data.view' => 'Lihat Master Data',
                'master-data.manage' => 'Kelola Master Data',
            ],
            'Sistem & Laporan' => [
                'user.manage' => 'Kelola Pengguna',
                'report.export' => 'Export Laporan',
            ]
        ];

        // Flat permission list for Admin
        $allPermissions = [];
        foreach ($permissions as $group => $perms) {
            foreach ($perms as $slug => $name) {
                $allPermissions[] = $slug;
            }
        }

        // Permissions for Staff
        $staffPermissions = [
            'dashboard.view',
            'asset.view',
            'asset.create',
            'loan.view',
            'loan.create',
            'master-data.view',
            'report.export'
        ];

        // Seed permissions for Admin
        foreach ($allPermissions as $perm) {
            \App\Models\RolePermission::updateOrCreate([
                'role' => 'admin',
                'permission' => $perm
            ]);
        }

        // Seed permissions for Staff
        foreach ($staffPermissions as $perm) {
            \App\Models\RolePermission::updateOrCreate([
                'role' => 'staff',
                'permission' => $perm
            ]);
        }

        // 3. Seed Users
        User::updateOrCreate(
            ['email' => 'admin@aset.local'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@aset.local'],
            [
                'name' => 'Staff Aset',
                'password' => Hash::make('staff12345'),
                'role' => 'staff',
            ]
        );
    }
}
