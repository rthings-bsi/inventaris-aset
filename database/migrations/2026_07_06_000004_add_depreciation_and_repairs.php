<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->decimal('residual_value', 15, 2)->nullable()->after('acquisition_cost');
            $table->integer('useful_life_years')->nullable()->after('residual_value');
            $table->string('depreciation_method')->default('straight_line')->after('useful_life_years');
        });

        Schema::create('asset_repairs', function (Blueprint $table) {
            $table->id('id_asset_repairs');
            $table->foreignId('id_assets')->constrained('assets', 'id_assets')->onDelete('cascade');
            $table->date('repair_date');
            $table->text('description');
            $table->string('repair_type')->default('maintenance'); // maintenance, damage, warranty
            $table->decimal('cost', 15, 2)->default(0);
            $table->string('vendor')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('completed'); // pending, in_progress, completed
            $table->foreignId('created_by')->nullable()->constrained('users', 'id_users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_repairs');
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['residual_value', 'useful_life_years', 'depreciation_method']);
        });
    }
};
