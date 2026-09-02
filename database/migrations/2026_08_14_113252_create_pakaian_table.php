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
        Schema::create('pakaian', function (Blueprint $table) {
            $table->id('pakaian_id');
            // Relasi ke tabel kategori_pakaian
            $table->foreignId('pakaian_kategori_pakaian_id')
                  ->constrained('kategori_pakaian', 'kategori_pakaian_id')
                  ->cascadeOnDelete();
            $table->string('pakaian_nama', 50);
            $table->string('pakaian_harga', 50);
            $table->string('pakaian_stok', 100);
            $table->string('pakaian_gambar_url', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pakaian');
    }
};
