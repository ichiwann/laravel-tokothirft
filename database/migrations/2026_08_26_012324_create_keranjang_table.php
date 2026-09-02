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
        Schema::create('keranjang', function (Blueprint $table) {
            $table->id('keranjang_id');

            $table->unsignedBigInteger('keranjang_user_id');
            $table->unsignedBigInteger('keranjang_pakaian_id');
            $table->integer('keranjang_jumlah')->default(1);
            $table->timestamps();

            $table->foreign('keranjang_user_id')->references('user_id')->on('user')->onDelete('cascade');
            $table->foreign('keranjang_pakaian_id')->references('pakaian_id')->on('pakaian')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keranjang');
    }
};
