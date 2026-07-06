<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_criteria_groups', function (Blueprint $table) {
            $table->id('id_criteria_groups');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category_type')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_criteria_items', function (Blueprint $table) {
            $table->id('id_criteria_items');
            $table->foreignId('id_criteria_groups')->constrained('audit_criteria_groups', 'id_criteria_groups')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('weight')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_criteria_items');
        Schema::dropIfExists('audit_criteria_groups');
    }
};
