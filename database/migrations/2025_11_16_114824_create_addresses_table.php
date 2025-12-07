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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User se link karna
            $table->string('full_name', 100); 
            $table->string('phone', 20);
            $table->string('street_address'); // 2392 Main Avenue
            $table->string('apartment_suite', 100)->nullable(); // Optional field
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('zip_code', 20);
            $table->string('country', 100)->default('India');
            $table->enum('type', ['shipping', 'billing'])->default('shipping'); // Address type: shipping ya billing
            $table->boolean('is_default')->default(false); // Default address ke liye
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
