<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // FK ke tabel orders
            $table->foreignId('menu_id')->constrained('menu')->onDelete('cascade'); // FK ke tabel menu (pastikan nama tabelnya 'menu' singular)
            $table->string('menu_name_at_order'); // Nama menu saat order dibuat (untuk nota/riwayat akurat)
            $table->decimal('price_at_order', 10, 2); // Harga menu saat order dibuat
            $table->integer('quantity'); // Jumlah item
            $table->decimal('subtotal', 10, 2); // Subtotal untuk item ini (price_at_order * quantity)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};