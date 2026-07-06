<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_audit_items', function (Blueprint $table) {
            $table->decimal('condition_score', 5, 2)->nullable()->after('status');
            $table->string('condition_grade')->nullable()->after('condition_score');
            $table->json('criteria_data')->nullable()->after('notes');
            $table->string('checklist_status')->default('pending')->after('criteria_data');
        });
    }

    public function down(): void
    {
        Schema::table('asset_audit_items', function (Blueprint $table) {
            $table->dropColumn(['condition_score', 'condition_grade', 'criteria_data', 'checklist_status']);
        });
    }
};
