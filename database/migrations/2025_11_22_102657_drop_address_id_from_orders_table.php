<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            
            // 1. ✅ Drop Foreign Key Constraint
            // Yeh step zaroori hai agar woh foreign key hai
            try {
                $table->dropForeign(['address_id']); 
            } catch (\Exception $e) {
                // Ignore error if FK constraint doesn't exist (purane MySQL ke liye)
            }
            
            // 2. ✅ Drop Column
            $table->dropColumn('address_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Agar aapko rollback karna ho toh address_id ko wapas add karna padega
            $table->foreignId('address_id')->nullable()->constrained('addresses')->after('user_id');
        });
    }
};