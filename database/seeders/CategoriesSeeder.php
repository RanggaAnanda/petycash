<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategoris')->insert([
            [
                'coa_id' => 1,
                'kode_kategori' => '1001',
                'name' => 'Kas Toko A',
                'status' => 'masuk',
                'has_child' => 'ya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coa_id' => 4,
                'kode_kategori' => '4001',
                'name' => 'Tramsfer dari keuangan',
                'status' => 'masuk',
                'has_child' => 'tidak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coa_id' => 5,
                'kode_kategori' => '5002',
                'name' => 'Operasional',
                'status' => 'keluar',
                'has_child' => 'ya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coa_id' => 5,
                'kode_kategori' => '5003',
                'name' => 'Kebersihan',
                'status' => 'keluar',
                'has_child' => 'ya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'coa_id' => 5,
                'kode_kategori' => '5004',
                'name' => 'Atk',
                'status' => 'keluar',
                'has_child' => 'ya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
