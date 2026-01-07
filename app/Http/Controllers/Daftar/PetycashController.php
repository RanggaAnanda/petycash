<?php

namespace App\Http\Controllers\Daftar;

use App\Models\Kategori;
use App\Models\Pengeluaran;
use App\Models\Pemasukkan; // Pastikan ejaan sesuai model Anda
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Support\Facades\DB;

class PetycashController extends Controller
{
    public function index(Request $request)
    {
        $saldoAwal = 5000000;

        // Ambil session
        $sessionFilter = session('petycash_filter', []);
        // Gabungkan session dengan default agar key yang kurang otomatis terisi
        $filter = array_merge([
            'waktu' => 'all',
            'toko' => 'all',
            'kategori' => 'all',
            'tipe' => 'all', // Pastikan ini ada
            'start_date' => null,
            'end_date' => null,
        ], $sessionFilter);

        // Sekarang kamu aman menggunakan $filter['tipe'] tanpa takut undefined
        // ... sisa kode query kamu ...


        // 1. Query Pemasukan
        $masuk = DB::table('pemasukans')
            ->select('id', 'tanggal', 'kategori_id', DB::raw('NULL as sub_kategori_id'), 'nominal', 'keterangan', 'user_id', DB::raw("'masuk' as tipe"));

        // 2. Query Pengeluaran
        $keluar = DB::table('pengeluarans')
            ->select('id', 'tanggal', 'kategori_id', 'sub_kategori_id', 'nominal', 'keterangan', 'user_id', DB::raw("'keluar' as tipe"));

        // Gunakan ?? 'all' untuk mengantisipasi jika key 'tipe' belum ada di session lama
        if ($filter['tipe'] === 'masuk') {
            $keluar->whereRaw('1 = 0');
        } elseif ($filter['tipe'] === 'keluar') {
            $masuk->whereRaw('1 = 0');
        }

        // --- APPLY FILTER ---
        $applyFilter = function ($query) use ($filter) {

            if ($filter['toko'] !== 'all') {
                $query->whereExists(function ($q) use ($filter) {
                    $q->select(DB::raw(1))
                        ->from('users')
                        ->whereColumn('users.id', 'user_id')
                        ->where('store_id', $filter['toko']);
                });
            }
            if ($filter['kategori'] !== 'all') {
                $query->where('kategori_id', $filter['kategori']);
            }

            // Filter Waktu
            switch ($filter['waktu']) {
                case 'hari_ini':
                    $query->whereDate('tanggal', today());
                    break;
                case 'kemarin':
                    $query->whereDate('tanggal', today()->subDay());
                    break;
                case 'minggu_lalu':
                    $query->whereBetween('tanggal', [now()->subDays(7), now()]);
                    break;
                case 'bulan_ini':
                    $query->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year);
                    break;
                case 'custom':
                    if ($filter['start_date'] && $filter['end_date']) {
                        $query->whereBetween('tanggal', [$filter['start_date'], $filter['end_date']]);
                    }
                    break;
            }
        };

        $applyFilter($masuk);
        $applyFilter($keluar);

        // 3. Union & Sort
        // Kita gunakan Get dulu untuk hitung saldo manual, baru kita manual paginate jika data sangat besar
        // Namun untuk Petty Cash, gabungan union ini kita ambil urut Tanggal Terlama (ASC) agar saldo running benar
        $gabungan = $masuk->union($keluar)->orderBy('tanggal', 'asc')->get();

        // 4. Hitung Running Saldo secara manual dalam koleksi
        $currentSaldo = $saldoAwal;
        $gabungan = $gabungan->map(function ($item) use (&$currentSaldo) {
            if ($item->tipe == 'masuk') {
                $currentSaldo += $item->nominal;
            } else {
                $currentSaldo -= $item->nominal;
            }
            $item->saldo_berjalan = $currentSaldo;

            // 1. Ambil Nama Kategori (Langsung ambil string namanya)
            $item->kategori_name = \App\Models\Kategori::where('id', $item->kategori_id)->value('name') ?? 'Tanpa Kategori';

            // 2. Ambil Nama Sub Kategori
            if ($item->sub_kategori_id) {
                $item->sub_name = \App\Models\SubKategori::where('id', $item->sub_kategori_id)->value('name') ?? '-';
            } else {
                $item->sub_name = '-';
            }

            // 3. Ambil Nama Toko dari User
            $user = \App\Models\User::with('store')->find($item->user_id);
            $item->store_name = ($user && $user->store) ? $user->store->name : 'Pusat';

            return $item;
        });

        // Balik urutan agar yang terbaru di atas untuk tampilan tabel
        $transaksiTampil = $gabungan->reverse();

        return view('daftar.petycash.index', [
            'transaksi' => $transaksiTampil,
            'saldoAwal' => $saldoAwal,
            'categories' => Kategori::all(),
            'tokos' => Store::all(),
            'filter' => $filter,
        ]);
    }

    public function filter(Request $request)
    {
        session(['petycash_filter' => $request->only(['waktu', 'toko', 'kategori', 'tipe', 'start_date', 'end_date'])]);
        return redirect()->route('daftar.petycash.index');
    }
}
