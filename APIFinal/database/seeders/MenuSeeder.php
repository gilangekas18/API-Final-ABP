<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Tambahkan baris ini

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ID kategori (1, 2, 3) sesuai dengan ID kategori yang sudah ada di tabel 'categories'.
        // Biasanya, 'Coffee' akan memiliki ID 1, 'Non Coffee' ID 2, dan 'Dessert' ID 3
        // jika Anda memasukkannya dalam urutan tersebut.
        DB::table('menu')->insert([
            ['category_id' => 1, 'name' => 'Espresso', 'price' => 18000.00],
            ['category_id' => 1, 'name' => 'Americano', 'price' => 20000.00],
            ['category_id' => 1, 'name' => 'Iced Coffee', 'price' => 20000.00],
            ['category_id' => 1, 'name' => 'Cappuccino', 'price' => 25000.00],
            ['category_id' => 1, 'name' => 'Matcha Latte', 'price' => 27000.00],
            ['category_id' => 1, 'name' => 'Vanilla Latte', 'price' => 28000.00],
            ['category_id' => 1, 'name' => 'Choco Latte', 'price' => 28000.00],
            ['category_id' => 1, 'name' => 'Latte', 'price' => 25000.00],
            ['category_id' => 1, 'name' => 'Cold Brew', 'price' => 21000.00],
            ['category_id' => 2, 'name' => 'Hot Chocolate', 'price' => 20000.00],
            ['category_id' => 2, 'name' => 'Matcha Bliss', 'price' => 22000.00],
            ['category_id' => 2, 'name' => 'Early Grey', 'price' => 25000.00],
            ['category_id' => 2, 'name' => 'Lemon Pop', 'price' => 23000.00],
            ['category_id' => 2, 'name' => 'Berry Boom', 'price' => 28000.00],
            ['category_id' => 2, 'name' => 'TropiCool', 'price' => 22000.00],
            ['category_id' => 2, 'name' => 'Milky Way', 'price' => 22000.00],
            ['category_id' => 2, 'name' => 'Minty Fresh', 'price' => 15000.00],
            ['category_id' => 2, 'name' => 'Peach Tea Twist', 'price' => 22000.00],
            ['category_id' => 2, 'name' => 'Blue Ocean', 'price' => 28000.00],
            ['category_id' => 3, 'name' => 'Choco Lava', 'price' => 30000.00],
            ['category_id' => 3, 'name' => 'Berry Pancake', 'price' => 28000.00],
            ['category_id' => 3, 'name' => 'Velvet Slice', 'price' => 27000.00],
            ['category_id' => 3, 'name' => 'Banana Bread', 'price' => 25000.00],
            ['category_id' => 3, 'name' => 'Muffin', 'price' => 14000.00],
            ['category_id' => 3, 'name' => 'Donuts', 'price' => 32000.00],
        ]);
    }
}