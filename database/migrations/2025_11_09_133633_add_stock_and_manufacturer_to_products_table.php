<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // ✅ Directly add the columns (no Schema::hasColumn)
            $table->string('manufacturer')->nullable()->after('description');
            $table->string('stock_status')->default('in_stock')->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // ✅ Drop columns directly (no Schema::hasColumn)
            $table->dropColumn(['manufacturer', 'stock_status']);
        });
    }
};
