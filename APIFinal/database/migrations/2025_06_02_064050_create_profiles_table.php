<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            // user_id sebagai Foreign Key ke tabel users
            // UNIQUE agar relasinya ONE-TO-ONE: satu user hanya bisa punya satu profil
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('phone_number')->nullable(); // Nomor telepon (opsional)
            $table->text('bio')->nullable();           // Bio/Deskripsi (opsional)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};