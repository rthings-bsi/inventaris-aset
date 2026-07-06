<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_repairs', function (Blueprint $table) {
            $table->foreignId('id_asset_loans')->nullable()->after('id_assets')
                ->constrained('asset_loans', 'id_asset_loans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('asset_repairs', function (Blueprint $table) {
            $table->dropForeign(['id_asset_loans']);
            $table->dropColumn('id_asset_loans');
        });
    }
};
