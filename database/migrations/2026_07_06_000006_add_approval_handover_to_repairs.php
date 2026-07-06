<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset_repairs', function (Blueprint $table) {
            // Approval
            $table->foreignId('approved_by')->nullable()->after('created_by')
                ->constrained('users', 'id_users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            
            // Handover (serah terima)
            $table->foreignId('handover_by')->nullable()->after('approved_at')
                ->constrained('users', 'id_users')->nullOnDelete();
            $table->date('handover_date')->nullable()->after('handover_by');
            
            // New condition after repair
            $table->string('new_condition_grade')->nullable()->after('handover_date');
            $table->text('handover_notes')->nullable()->after('new_condition_grade');
        });

        // Update status check constraint via raw SQL for SQLite
        // We handle valid statuses in the model/controller layer
    }

    public function down(): void
    {
        Schema::table('asset_repairs', function (Blueprint $table) {
            $table->dropColumn([
                'approved_by', 'approved_at',
                'handover_by', 'handover_date',
                'new_condition_grade', 'handover_notes',
            ]);
        });
    }
};
