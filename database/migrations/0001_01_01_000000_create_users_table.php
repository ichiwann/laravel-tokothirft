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
        Schema::create('user', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('user_username', 50);
            $table->string('user_password', 255); // Dibuat 255 agar muat hash password Laravel
            $table->string('user_fullname', 100);
            $table->string('user_email', 50);
            $table->char('user_nohp', 13);
            $table->string('user_alamat', 200);
            $table->string('user_profil_url', 255)->default('url_placeholder_profil');
            $table->enum('user_level', ['Admin', 'Pengguna']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
