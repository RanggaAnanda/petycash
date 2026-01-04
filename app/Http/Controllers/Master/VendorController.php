<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Kategori;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * List vendor + form tambah
     */
    public function index()
    {
        $vendors = Vendor::with('kategori')->orderBy('id', 'desc')->paginate(10);
        $kategoris = Kategori::all();

        return view('master.vendor.index', compact('vendors', 'kategoris'));
    }

    /**
     * Simpan vendor baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:vendors,kode',
            'name' => 'required|string',
            'kategori_id' => 'nullable|exists:kategoris,id',
        ]);

        Vendor::create($request->only('kode', 'name', 'kategori_id'));

        return redirect()->route('master.vendor.index')->with('success', 'Vendor berhasil ditambahkan');
    }

    /**
     * Tampilkan form edit vendor
     */
    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        $kategoris = Kategori::all();

        return view('master.vendor.edit', compact('vendor', 'kategoris'));
    }

    /**
     * Update vendor
     */
    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        $request->validate([
            'kode' => 'required|unique:vendors,kode,' . $vendor->id,
            'name' => 'required|string',
            'kategori_id' => 'nullable|exists:kategoris,id',
        ]);

        $vendor->update($request->only('kode', 'name', 'kategori_id'));

        return redirect()->route('master.vendor.index')->with('success', 'Vendor berhasil diupdate');
    }

    /**
     * Hapus vendor
     */
    public function destroy($id)
    {
        Vendor::findOrFail($id)->delete();

        return redirect()->route('master.vendor.index')->with('success', 'Vendor berhasil dihapus');
    }
}
