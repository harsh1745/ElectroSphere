<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // ✅ FIX: Slug column add kiya
            $table->string('slug')->unique()->nullable()->after('name'); 
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Rollback ke liye column drop karna
            $table->dropColumn('slug');
        });
    }
};