<?php

namespace App\Http\Controllers\Laporan;

use App\Models\Omset;
use App\Models\Store;
use App\Models\Jurnal;
use App\Models\Account;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

class AkuntansiController extends Controller
{
    // Buku Besar
    public function bukuBesar(Request $request)
    {
        $query = Account::with(['jurnals' => function ($q) use ($request) {

            // Filter tanggal
            if ($request->waktu && $request->waktu !== 'all') {
                switch ($request->waktu) {
                    case 'hari_ini':
                        $q->whereDate('tanggal', now());
                        break;
                    case 'kemarin':
                        $q->whereDate('tanggal', now()->subDay());
                        break;
                    case 'minggu_lalu':
                        $q->whereDate('tanggal', '>=', now()->subWeek())
                            ->whereDate('tanggal', '<=', now());
                        break;
                    case 'bulan_ini':
                        $q->whereMonth('tanggal', now()->month)
                            ->whereYear('tanggal', now()->year);
                        break;
                    case 'bulan_lalu':
                        $q->whereMonth('tanggal', now()->subMonth()->month)
                            ->whereYear('tanggal', now()->subMonth()->year);
                        break;
                    case 'custom':
                        if ($request->start_date) $q->whereDate('tanggal', '>=', $request->start_date);
                        if ($request->end_date) $q->whereDate('tanggal', '<=', $request->end_date);
                        break;
                }
            }

            // Filter tipe transaksi
            if ($request->tipe && $request->tipe !== 'all') {
                if ($request->tipe == 'masuk') $q->where('debit', '>', 0);
                if ($request->tipe == 'keluar') $q->where('kredit', '>', 0);
            }

            // Filter toko
            if ($request->toko && $request->toko !== 'all') {
                $q->where('store_id', $request->toko);
            }

            // Filter kategori
            if ($request->kategori && $request->kategori !== 'all') {
                $q->whereHas('kategori', fn($q2) => $q2->where('id', $request->kategori));
            }

            $q->orderBy('tanggal')->orderBy('id');
        }])->get();

        $bukuBesar = [];
        foreach ($query as $account) {
            $saldo = 0;
            $rows = [];
            foreach ($account->jurnals as $jurnal) {
                $saldo = $account->normal_balance === 'Debit'
                    ? $saldo + $jurnal->debit - $jurnal->kredit
                    : $saldo + $jurnal->kredit - $jurnal->debit;

                $rows[] = [
                    'tanggal' => $jurnal->tanggal,
                    'no_bukti' => $jurnal->no_bukti,
                    'keterangan' => $jurnal->keterangan,
                    'debit' => $jurnal->debit,
                    'kredit' => $jurnal->kredit,
                    'saldo' => $saldo,
                ];
            }

            $bukuBesar[] = [
                'account' => $account,
                'rows' => $rows,
            ];
        }

        $tokos = Store::all();
        $categories = Kategori::all();

        return view('laporan.pettycash.akuntansi.buku-besar', compact('bukuBesar', 'tokos', 'categories'));
    }

    // Neraca Saldo dengan filter
    public function neracaSaldo(Request $request)
    {
        $tokos = Store::all();
        $categories = Kategori::all();

        // Ambil filter dari request
        $waktu = $request->get('waktu', 'all');
        $toko = $request->get('toko', 'all');
        $kategori = $request->get('kategori', 'all');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        // Query Jurnal
        $query = Jurnal::query();

        // Filter Toko
        if ($toko !== 'all') {
            $query->where('store_id', $toko);
        }

        // Filter Kategori
        if ($kategori !== 'all') {
            $query->where('kategori_id', $kategori);
        }

        // Filter Waktu
        switch ($waktu) {
            case 'hari_ini':
                $query->whereDate('tanggal', now());
                break;
            case 'kemarin':
                $query->whereDate('tanggal', now()->subDay());
                break;
            case 'minggu_lalu':
                $query->whereBetween('tanggal', [now()->subWeek(), now()]);
                break;
            case 'bulan_ini':
                $query->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year);
                break;
            case 'bulan_lalu':
                $query->whereMonth('tanggal', now()->subMonth()->month)
                    ->whereYear('tanggal', now()->subMonth()->year);
                break;
            case 'custom':
                if ($start_date && $end_date) {
                    $query->whereBetween('tanggal', [$start_date, $end_date]);
                }
                break;
            default:
                // all time, no filter
                break;
        }

