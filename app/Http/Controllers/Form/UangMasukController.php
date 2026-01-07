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
    public function create()
    {
        return view('forms.uang-masuk.create', [
            'kategoris' => Kategori::with('subKategoris')
                ->where('status', 'masuk')->get()
        ]);
    }

    public function edit($id)
    {
        return view('forms.uang-masuk.edit', [
            'item' => Pemasukan::findOrFail($id),
            'kategoris' => Kategori::with('subKategoris')
                ->where('status', 'masuk')->get()
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
        Log::info('Store Uang Masuk: validasi selesai', ['data' => $data, 'nominal' => $nominal]);

        try {
            DB::transaction(function () use ($data, $nominal) {

                // 1. Buat Pemasukan
                $pemasukan = Pemasukan::create([
                    'tanggal'         => $data['tanggal'],
                    'kategori_id'     => $data['kategori_id'],
                    'sub_kategori_id' => $data['sub_kategori_id'] ?? null,
                    'nominal'         => $nominal,
                    'keterangan'      => $data['keterangan'] ?? null,
                    'user_id'         => 1,
                    'store_id'        => 1,
                ]);
                Log::info('Pemasukan berhasil dibuat', ['pemasukan_id' => $pemasukan->id]);

                // 2. Ambil Kategori + Subkategori + Account Kas
                $kategori = Kategori::with('coa', 'subKategoris.coa')->findOrFail($data['kategori_id']);
                // Tambahkan default null untuk sub_kategori_id
                $subKategoriId = $data['sub_kategori_id'] ?? null;

                // Ambil sub kategori
                $sub = $subKategoriId
                    ? $kategori->subKategoris->where('id', $subKategoriId)->first()
                    : null;
                $akunKas = Account::where('kode_akun', '1001')->firstOrFail();

                Log::info('Kategori dan Subkategori', [
                    'kategori_id' => $kategori->id,
                    'sub_id' => $sub?->id ?? null,
                    'akunKas_id' => $akunKas->id
                ]);

                // 3. Tentukan akun pendapatan
                $akunPendapatan = $sub && $sub->coa ? $sub->coa : $kategori->coa;
                $noBukti = 'PM-' . str_pad($pemasukan->id, 6, '0', STR_PAD_LEFT);

                Log::info('Akun Pendapatan & No Bukti', [
                    'akunPendapatan' => $akunPendapatan->id ?? null,
                    'noBukti' => $noBukti
                ]);

                // 4. Buat jurnal double entry
                JournalHelper::doubleEntry(
                    $data['tanggal'],
                    $noBukti,
                    'pemasukan',
                    $pemasukan->id,
                    $akunKas,
                    $akunPendapatan,
                    $nominal,
                    'Pemasukan - ' . $kategori->name . ($sub ? ' / ' . $sub->name : ''),
                    1,
                    $data['kategori_id'],
                    $data['sub_kategori_id'] ?? null
                );

                Log::info('Jurnal berhasil dibuat untuk Pemasukan', ['pemasukan_id' => $pemasukan->id]);
            });
        } catch (\Throwable $e) {
            Log::error('Store Uang Masuk Gagal', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return redirect('daftar/petycash')
            ->with('success', 'Uang masuk berhasil disimpan');
    }

    public function update(Request $request, $id)
    {
        $pemasukan = Pemasukan::findOrFail($id);

        $data = $request->validate([
            'tanggal'         => 'required|date',
            'kategori_id'     => 'required',
            'sub_kategori_id' => 'nullable',
            'nominal'         => 'required',
            'keterangan'      => 'nullable',
        ]);

        $nominal = str_replace('.', '', $data['nominal']);

        DB::transaction(function () use ($data, $nominal, $pemasukan) {

            $pemasukan->update([
                'tanggal'         => $data['tanggal'],
                'kategori_id'     => $data['kategori_id'],
                'sub_kategori_id' => $data['sub_kategori_id'] ?? null,
                'nominal'         => $nominal,
                'keterangan'      => $data['keterangan'] ?? null,
            ]);

            $kategori = Kategori::with('coa', 'subKategoris.coa')->findOrFail($data['kategori_id']);
            // Tambahkan default null untuk sub_kategori_id
            $subKategoriId = $data['sub_kategori_id'] ?? null;

            // Ambil sub kategori
            $sub = $subKategoriId
                ? $kategori->subKategoris->where('id', $subKategoriId)->first()
                : null;
            $akunKas = Account::where('kode_akun', '1001')->firstOrFail();
            $akunPendapatan = $sub && $sub->coa ? $sub->coa : $kategori->coa;

            $pemasukan->jurnals()->delete();

            $noBukti = 'PM-' . str_pad($pemasukan->id, 6, '0', STR_PAD_LEFT);
            JournalHelper::doubleEntry(
                $data['tanggal'],
                $noBukti,
                'pemasukan',
                $pemasukan->id,
                $akunKas,
                $akunPendapatan,
                $nominal,
                'Pemasukan - ' . $kategori->name . ($sub ? ' / ' . $sub->name : ''),
                1,
                $data['kategori_id'],
                $data['sub_kategori_id'] ?? null
            );
        });

        return redirect('daftar/petycash')
            ->with('success', 'Uang masuk berhasil diperbarui');
    }

    public function getSubKategori($kategoriId)
    {
        $subKats = \App\Models\SubKategori::where('kategori_id', $kategoriId)->get();
        return response()->json($subKats);
    }
}
