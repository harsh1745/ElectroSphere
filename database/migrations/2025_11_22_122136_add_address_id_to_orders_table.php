<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // Safest: Only add if column does NOT exist
            if (!Schema::hasColumn('orders', 'address_id')) {
                $table->unsignedBigInteger('address_id')->nullable()->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'address_id')) {

                // Drop foreign key (if exists)
                try {
                    $table->dropForeign(['address_id']);
                } catch (\Exception $e) {
                }

                // Drop the column
                $table->dropColumn('address_id');
            }
        });
    }
};
