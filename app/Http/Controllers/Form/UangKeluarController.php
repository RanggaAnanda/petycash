<?php

namespace App\Http\Controllers\Form;

use App\Models\Pengeluaran;
use App\Models\Kategori;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Helpers\JournalHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class UangKeluarController extends Controller
{
    public function create()
    {
        return view('forms.uang-keluar.create', [
            'kategoris' => Kategori::with('subKategoris')
                ->where('status', 'keluar')->get()
        ]);
    }

    public function edit($id)
    {
        return view('forms.uang-keluar.edit', [
            'item' => Pengeluaran::findOrFail($id),
            'kategoris' => Kategori::with('subKategoris')
                ->where('status', 'keluar')->get()
        ]);
    }



    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal'         => 'required|date',
            'kategori_id'     => 'required',
            'sub_kategori_id' => 'nullable',
            'nominal'         => 'required',
            'keterangan'      => 'nullable',
        ]);

        $nominal = str_replace('.', '', $data['nominal']);
        Log::info('Store Uang Keluar: validasi selesai', ['data' => $data, 'nominal' => $nominal]);

        try {
            DB::transaction(function () use ($data, $nominal) {

                // 1. Buat Pengeluaran
                $pengeluaran = Pengeluaran::create([
                    'tanggal'          => $data['tanggal'],
                    'kategori_id'      => $data['kategori_id'],
                    'sub_kategori_id'  => $data['sub_kategori_id'] ?? null,
                    'nominal'          => $nominal,
                    'keterangan'       => $data['keterangan'] ?? null,
                    'user_id'          => 1,
                ]);
                Log::info('Pengeluaran berhasil dibuat', ['pengeluaran_id' => $pengeluaran->id]);

                // 2. Ambil Kategori + Subkategori + Account Kas
                $kategori = Kategori::with('coa', 'subKategoris.coa')->findOrFail($data['kategori_id']);
                $sub = $data['sub_kategori_id'] ? $kategori->subKategoris->where('id', $data['sub_kategori_id'])->first() : null;
                $akunKas = Account::where('kode_akun', '1001')->firstOrFail();

                Log::info('Kategori dan Subkategori', [
                    'kategori_id' => $kategori->id,
                    'sub_id' => $sub?->id ?? null,
                    'akunKas_id' => $akunKas->id
                ]);

                // 3. Tentukan akun beban
                $akunBeban = $sub && $sub->coa ? $sub->coa : $kategori->coa;
                $noBukti = 'PK-' . str_pad($pengeluaran->id, 6, '0', STR_PAD_LEFT);

                Log::info('Akun Beban & No Bukti', [
                    'akunBeban' => $akunBeban->id ?? null,
                    'noBukti' => $noBukti
                ]);

                Log::info('Debug Jurnal Params', [
                    'tanggal' => $data['tanggal'],
                    'noBukti' => $noBukti,
                    'refType' => 'pengeluaran',
                    'refId' => $pengeluaran->id,
                    'akunBeban_id' => $akunBeban?->id ?? null,
                    'akunKas_id' => $akunKas?->id ?? null,
                    'nominal' => $nominal,
                    'kategori_id' => $data['kategori_id'],
                    'sub_kategori_id' => $data['sub_kategori_id'] ?? null
                ]);


                // 4. Buat jurnal
                JournalHelper::doubleEntry(
                    $data['tanggal'],
                    $noBukti,
                    'pengeluaran',
                    $pengeluaran->id,
                    $akunBeban,
                    $akunKas,
                    $nominal,
                    'Pengeluaran - ' . $kategori->name . ($sub ? ' / ' . $sub->name : ''),
                    1,
                    $data['kategori_id'],
                    $data['sub_kategori_id']
                );

                Log::info('Jurnal berhasil dibuat untuk Pengeluaran', ['pengeluaran_id' => $pengeluaran->id]);
            });
        } catch (\Throwable $e) {
            Log::error('Store Uang Keluar Gagal', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return redirect('daftar/petycash')
            ->with('success', 'Uang keluar berhasil disimpan');
    }


    public function update(Request $request, $id)
    {
        $pengeluaran = Pengeluaran::findOrFail($id);

        $data = $request->validate([
            'tanggal'         => 'required|date',
            'kategori_id'     => 'required',
            'sub_kategori_id' => 'nullable',
            'nominal'         => 'required',
            'keterangan'      => 'nullable',
        ]);

        $nominal = str_replace('.', '', $data['nominal']);

        DB::transaction(function () use ($data, $nominal, $pengeluaran) {

            $pengeluaran->update([
                'tanggal'         => $data['tanggal'],
                'kategori_id'     => $data['kategori_id'],
                'sub_kategori_id' => $data['sub_kategori_id'] ?? null,
                'nominal'         => $nominal,
                'keterangan'      => $data['keterangan'] ?? null,
            ]);

            $kategori = Kategori::with('coa', 'subKategoris.coa')->findOrFail($data['kategori_id']);
            $sub = $data['sub_kategori_id'] ? $kategori->subKategoris->where('id', $data['sub_kategori_id'])->first() : null;
            $akunKas = Account::where('kode_akun', '1001')->firstOrFail();
            $akunBeban = $sub && $sub->coa ? $sub->coa : $kategori->coa;

            $pengeluaran->jurnals()->delete();

            $noBukti = 'PK-' . str_pad($pengeluaran->id, 6, '0', STR_PAD_LEFT);
            JournalHelper::doubleEntry(
                $data['tanggal'],
                $noBukti,
                'pengeluaran',
                $pengeluaran->id,
                $akunBeban,
                $akunKas,
                $nominal,
                'Pengeluaran - ' . $kategori->name . ($sub ? ' / ' . $sub->name : ''),
                1,
                $data['kategori_id'],
                $data['sub_kategori_id']
            );
        });

        return redirect('daftar/petycash')
            ->with('success', 'Uang keluar berhasil diperbarui');
    }

    public function getSubKategori($kategoriId)
    {
        $subKats = \App\Models\SubKategori::where('kategori_id', $kategoriId)->get();
        return response()->json($subKats);
    }
}
