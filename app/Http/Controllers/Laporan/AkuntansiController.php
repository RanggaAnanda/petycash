<?php

namespace App\Http\Controllers\Laporan;

use App\Models\Jurnal;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Store;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class AkuntansiController extends Controller
{
    public function jurnal()
    {
        $data = Jurnal::with('account')
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        return view('laporan.pettycash.akuntansi.jurnal', compact('data'));
    }
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

            // Filter kategori (jika ada relasi kategori)
            if ($request->kategori && $request->kategori !== 'all') {
                $q->whereHas('kategori', fn($q2) => $q2->where('id', $request->kategori));
            }

            $q->orderBy('tanggal')->orderBy('id');
        }])->get();

        // Struktur buku besar sama seperti sebelumnya
        $bukuBesar = [];
        foreach ($query as $account) {
            $saldo = 0;
            $rows = [];
            foreach ($account->jurnals as $jurnal) {
                $saldo = $account->normal_balance == 'Debit'
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

        $tokos = Store::all();      // untuk dropdown
        $categories = Kategori::all(); // jika ada kategori

        return view('laporan.pettycash.akuntansi.buku-besar', compact('bukuBesar', 'tokos', 'categories'));
    }

    // Neraca Saldo
    public function neracaSaldo()
    {
        $accounts = Account::with('jurnals')->get();

        $neraca = [];
        $totalDebit = 0;
        $totalKredit = 0;

        foreach ($accounts as $account) {
            $debitTotal = $account->jurnals->sum('debit');
            $kreditTotal = $account->jurnals->sum('kredit');

            $neraca[] = [
                'account' => $account,
                'debit' => $debitTotal,
                'kredit' => $kreditTotal,
            ];

            $totalDebit += $debitTotal;
            $totalKredit += $kreditTotal;
        }

        $isBalanced = ($totalDebit == $totalKredit);

        return view('laporan.pettycash.akuntansi.neraca', compact(
            'neraca',
            'totalDebit',
            'totalKredit',
            'isBalanced'
        ));
    }

    public function labaRugi()
    {
        $pendapatan = Account::where('jenis_akun', 'Pendapatan')->with('jurnals')->get();
        $totalPendapatan = 0;
        $pendapatan_totals = [];
        foreach ($pendapatan as $akun) {
            $total = $akun->jurnals->sum('kredit') - $akun->jurnals->sum('debit');
            $pendapatan_totals[] = [
                'akun' => $akun,
                'total' => $total,
            ];
            $totalPendapatan += $total;
        }

        $beban = Account::where('jenis_akun', 'Beban')->with('jurnals')->get();
        $totalBeban = 0;
        $beban_totals = [];
        foreach ($beban as $akun) {
            $total = $akun->jurnals->sum('debit') - $akun->jurnals->sum('kredit');
            $beban_totals[] = [
                'akun' => $akun,
                'total' => $total,
            ];
            $totalBeban += $total;
        }

        $laba_bersih = $totalPendapatan - $totalBeban;

        return view('laporan.pettycash.akuntansi.laba-rugi', compact(
            'pendapatan_totals',
            'totalPendapatan',
            'beban_totals',
            'totalBeban',
            'laba_bersih'
        ));
    }

    public function arusKas()
    {
        $kasMasuk = Jurnal::whereHas(
            'account',
            fn($q) =>
            $q->where('kode_akun', 'like', '100%')
        )->sum('debit');

        $kasKeluar = Jurnal::whereHas(
            'account',
            fn($q) =>
            $q->where('kode_akun', 'like', '100%')
        )->sum('kredit');

        return view('laporan.pettycash.akuntansi.arus-kas', compact('kasMasuk', 'kasKeluar'));
    }
}
