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
        Schema::table('admins', function (Blueprint $table) {
            // 'username' column ko drop kar raha hoon jaisa ki aapki table structure mein tha
            $table->dropColumn('username');
            
            // 'email' column add kar raha hoon, jo unique hoga aur login ke liye zaroori hai
            $table->string('email')->unique()->after('id');
            
            // Password reset ke liye 'remember_token' add karna bhi standard practice hai
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            // Agar rollback kiya toh 'email' column drop ho jaayega
            $table->dropColumn('email');
            $table->dropRememberToken();
            
            // Aur 'username' column wapas aa jaayega (agar aapki original migration mein tha)
            // Note: Agar original table mein 'username' nahi tha, toh isko hata dein
            $table->string('username')->unique()->after('id');
        });
    }
};
