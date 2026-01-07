<?php

namespace App\Http\Controllers\Daftar;

use App\Models\Omset;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OmsetController extends Controller
{
    public function index(Request $request)
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

        return view('daftar.omset.index', compact('items', 'tokos'));
    }
}
