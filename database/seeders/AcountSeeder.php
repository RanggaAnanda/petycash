<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('accounts')->insert([
            [
                'kode_akun' => '1001',
                'jenis_akun' => 'Aset',
                'normal_balance' => 'Debit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_akun' => '20',
                'jenis_akun' => 'Kewajiban',
                'normal_balance' => 'Kredit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_akun' => '30',
                'jenis_akun' => 'Modal',
                'normal_balance' => 'Kredit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_akun' => '40',
                'jenis_akun' => 'Pendapatan',
                'normal_balance' => 'Kredit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_akun' => '50',
                'jenis_akun' => 'Beban',
                'normal_balance' => 'Debit',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
