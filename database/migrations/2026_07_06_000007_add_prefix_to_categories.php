<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('code_prefix', 10)->nullable()->after('category_name');
        });

        // Auto-generate prefixes for existing categories
        $categories = DB::table('categories')->get();
        foreach ($categories as $cat) {
            $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $cat->category_name), 0, 3));
            DB::table('categories')->where('id_categories', $cat->id_categories)->update(['code_prefix' => $prefix]);
        }
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('code_prefix');
        });
    }
};
