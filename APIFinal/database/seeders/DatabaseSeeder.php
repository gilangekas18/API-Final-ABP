<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class, // Pastikan baris ini ada
            MenuSeeder::class,     // Pastikan baris ini ada
            // UserSeeder::class,   // Ini opsional jika Anda punya UserSeeder
        ]);
    }
}