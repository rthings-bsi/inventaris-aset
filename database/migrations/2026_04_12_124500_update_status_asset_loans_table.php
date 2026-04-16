<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Try native change if Doctrine DBAL is installed or Laravel 11 natively supports it
        // Or handle ENUM change via raw DB statement if it's MySQL
        
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE asset_loans MODIFY COLUMN status VARCHAR(255) DEFAULT 'pending'");
        } elseif ($driver === 'pgsql') {
            DB::statement("ALTER TABLE asset_loans ALTER COLUMN status TYPE VARCHAR(255)");
            DB::statement("ALTER TABLE asset_loans ALTER COLUMN status SET DEFAULT 'pending'");
        } else {
            Schema::table('asset_loans', function (Blueprint $table) {
                // Fallback for sqlite / others
                $table->string('status')->default('pending')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Assuming MySQL for rollback example to enum, but safe to leave as string
        $driver = DB::connection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE asset_loans MODIFY COLUMN status ENUM('borrowed', 'returned') DEFAULT 'borrowed'");
        } else {
            Schema::table('asset_loans', function (Blueprint $table) {
                $table->string('status')->default('borrowed')->change();
            });
        }
    }
};
