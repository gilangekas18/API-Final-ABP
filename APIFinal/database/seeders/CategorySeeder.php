<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Tambahkan baris ini untuk menggunakan DB Facade

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Masukkan data ke tabel 'categories'
        DB::table('categories')->insert([
            ['name' => 'Coffee'],
            ['name' => 'Non Coffee'],
            ['name' => 'Dessert'],
        ]);
    }
}