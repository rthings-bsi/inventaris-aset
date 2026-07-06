<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_audits', function (Blueprint $table) {
            $table->string('audit_type')->default('full')->after('description');
            $table->string('frequency')->nullable()->after('audit_type');
            $table->date('next_audit_date')->nullable()->after('status');
            $table->json('selected_assets')->nullable()->after('next_audit_date');
        });
    }

    public function down(): void
    {
        Schema::table('asset_audits', function (Blueprint $table) {
            $table->dropColumn(['audit_type', 'frequency', 'next_audit_date', 'selected_assets']);
        });
    }
};
