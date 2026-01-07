<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sub_kategoris')->insert([
            [
                'kategori_id' => 2,
                'kode_sub' => '100',
                'name' => 'Listrik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 2,
                'kode_sub' => '101',
                'name' => 'Air ledeng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 3,
                'kode_sub' => '100',
                'name' => 'Peralatan kebersihan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 3,
                'kode_sub' => '101',
                'name' => 'Pewangi ruangan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 4,
                'kode_sub' => '100',
                'name' => 'Alat Tulis Kantor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 4,
                'kode_sub' => '101',
                'name' => 'Kertas Printer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
