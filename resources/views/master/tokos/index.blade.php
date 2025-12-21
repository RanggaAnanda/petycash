@extends('layouts.app')

@section('title', 'Master Toko')
@section('page-title', 'Master - Toko / Divisi')

@section('content')

    <div class="space-y-6">

        <!-- FORM TOKO -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <h3 class="font-semibold mb-4">Form Toko</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label name="Kode" />
                    <x-input id="kodeToko" />
                </div>

                <div>
                    <x-input-label name="Toko" />
                    <x-input id="namaToko" />
                </div>
            </div>

            <div class="mt-4">
                <button id="btnSaveToko" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Simpan</button>
                <button id="btnCancelEdit" type="button" class="ml-2 px-3 py-2 bg-gray-200 rounded hidden">Batal</button>
            </div>
        </div>

        <!-- LIST TOKO -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold">List Toko</h3>
                <div class="flex items-center gap-3">
                    <select id="perPageToko" class="border rounded px-2 py-1">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span class="text-sm text-gray-500">entries per page</span>
                </div>
            </div>

            <div class="flex items-center justify-end mt-3">
                <label class="text-sm mr-2">Search:</label>
                <input id="filterTokoSearch" type="text" class="px-3 py-2 rounded border" placeholder="Cari..." />
            </div>

            <x-table class="mt-4">
                <thead>
                    <tr>
                        <x-th class="w-12 text-center">No</x-th>
                        <x-th class="text-center">KODE</x-th>
                        <x-th class="text-left">TOKO</x-th>
                        <x-th class="w-24 text-center">EDIT</x-th>
                        <x-th class="w-24 text-center">DELETE</x-th>
                    </tr>
                </thead>
                <x-tbody id="tableBodyToko" />
            </x-table>

            <div class="flex items-center justify-between mt-3">
                <div id="infoToko" class="text-sm text-gray-500">Showing 0 to 0 of 0 entries</div>
                <div id="paginationToko" class="flex items-center gap-1 bg-white dark:bg-gray-800 border rounded-lg p-1 shadow-sm"></div>
            </div>
        </div>

    </div>

    <script src="{{ asset('js/helpers/pagination.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const baseUrl = "{{ url('/master/tokos') }}";
        const csrfToken = "{{ csrf_token() }}";

        function escapeHtml(s){ if (s === null || s === undefined) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;'); }

        // demo data
        const tokoData = [];
        for (let i=2000;i<=2014;i++){
            tokoData.push({ id: i-1999, kode: String(i), name: `PLANET FASHION STORE ${i}` });
        }

        const perPageSelect = document.getElementById('perPageToko');
        const searchBox = document.getElementById('filterTokoSearch');
        const tbody = document.getElementById('tableBodyToko');
        const infoEl = document.getElementById('infoToko');
        const btnSave = document.getElementById('btnSaveToko');
        const btnCancel = document.getElementById('btnCancelEdit');

        let perPage = parseInt(perPageSelect.value) || 10;
        let currentPage = 1;
        let filtered = [];
        let editingId = null; // null means creating new, number means editing existing

        function resetForm(){
            document.getElementById('kodeToko').value='';
            document.getElementById('namaToko').value='';
            editingId = null;
            btnSave.textContent = 'Simpan';
            btnCancel.classList.add('hidden');
        }

        function applyFilter(){
            const q = searchBox.value.trim().toLowerCase();
            filtered = tokoData.filter(t => !q || t.kode.includes(q) || t.name.toLowerCase().includes(q));
            currentPage = 1; renderTable();
        }

        perPageSelect.addEventListener('change', ()=>{ perPage = parseInt(perPageSelect.value)||10; currentPage = 1; renderTable(); });
        searchBox.addEventListener('input', applyFilter);

        tbody.addEventListener('click', function(e){
            const el = e.target.closest('.toko-edit');
            if (!el) return;
            e.preventDefault();
            const id = parseInt(el.dataset.id);
            const toko = tokoData.find(t => t.id === id);
            if (!toko) return;
            // populate form
            document.getElementById('kodeToko').value = toko.kode;
            document.getElementById('namaToko').value = toko.name;
            editingId = id;
            btnSave.textContent = 'Update';
            btnCancel.classList.remove('hidden');
            document.getElementById('kodeToko').focus();
        });

        btnCancel.addEventListener('click', function(){ resetForm(); });

        function renderTable(){
            tbody.innerHTML = '';
            const total = filtered.length;
            const start = (currentPage-1)*perPage;
            const end = Math.min(start + perPage, total);
            const pageData = filtered.slice(start, end);

            pageData.forEach((row, idx)=>{
                tbody.innerHTML += `
                <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <td class="p-3 text-center font-medium">${start+idx+1}</td>
                    <td class="p-3 text-center">${escapeHtml(row.kode)}</td>
                    <td class="p-3">${escapeHtml(row.name)}</td>
                    <td class="p-3 text-center"><a href="#" data-id="${row.id}" class="toko-edit text-blue-600">Edit</a></td>
                    <td class="p-3 text-center">
                        <form action="${baseUrl}/${row.id}" method="POST" onsubmit="return confirm('Yakin ingin menghapus toko ini?');">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-red-600">Delete</button>
                        </form>
                    </td>
                </tr>`;
            });

            if (total === 0) infoEl.textContent = 'Showing 0 to 0 of 0 entries';
            else infoEl.textContent = `Showing ${start+1} to ${end} of ${total} entries`;

            renderPagination({ containerId: 'paginationToko', currentPage, perPage, totalData: total, onChange: (p) => { changePage(p); } });
        }

        function changePage(p){ const totalPages = Math.ceil(filtered.length / perPage)||1; if (p<1 || p>totalPages) return; currentPage = p; renderTable(); }

        // Save or Update toko (demo)
        btnSave.addEventListener('click', ()=>{
            const kode = document.getElementById('kodeToko').value.trim();
            const name = document.getElementById('namaToko').value.trim();
            if (!kode || !name) { alert('Mohon isi Kode dan Toko.'); return; }

            if (editingId !== null) {
                const idx = tokoData.findIndex(t => t.id === editingId);
                if (idx !== -1) {
                    tokoData[idx].kode = kode;
                    tokoData[idx].name = name;
                    applyFilter();
                    resetForm();
                    return;
                }
            }

            const maxId = tokoData.reduce((m,r)=>Math.max(m,r.id),0);
            tokoData.unshift({ id: maxId+1, kode, name });
            applyFilter();
            document.getElementById('kodeToko').value=''; document.getElementById('namaToko').value='';
        });

        // init
        filtered = tokoData.slice(); renderTable();
    });
    </script>
@endsection
