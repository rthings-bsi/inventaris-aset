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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code')->unique();
            $table->string('asset_name');
            $table->text('description')->nullable();
            $table->string('category');
            $table->decimal('acquisition_cost', 15, 2);
            $table->date('acquisition_date');
            $table->string('condition');
            $table->string('location');
            $table->string('person_in_charge')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['active', 'maintenance', 'broken', 'disposed'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
