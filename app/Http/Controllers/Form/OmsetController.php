<?php

namespace App\Http\Controllers\Form;

use App\Models\Omset;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OmsetController extends Controller
{
    public function create()
    {
        return view('forms.omset.create', [
            'tokos' => Store::all()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal'  => 'required|date',
            'store_id' => 'required|exists:stores,id',
            'nominal'  => 'required',
        ]);

        DB::transaction(function () use ($data) {
            Omset::create([
                'tanggal'  => $data['tanggal'],
                'store_id' => $data['store_id'],
                'nominal'  => preg_replace('/[^0-9]/', '', $data['nominal']),
                'user_id'  =>1,
            ]);
        });

        return redirect()
            ->route('daftar.omset.index')
            ->with('success', 'Omset berhasil ditambahkan');
    }

    public function edit($id)
    {
        return view('forms.omset.edit', [
            'item'  => Omset::findOrFail($id),
            'tokos' => Store::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'tanggal'  => 'required|date',
            'store_id' => 'required|exists:stores,id',
            'nominal'  => 'required',
        ]);

        DB::transaction(function () use ($data, $id) {
            $item = Omset::findOrFail($id);

            $item->update([
                'tanggal'  => $data['tanggal'],
                'store_id' => $data['store_id'],
                'nominal'  => preg_replace('/[^0-9]/', '', $data['nominal']),
            ]);
        });

        return redirect()
            ->route('daftar.omset.index')
            ->with('success', 'Omset berhasil diperbarui');
    }
}
