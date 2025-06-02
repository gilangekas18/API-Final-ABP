<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Bisa dari user login, atau guest
            $table->string('sender_name');     // Nama pengirim (akan selalu diinput)
            $table->string('sender_email');    // Email pengirim (akan selalu diinput)
            $table->string('sender_phone')->nullable(); // Telepon pengirim (opsional)
            $table->string('subject');         // Subjek pesan
            $table->text('message_content');   // Isi pesan
            // Kolom 'status' DIBUANG sepenuhnya
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};