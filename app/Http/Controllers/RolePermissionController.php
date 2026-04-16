<?php

namespace App\Http\Controllers;

use App\Models\RolePermission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function index()
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $roles = \App\Models\Role::all();
        $groupedPermissions = [
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

        $rolePermissions = [];
        foreach ($roles as $role) {
            $rolePermissions[$role->slug] = RolePermission::where('role', $role->slug)->pluck('permission')->toArray();
        }

        return view('settings.roles', compact('roles', 'groupedPermissions', 'rolePermissions'));
    }

    public function update(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'nullable|array'
        ]);

        $roles = \App\Models\Role::all();
        $inputPermissions = $request->input('permissions', []);

        foreach ($roles as $role) {
            // Clear existing permissions for this role
            RolePermission::where('role', $role->slug)->delete();
            
            if (isset($inputPermissions[$role->slug])) {
                foreach ($inputPermissions[$role->slug] as $perm => $val) {
                    RolePermission::create([
                        'role' => $role->slug,
                        'permission' => $perm
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Pengaturan hak akses role berhasil diperbarui!');
    }
}
