<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// KOREKSI: Gunakan model Anda, bukan library Spreadsheet!
use App\Models\Kategori;
use App\Models\Account;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        // Gunakan paginate(10) agar data memiliki link navigasi halaman
        $kategoris = Kategori::with('coa')->paginate(10);

        $coas = Account::all();

        $editKategori = null;
        if ($request->has('edit')) {
            $editKategori = Kategori::find($request->edit);
        }

        return view('master.kategori.index', compact('kategoris', 'coas', 'editKategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|in:masuk,keluar',
            'kode_input' => 'required|digits:2', // User isi 01, 02, dst
            'name' => 'required|string|max:255',
            'has_child' => 'required|in:ya,tidak',
        ]);

        // LOGIKA OTOMATIS:
        // Pemasukan arahkan ke Pendapatan (Kode 40)
        // Pengeluaran arahkan ke Beban (Kode 50)
        $prefix = ($request->status === 'masuk') ? '40' : '50';

        // Ambil ID dari tabel accounts berdasarkan kode prefix
        $coa = Account::where('kode_akun', $prefix)->first();

        if (!$coa) {
            return back()->with('error', "Master Akun dengan kode $prefix tidak ditemukan. Jalankan seeder COA terlebih dahulu!");
        }

        Kategori::create([
            'coa_id' => $coa->id,
            'kode_kategori' => $prefix . $request->kode_input, // Menggabungkan: 50 + 01 = 5001
            'name' => $request->name,
            'status' => $request->status,
            'has_child' => $request->has_child,
        ]);

        return redirect()->route('master.kategori.index')->with('success', 'Kategori berhasil disimpan.');
    }

    // KategoriController.php
    public function getNextCode($status)
    {
        $prefix = ($status === 'masuk') ? '40' : '50';

        // Cari kategori dengan kode yang diawali prefix tersebut, urutkan dari yang terbesar
        $lastCategory = Kategori::where('kode_kategori', 'like', $prefix . '%')
            ->orderBy('kode_kategori', 'desc')
            ->first();

        if (!$lastCategory) {
            return response()->json(['next_code' => '01']);
        }

        // Ambil 2 digit terakhir, tambah 1
        $lastSuffix = (int) substr($lastCategory->kode_kategori, -2);
        $nextSuffix = str_pad($lastSuffix + 1, 2, '0', STR_PAD_LEFT);

        return response()->json(['next_code' => $nextSuffix]);
    }


    public function edit(Kategori $kategori)
    {
        return $this->index(
            request()->merge(['edit' => $kategori->id])
        );
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:masuk,keluar', // Tambahkan ini
            'name' => 'required|string|max:255',
            'has_child' => 'required|in:ya,tidak',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'status' => $request->status, // Pastikan ini ada
            'name' => $request->name,
            'has_child' => $request->has_child,
        ]);

        return redirect()->route('master.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('master.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
