<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stores')->insert([
            [
                'code' => '1001',
                'name' => 'Pusat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '2001',
                'name' => 'Planet Fashion Bandung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '2002',
                'name' => 'Planet Fashion Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '2003',
                'name' => 'Planet Fashion Surabaya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3001',
                'name' => 'Konveksi Planet Fashion Bandung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3002',
                'name' => 'Konveksi Planet Fashion Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '3003',
                'name' => 'Konveksi Planet Fashion Surabaya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
