<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');

        $stores = Store::query()
            ->when($search, function ($query, $search) {
                $query->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        $editStore = null;
        if ($request->filled('edit')) {
            $editStore = Store::findOrFail($request->edit);
        }

        // Jika AJAX request, return <tbody> langsung
        if ($request->ajax()) {
            $html = '';
            foreach ($stores as $i => $store) {
                $html .= '<tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition">';
                $html .= '<td class="p-3 text-center text-sm">' . ($stores->firstItem() + $i) . '</td>';
                $html .= '<td class="p-3 text-center text-sm">' . $store->code . '</td>';
                $html .= '<td class="p-3 text-center text-sm">' . $store->name . '</td>';
                $html .= '<td class="p-3 text-center text-sm">
                            <a href="' . route('master.stores.index', ['edit' => $store->id]) . '" class="text-blue-600 hover:underline">Edit</a>
                          </td>';
                $html .= '<td class="p-3 text-center text-sm">
                            <form method="POST" action="' . route('master.stores.destroy', $store) . '" onsubmit="return confirm(\'Hapus data?\')">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                          </td>';
                $html .= '</tr>';
            }

            if ($stores->count() === 0) {
                $html .= '<tr><td colspan="5" class="p-4 text-center text-gray-500 text-sm">Data belum ada</td></tr>';
            }

            return $html;
        }

        return view('master.stores.index', compact('stores', 'editStore'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:stores,code',
            'name' => 'required|string|max:100',
        ]);

        Store::create($request->only('code', 'name'));

        return redirect()->route('master.stores.index')->with('success', 'Toko berhasil ditambahkan');
    }

    public function update(Request $request, Store $store)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:stores,code,' . $store->id,
            'name' => 'required|string|max:100',
        ]);

        $store->update($request->only('code', 'name'));

        return redirect()->route('master.stores.index')->with('success', 'Toko berhasil diperbarui!');
    }

    public function destroy(Store $store)
    {
        $store->delete();

        return redirect()->route('master.stores.index')->with('success', 'Toko berhasil dihapus!');
    }
}
