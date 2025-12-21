@extends('layouts.app')

@section('title', 'Master Sub Kategori')
@section('page-title', 'Master - Sub Kategori')

@php
    // fallback kategori if not provided by controller
    if (empty($kategori)) {
        $kategori = (object)[ 'id' => 1, 'name' => 'ATK' ];
    }

    // fallback subkategoris
    if (empty($subkategoris) || (is_object($subkategoris) && method_exists($subkategoris, 'isEmpty') && $subkategoris->isEmpty())) {
        $subkategoris = collect([
            (object)['id'=>1,'kode'=>'100','name'=>'SOLATIF','kategori_id'=>$kategori->id],
            (object)['id'=>2,'kode'=>'101','name'=>'PULPEN','kategori_id'=>$kategori->id],
            (object)['id'=>3,'kode'=>'102','name'=>'LABEL / DH KECIL','kategori_id'=>$kategori->id],
        ]);
    }

    $subJson = $subkategoris->map(function($s){
        return ['id'=>$s->id, 'kode'=>$s->kode ?? null, 'name'=>$s->name];
    })->values();
@endphp

@section('content')
<div class="space-y-6">

    <!-- FORM SUB KATEGORI -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
        <h3 class="font-semibold mb-4">Form Sub Kategori</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label name="Kategori" />
                <x-input id="kategoriName" value="{{ $kategori->name }}" readonly />
            </div>

            <div>
                <x-input-label name="Kode" />
                <x-input id="kodeSub" name="kode" />
            </div>

            <div class="md:col-span-2">
                <x-input-label name="Sub Kategori" />
                <x-input id="namaSub" name="nama" />
            </div>
        </div>

        <div class="mt-4">
            <button id="btnSaveSub" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Simpan</button>
        </div>
    </div>

    <!-- LIST SUB KATEGORI -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold">List Toko</h3>
            <div class="flex items-center gap-3">
                <select id="perPageSub" class="border rounded px-2 py-1">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <span class="text-sm text-gray-500">entries per page</span>
            </div>
        </div>

        <div class="flex items-center justify-end mt-3">
            <label class="text-sm mr-2">Search:</label>
            <input id="filterSub" type="text" class="px-3 py-2 rounded border" placeholder="Cari..." />
        </div>

        <x-table class="mt-4">
            <thead>
                <tr>
                    <x-th class="w-12 text-center">No</x-th>
                    <x-th class="text-center">KODE</x-th>
                    <x-th class="text-left">SUBKATEGORI</x-th>
                    <x-th class="w-20 text-center">DELETE</x-th>
                </tr>
            </thead>
            <x-tbody id="tableBodySub" />
        </x-table>

        <div class="flex items-center justify-between mt-3">
            <div id="infoSub" class="text-sm text-gray-500">Showing 0 to 0 of 0 entries</div>
            <div id="paginationSub" class="flex items-center gap-1 bg-white dark:bg-gray-800 border rounded-lg p-1 shadow-sm"></div>
        </div>
    </div>

</div>

<script src="{{ asset('js/helpers/pagination.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function(){
        const baseUrl = "{{ url('/subkategori') }}";
        const csrfToken = "{{ csrf_token() }}";
        const kategoriName = "{{ $kategori->name }}";

        function escapeHtml(s){ if (s === null || s === undefined) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;'); }

        const subServer = @json($subJson);
        const subData = subServer.map(s => ({ id: s.id, kode: s.kode ?? (100 + s.id).toString(), name: s.name }));

        const perPageSelect = document.getElementById('perPageSub');
        const searchBox = document.getElementById('filterSub');
        const tbody = document.getElementById('tableBodySub');
        const infoEl = document.getElementById('infoSub');

        let perPage = parseInt(perPageSelect.value) || 10;
        let currentPage = 1;
        let filtered = [];

        function applyFilter(){
            const q = searchBox.value.trim().toLowerCase();
            filtered = subData.filter(s => !q || s.kode.includes(q) || s.name.toLowerCase().includes(q));
            currentPage = 1; renderTable();
        }

        perPageSelect.addEventListener('change', ()=>{ perPage = parseInt(perPageSelect.value)||10; currentPage = 1; renderTable(); });
        searchBox.addEventListener('input', applyFilter);

        function renderTable(){
            tbody.innerHTML = '';
            const total = filtered.length;
            const start = (currentPage-1) * perPage;
            const end = Math.min(start + perPage, total);
            const pageData = filtered.slice(start, end);

            if (pageData.length === 0){
                tbody.innerHTML = `<tr><td class="p-3 text-center" colspan="4">No data found</td></tr>`;
            } else {
                pageData.forEach((r, idx) => {
                    tbody.innerHTML += `
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <td class="p-3 text-center font-medium">${start+idx+1}</td>
                        <td class="p-3 text-center">${escapeHtml(r.kode)}</td>
                        <td class="p-3">${escapeHtml(r.name)}</td>
                        <td class="p-3 text-center">
                            <form action="${baseUrl}/${r.id}" method="POST" onsubmit="return confirm('Yakin ingin menghapus sub kategori ini?');">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>`;
                });
            }

            infoEl.textContent = total === 0 ? 'Showing 0 to 0 of 0 entries' : `Showing ${start+1} to ${end} of ${total} entries`;

            renderPagination({ containerId: 'paginationSub', currentPage, perPage, totalData: total, onChange: (p) => { changePage(p); } });
        }

        function changePage(p){ const totalPages = Math.ceil(filtered.length / perPage) || 1; if (p<1 || p>totalPages) return; currentPage = p; renderTable(); }

        // Save new sub kategori (demo)
        document.getElementById('btnSaveSub').addEventListener('click', () => {
            const kode = document.getElementById('kodeSub').value.trim();
            const name = document.getElementById('namaSub').value.trim();
            if (!kode || !name) { alert('Isi Kode dan Sub Kategori.'); return; }

            const maxId = subData.reduce((m, r) => Math.max(m, r.id), 0);
            subData.unshift({ id: maxId + 1, kode, name });
            applyFilter();

            document.getElementById('kodeSub').value = '';
            document.getElementById('namaSub').value = '';
        });

        // init
        filtered = subData.slice(); renderTable();
    });
</script>
@endsection