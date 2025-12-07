<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\Type; // Required for changing column type

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            
            // 1. Rating Field Update (Agar pehle simple integer tha)
            // Doctrine/DBAL package required ho sakta hai: composer require doctrine/dbal
            // ✅ CHANGE: Data type ko unsignedTinyInteger mein badlo
            $table->unsignedTinyInteger('rating')->comment('Rating out of 5')->change();
            
            // 2. Status Field Add Karna (ShopController use kar raha hai)
            // ✅ NEW: Status field for moderation
            $table->string('status')->default('approved')->after('comment'); 
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Down: Status field ko drop karo
            $table->dropColumn('status');
            
            // Down: Rating ko wapas original type mein badlo (optional)
            // $table->integer('rating')->change(); 
        });
    }
};