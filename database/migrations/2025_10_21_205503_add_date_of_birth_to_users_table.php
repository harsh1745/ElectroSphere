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
        Schema::table('users', function (Blueprint $table) {
            // Naya 'date' type column 'date_of_birth' add kiya gaya.
            // 'email' ke baad field add kiya hai.
            $table->date('date_of_birth')->nullable()->after('email'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Agar rollback (migrate:rollback) karein, toh yeh column delete ho jayega.
            $table->dropColumn('date_of_birth');
        });
    }
};
