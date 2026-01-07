<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Account;
use App\Models\Kategori;
use App\Helpers\JournalHelper;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Data toko dan jumlah transaksi
        $tokoTransaksi = [
            2 => 80,
            3 => 60,
            4 => 90,
            5 => 50,
            6 => 70,
            7 => 40,
        ];

        // Ambil akun kas (ID tetap atau kode_akun 1001)
        $akunKas = Account::where('kode_akun', '1001')->firstOrFail();

        // Ambil semua kategori pemasukan dan pengeluaran
        $kategoriMasuk = Kategori::where('status', 'masuk')->with('coa', 'subKategoris.coa')->get();
        $kategoriKeluar = Kategori::where('status', 'keluar')->with('coa', 'subKategoris.coa')->get();

        foreach ($tokoTransaksi as $storeId => $jumlahTransaksi) {

            for ($i = 0; $i < $jumlahTransaksi; $i++) {
                // Tentukan jenis transaksi random: masuk atau keluar
                $tipe = $faker->randomElement(['masuk', 'keluar']);

                // Tentukan tanggal acak bulan Desember 2025
                $tanggal = $faker->dateTimeBetween('2025-12-01', '2025-12-31')->format('Y-m-d');

                if ($tipe === 'masuk') {
                    $kategori = $kategoriMasuk->random();
                    $sub = $kategori->subKategoris->isNotEmpty() ? $kategori->subKategoris->random() : null;

                    $nominal = $faker->numberBetween(10000, 500000);

                    DB::transaction(function () use ($kategori, $sub, $akunKas, $storeId, $tanggal, $nominal) {
                        $pemasukan = Pemasukan::create([
                            'tanggal' => $tanggal,
                            'kategori_id' => $kategori->id,
                            'sub_kategori_id' => $sub?->id,
                            'nominal' => $nominal,
                            'keterangan' => 'Seeder Pemasukan',
                            'user_id' => 1,
                            'store_id' => $storeId,
                        ]);

                        $akunPendapatan = $sub && $sub->coa ? $sub->coa : $kategori->coa;

                        JournalHelper::doubleEntry(
                            $tanggal,
                            'PM-' . str_pad($pemasukan->id, 6, '0', STR_PAD_LEFT),
                            'pemasukan',
                            $pemasukan->id,
                            $akunKas,
                            $akunPendapatan,
                            $nominal,
                            'Pemasukan - ' . $kategori->name . ($sub ? ' / ' . $sub->name : ''),
                            $storeId,
                            $kategori->id,
                            $sub?->id
                        );
                    });

                } else {
                    $kategori = $kategoriKeluar->random();
                    $sub = $kategori->subKategoris->isNotEmpty() ? $kategori->subKategoris->random() : null;

                    $nominal = $faker->numberBetween(10000, 300000);

                    DB::transaction(function () use ($kategori, $sub, $akunKas, $storeId, $tanggal, $nominal) {
                        $pengeluaran = \App\Models\Pengeluaran::create([
                            'tanggal' => $tanggal,
                            'kategori_id' => $kategori->id,
                            'sub_kategori_id' => $sub?->id,
                            'nominal' => $nominal,
                            'keterangan' => 'Seeder Pengeluaran',
                            'user_id' => 1,
                            'store_id' => $storeId,
                        ]);

                        $akunBeban = $sub && $sub->coa ? $sub->coa : $kategori->coa;

                        JournalHelper::doubleEntry(
                            $tanggal,
                            'PK-' . str_pad($pengeluaran->id, 6, '0', STR_PAD_LEFT),
                            'pengeluaran',
                            $pengeluaran->id,
                            $akunBeban,
                            $akunKas,
                            $nominal,
                            'Pengeluaran - ' . $kategori->name . ($sub ? ' / ' . $sub->name : ''),
                            $storeId,
                            $kategori->id,
                            $sub?->id
                        );
                    });
                }
            }
        }

        $this->command->info('Seeder transaksi selesai!');
    }
}
