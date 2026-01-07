<?php

namespace App\Http\Controllers\Form;

use App\Models\Account;
use App\Models\Kategori;
use App\Models\Pemasukan;
use Illuminate\Http\Request;
use App\Helpers\JournalHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UangMasukController extends Controller
{
    /**
     * FORM CREATE
     */
    public function create()
    {
        $kategoris = Kategori::with('subKategoris')
            ->where('status', 'masuk')
            ->get();

        return view('forms.uang-masuk.create', compact('kategoris'));
    }

    /**
     * STORE PEMASUKAN + DOUBLE ENTRY
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal'         => 'required|date',
            'kategori_id'     => 'required|exists:kategoris,id',
            'sub_kategori_id' => 'nullable|exists:sub_kategoris,id',
            'nominal'         => 'required|string',
            'keterangan'      => 'nullable|string',
        ]);

        $nominal = (int) str_replace('.', '', $data['nominal']);

        try {
            DB::transaction(function () use ($data, $nominal) {

                // =====================
                // 1. SIMPAN PEMASUKAN
                // =====================
                $pemasukan = Pemasukan::create([
                    'tanggal'         => $data['tanggal'],
                    'kategori_id'     => $data['kategori_id'],
                    'sub_kategori_id' => $data['sub_kategori_id'] ?? null,
                    'nominal'         => $nominal,
                    'keterangan'      => $data['keterangan'] ?? null,
                    'user_id'         => 1,
                    'store_id'        => 1,
                ]);

                // =====================
                // 2. AMBIL AKUN
                // =====================
                $kategori = Kategori::with('coa', 'subKategoris.coa')
                    ->findOrFail($data['kategori_id']);

                $subKategoriId = $data['sub_kategori_id'] ?? null;

                $sub = $subKategoriId
                    ? $kategori->subKategoris->where('id', $subKategoriId)->first()
                    : null;


                $akunKas = Account::where('kode_akun', '1001')->firstOrFail();

                // PRIORITAS SUB KATEGORI
                $akunPendapatan = $sub && $sub->coa
                    ? $sub->coa
                    : $kategori->coa;

                // =====================
                // 3. JURNAL DOUBLE ENTRY
                // =====================
                JournalHelper::doubleEntry(
                    $data['tanggal'],
                    'PM-' . str_pad($pemasukan->id, 6, '0', STR_PAD_LEFT),
                    'pemasukan',
                    $pemasukan->id,
                    $akunKas,            // DEBIT
                    $akunPendapatan,     // KREDIT
                    $nominal,
                    'Pemasukan - ' . $kategori->name . ($sub ? ' / ' . $sub->name : ''),
                    1
                );
            });
        } catch (\Throwable $e) {
            Log::error('UangMasuk store failed', [
                'message' => $e->getMessage(),
            ]);

            return back()->with('error', $e->getMessage());
        }

        return redirect('daftar/petycash')
            ->with('success', 'Uang masuk berhasil disimpan');
    }

    /**
     * FORM EDIT
     */
    public function edit($id)
    {
        $item = Pemasukan::findOrFail($id);

        $kategoris = Kategori::where('status', 'masuk')->get();

        $subKategoris = $item->kategori->subKategorisMasuk;


        return view(
            'forms.uang-masuk.edit',
            compact('item', 'kategoris', 'subKategoris')
        );
    }


    /**
     * UPDATE PEMASUKAN + UPDATE JURNAL
     */
    public function update(Request $request, $id)
    {
        $item = Pemasukan::findOrFail($id);

        $data = $request->validate([
            'tanggal'         => 'required|date',
            'kategori_id'     => 'required|exists:kategoris,id',
            'sub_kategori_id' => 'nullable|exists:sub_kategoris,id',
            'nominal'         => 'required|string',
            'keterangan'      => 'nullable|string',
        ]);

        $nominal = (int) str_replace('.', '', $data['nominal']);

        DB::transaction(function () use ($item, $data, $nominal) {

            // =====================
            // UPDATE PEMASUKAN
            // =====================
            $item->update([
                'tanggal'         => $data['tanggal'],
                'kategori_id'     => $data['kategori_id'],
                'sub_kategori_id' => $data['sub_kategori_id'] ?? null,
                'nominal'         => $nominal,
                'keterangan'      => $data['keterangan'] ?? null,
            ]);

            // =====================
            // AMBIL AKUN
            // =====================
            $kategori = Kategori::with('coa', 'subKategoris.coa')
                ->findOrFail($data['kategori_id']);

            $subKategoriId = $data['sub_kategori_id'] ?? null;

            $sub = $subKategoriId
                ? $kategori->subKategoris->where('id', $subKategoriId)->first()
                : null;


            $akunKas = Account::where('kode_akun', '1001')->firstOrFail();

            $akunPendapatan = $sub && $sub->coa
                ? $sub->coa
                : $kategori->coa;

            // =====================
            // UPDATE JURNAL
            // =====================
            if ($item->jurnals()->exists()) {
                $item->jurnals()->delete();
            }


            JournalHelper::doubleEntry(
                $data['tanggal'],
                'PM-' . str_pad($item->id, 6, '0', STR_PAD_LEFT),
                'pemasukan',
                $item->id,
                $akunKas,
                $akunPendapatan,
                $nominal,
                'Pemasukan - ' . $kategori->name . ($sub ? ' / ' . $sub->name : ''),
                1
            );
        });

        return redirect('/daftar/petycash')
            ->with('success', 'Uang masuk berhasil diperbarui');
    }

    /**
     * AJAX SUB KATEGORI
     */
    public function getSubKategori($kategoriId)
    {
        return response()->json(
            \App\Models\SubKategori::where('kategori_id', $kategoriId)->get()
        );
    }
}
