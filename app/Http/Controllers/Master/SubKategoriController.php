<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\SubKategori;
use Illuminate\Http\Request;

class SubKategoriController extends Controller
{
    public function index($kategori_id)
    {
        $kategori = Kategori::findOrFail($kategori_id);
        
        // Ambil semua sub kategori milik kategori ini
        $subkategoris = SubKategori::where('kategori_id', $kategori_id)
            ->orderBy('kode_sub', 'asc')
            ->get();

        // LOGIC AUTO GENERATE KODE (3 Digit: 100, 101, dst)
        $lastSub = SubKategori::where('kategori_id', $kategori_id)
            ->orderBy('kode_sub', 'desc')
            ->first();
            
        // Jika belum ada, mulai dari 100. Jika ada, ambil kode terakhir + 1.
        $nextCode = $lastSub ? (int)$lastSub->kode_sub + 1 : 100;

        return view('master.subkategori.index', compact('kategori', 'subkategoris', 'nextCode'));
    }

    public function store(Request $request, $kategori_id)
    {
        $request->validate([
            'kode' => 'required',
            'nama' => 'required|string|max:255',
        ]);

        SubKategori::create([
            'kategori_id' => $kategori_id,
            'kode_sub'    => $request->kode,
            'name'        => $request->nama,
        ]);

        return back()->with('success', 'Sub Kategori berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $sub = SubKategori::findOrFail($id);
        $sub->delete();

        return back()->with('success', 'Sub Kategori berhasil dihapus.');
    }
}