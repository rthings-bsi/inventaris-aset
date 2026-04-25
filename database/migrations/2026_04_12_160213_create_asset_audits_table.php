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
        Schema::create('asset_audits', function (Blueprint $table) {
            $table->id('id_asset_audits');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('audit_date');
            $table->string('status')->default('open'); // open, completed
            $table->foreignId('created_by')->nullable()->constrained('users', 'id_users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_audits');
    }
};
