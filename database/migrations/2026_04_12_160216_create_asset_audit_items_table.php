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
        Schema::create('asset_audit_items', function (Blueprint $table) {
            $table->id('id_asset_audit_items');
            $table->foreignId('id_asset_audits')->constrained('asset_audits', 'id_asset_audits')->onDelete('cascade');
            $table->foreignId('id_assets')->nullable()->constrained('assets', 'id_assets')->onDelete('cascade');
            $table->string('scanned_code'); // what was actually scanned
            $table->string('status')->nullable(); // present, damaged, etc.
            $table->text('notes')->nullable();
            $table->timestamp('scanned_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_audit_items');
    }
};
