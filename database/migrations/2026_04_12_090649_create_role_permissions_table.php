<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('role'); // admin, staff
            $blueprint->string('permission'); // e.g. asset.create
            $blueprint->timestamps();

            $blueprint->unique(['role', 'permission']);
        });

        // Seed default permissions
        $adminPermissions = [
            'asset.view', 'asset.create', 'asset.edit', 'asset.delete', 'asset.bulk-delete',
            'master-data.view', 'master-data.manage', 'user.manage', 'report.export'
        ];

        foreach ($adminPermissions as $permission) {
            \Illuminate\Support\Facades\DB::table('role_permissions')->insert([
                'role' => 'admin',
                'permission' => $permission,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Default staff permissions
        $staffPermissions = ['asset.view', 'report.export'];
        foreach ($staffPermissions as $permission) {
            \Illuminate\Support\Facades\DB::table('role_permissions')->insert([
                'role' => 'staff',
                'permission' => $permission,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
