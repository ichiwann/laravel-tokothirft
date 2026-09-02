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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id('pembelian_id');
            // Relasi ke user
            $table->foreignId('pembelian_user_id')
                  ->constrained('user', 'user_id')
                  ->cascadeOnDelete();
            // Relasi ke metode_pembayaran
            $table->foreignId('pembelian_metode_pembayaran_id')
                  ->constrained('metode_pembayaran', 'metode_pembayaran_id')
                  ->cascadeOnDelete();
            $table->timestamp('pembelian_tanggal');
            $table->integer('pembelian_total_harga');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
