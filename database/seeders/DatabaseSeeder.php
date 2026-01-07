<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Panggil seeder spesifik Anda di sini
        $this->call([
            StoreSeeder::class,
            UserSeeder::class,
            AcountSeeder::class,
            CategoriesSeeder::class,
            SubcategoriesSeeder::class,
            VendorSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}