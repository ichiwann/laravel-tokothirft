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
        Schema::create('metode_pembayaran', function (Blueprint $table) {
            $table->id('metode_pembayaran_id');
            // Relasi ke tabel user
            $table->foreignId('metode_pembayaran_user_id')
                  ->constrained('user', 'user_id')
                  ->cascadeOnDelete();
            $table->enum('metode_pembayaran_jenis', ['DANA', 'OVO', 'BCA', 'COD']);
            $table->string('metode_pembayaran_nomor', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metode_pembayaran');
    }
};
