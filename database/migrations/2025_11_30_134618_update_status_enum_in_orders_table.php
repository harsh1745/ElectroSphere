<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'processing',
                'shipped',
                'completed',
                'cancelled',
                'refunded'
            ])->default('pending')->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'completed',
                'cancelled'
            ])->default('pending')->change();
        });
    }
};