        $jurnals = $query->get();

        // Gabungkan per akun
        $neraca = [];
        $totalDebit = 0;
        $totalKredit = 0;

        foreach (Account::all() as $akun) {
            $debit = $jurnals->where('account_id', $akun->id)->sum('debit');
            $kredit = $jurnals->where('account_id', $akun->id)->sum('kredit');
            if ($debit > 0 || $kredit > 0) {
                $neraca[] = [
                    'account' => $akun,
                    'debit' => $debit,
                    'kredit' => $kredit
                ];
                $totalDebit += $debit;
                $totalKredit += $kredit;
            }
        }

        return view('laporan.pettycash.akuntansi.neraca', [
            'neraca' => $neraca,
            'totalDebit' => $totalDebit,
            'totalKredit' => $totalKredit,
            'isBalanced' => $totalDebit == $totalKredit,
            'tokos' => $tokos,
            'categories' => $categories
        ]);
    }

    // Laba Rugi dengan filter
    public function labaRugi(Request $request)
    {
        $tokos = Store::all();
        $categories = Kategori::all();

        $waktu = $request->get('waktu', 'all');
        $toko = $request->get('toko', 'all');
        $kategori = $request->get('kategori', 'all');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $query = Jurnal::query();

        // Filter toko
        if ($toko !== 'all') {
            $query->where('store_id', $toko);
        }

        // Filter kategori
        if ($kategori !== 'all') {
            $query->where('kategori_id', $kategori);
        }

        // Filter waktu
        switch ($waktu) {
            case 'hari_ini':
                $query->whereDate('tanggal', now());
                break;
            case 'kemarin':
                $query->whereDate('tanggal', now()->subDay());
                break;
            case 'minggu_lalu':
                $query->whereBetween('tanggal', [now()->subWeek(), now()]);
                break;
            case 'bulan_ini':
                $query->whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year);
                break;
            case 'bulan_lalu':
                $query->whereMonth('tanggal', now()->subMonth()->month)
                    ->whereYear('tanggal', now()->subMonth()->year);
                break;
            case 'custom':
                if ($start_date && $end_date) {
                    $query->whereBetween('tanggal', [$start_date, $end_date]);
                }
                break;
            default:
                break;
        }

        $jurnals = $query->get();

        // Pendapatan dan Beban
        $pendapatan_totals = [];
        $beban_totals = [];
        $totalPendapatan = 0;
        $totalBeban = 0;

        $pendapatan_accounts = Account::where('jenis_akun', 'Pendapatan')->get();
        $beban_accounts = Account::where('jenis_akun', 'Beban')->get();

        foreach ($pendapatan_accounts as $akun) {
            $total = $jurnals->where('account_id', $akun->id)->sum('kredit')
                - $jurnals->where('account_id', $akun->id)->sum('debit');
            if ($total != 0) {
                $pendapatan_totals[] = ['akun' => $akun, 'total' => $total];
                $totalPendapatan += $total;
            }
        }

        foreach ($beban_accounts as $akun) {
            $total = $jurnals->where('account_id', $akun->id)->sum('debit')
                - $jurnals->where('account_id', $akun->id)->sum('kredit');
            if ($total != 0) {
                $beban_totals[] = ['akun' => $akun, 'total' => $total];
                $totalBeban += $total;
            }
        }

        $laba_bersih = $totalPendapatan - $totalBeban;

        return view('laporan.pettycash.akuntansi.laba-rugi', compact(
            'pendapatan_totals',
            'beban_totals',
            'totalPendapatan',
            'totalBeban',
            'laba_bersih',
            'tokos',
            'categories'
        ));
    }


    public function omset(Request $request)
    {
        $tokos = Store::orderBy('name')->get();

        $query = Omset::with('store');

        if ($request->filled('toko') && $request->toko !== 'all') {
            $query->where('store_id', $request->toko);
        }

        if ($request->filled('waktu')) {
            match ($request->waktu) {
                'hari_ini'   => $query->whereDate('tanggal', now()),
                'minggu_ini' => $query->whereBetween('tanggal', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ]),
                'bulan_ini'  => $query->whereMonth('tanggal', now()->month),
                default      => null,
            };
        }

        $items = $query
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('laporan.omset', compact('items', 'tokos'));
    }

    public function petycash()
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

        return view('laporan.petycash', [
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
        return redirect()->route('laporan.petycash');
    }
}
