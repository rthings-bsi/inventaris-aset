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
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['category', 'location', 'person_in_charge']);
            $table->foreignId('id_categories')->nullable()->constrained('categories', 'id_categories')->nullOnDelete();
            $table->foreignId('id_locations')->nullable()->constrained('locations', 'id_locations')->nullOnDelete();
            $table->foreignId('id_users')->nullable()->constrained('users', 'id_users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['id_categories']);
            $table->dropForeign(['id_locations']);
            $table->dropForeign(['id_users']);
            $table->dropColumn(['id_categories', 'id_locations', 'id_users']);
            $table->string('category');
            $table->string('location');
            $table->string('person_in_charge')->nullable();
        });
    }
};
