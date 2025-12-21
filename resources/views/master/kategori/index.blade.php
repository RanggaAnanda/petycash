@extends('layouts.app')

@section('title', 'Master Kategori')
@section('page-title', 'Master - Kategori')

@php
    if (empty($kategoris) || (is_object($kategoris) && method_exists($kategoris, 'isEmpty') && $kategoris->isEmpty())) {
        $kategoris = collect([
            (object)['id'=>1,'name'=>'Kategori A','parent_id'=>null],
            (object)['id'=>2,'name'=>'Kategori B','parent_id'=>1],
        ]);
    }

    // prepare JSON-friendly data for client-side rendering (parent set to "Sub" or "Root")
    $kategoriJson = $kategoris->map(function($k){
        return [
            'id' => $k->id,
            'name' => $k->name,
            'parent' => $k->parent_id ? 'Sub' : 'Root'
        ];
    })->values();
@endphp

@section('content')
    <div class="space-y-6">

        <!-- FORM KATEGORI -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <h3 class="font-semibold mb-4">Form Kategori</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label name="Kode" />
                    <x-input id="kodeKategori" />
                </div>

                <div>
                    <x-input-label name="Status" />
                    <x-dropdown name="statusKategori" id="statusKategori" :options="['' => 'Pilih..', 'aktif'=>'aktif', 'keluar'=>'keluar']" />
                </div>

                <div>
                    <x-input-label name="Kategori" />
                    <x-input id="namaKategori" />
                </div>

                <div>
                    <x-input-label name="Child" />
                    <x-dropdown name="childKategori" id="childKategori" :options="['0'=>'Tidak','1'=>'Ya']" />
                </div>
            </div>

            <div class="mt-4">
                <button id="btnSaveKategori" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Simpan</button>
                <button id="btnCancelEditKategori" type="button" class="ml-2 px-3 py-2 bg-gray-200 rounded hidden">Batal</button>
            </div>
        </div>

        <!-- LIST KATEGORI -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold">List Kategori</h3>
                <div class="flex items-center gap-3">
                    <select id="perPageKategori" class="border rounded px-2 py-1">
                        <option value="8">8</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span class="text-sm text-gray-500">entries per page</span>
                </div>
            </div>

            <div class="flex items-center justify-end mt-3">
                <label class="text-sm mr-2">Search:</label>
                <input id="filterKategori" type="text" class="px-3 py-2 rounded border" placeholder="Cari..." />
            </div>

            <x-table class="mt-4">
                <thead>
                    <tr>
                        <x-th class="w-12 text-center">No</x-th>
                        <x-th class="text-center">KODE</x-th>
                        <x-th class="text-center">STATUS</x-th>
                        <x-th class="text-left">KATEGORI</x-th>
                        <x-th class="text-center">SUBKATEGORI</x-th>
                        <x-th class="w-20 text-center">EDIT</x-th>
                        <x-th class="w-20 text-center">DELETE</x-th>
                    </tr>
                </thead>
                <x-tbody id="tableBodyKategori" />
            </x-table>

            <div class="flex items-center justify-between mt-3">
                <div id="infoKategori" class="text-sm text-gray-500">Showing 0 to 0 of 0 entries</div>
                <div id="paginationKategori" class="flex items-center gap-1 bg-white dark:bg-gray-800 border rounded-lg p-1 shadow-sm"></div>
            </div>
        </div>

    </div>

    <script src="{{ asset('js/helpers/pagination.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const baseUrl = "{{ url('/master/kategori') }}";
        const csrfToken = "{{ csrf_token() }}";
        const kategoriServer = @json($kategoriJson);

        // helper to escape HTML when injecting into innerHTML
        function escapeHtml(str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        // Build client-side dataset from server data
        const kategoriData = kategoriServer.map((k, idx) => ({
            id: k.id,
            kode: (100 + k.id).toString(),
            status: idx % 2 === 0 ? 'keluar' : 'aktif',
            name: k.name,
            subkategori: k.parent === 'Sub' ? 'subkategori' : '-'
        }));

        // DOM refs
        const perPageSelect = document.getElementById('perPageKategori');
        const searchBox = document.getElementById('filterKategori');
        const tbody = document.getElementById('tableBodyKategori');
        const infoEl = document.getElementById('infoKategori');
        const btnSave = document.getElementById('btnSaveKategori');
        const btnCancel = document.getElementById('btnCancelEditKategori');

        let perPage = parseInt(perPageSelect.value) || 8;
        let currentPage = 1;
        let filtered = [];
        let editingId = null;

        function resetForm(){
            document.getElementById('kodeKategori').value = '';
            document.getElementById('statusKategori').value = '';
            document.getElementById('namaKategori').value = '';
            document.getElementById('childKategori').value = '0';
            editingId = null;
            btnSave.textContent = 'Simpan';
            btnCancel.classList.add('hidden');
        }

        function applyFilter() {
            const q = searchBox.value.trim().toLowerCase();
            filtered = kategoriData.filter(k => {
                if (!q) return true;
                return k.kode.includes(q)
                    || k.status.toLowerCase().includes(q)
                    || k.name.toLowerCase().includes(q)
                    || k.subkategori.toLowerCase().includes(q);
            });
            currentPage = 1;
            renderTable();
        }

        perPageSelect.addEventListener('change', () => {
            perPage = parseInt(perPageSelect.value) || 8;
            currentPage = 1;
            renderTable();
        });

        searchBox.addEventListener('input', applyFilter);

        // handle edit clicks (event delegation)
        tbody.addEventListener('click', function(e){
            const el = e.target.closest('.kategori-edit');
            if (!el) return;
            e.preventDefault();
            const id = parseInt(el.dataset.id);
            const item = kategoriData.find(k => k.id === id);
            if (!item) return;

            // populate form
            document.getElementById('kodeKategori').value = item.kode;
            document.getElementById('statusKategori').value = item.status;
            document.getElementById('namaKategori').value = item.name;
            document.getElementById('childKategori').value = item.subkategori === 'subkategori' ? '1' : '0';

            editingId = id;
            btnSave.textContent = 'Update';
            btnCancel.classList.remove('hidden');

            // focus and scroll to form (match Toko UX)
            const kodeInput = document.getElementById('kodeKategori');
            if (kodeInput) {
                kodeInput.focus();
                kodeInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });

        btnCancel.addEventListener('click', function(){ resetForm(); });

        function renderTable() {
            tbody.innerHTML = '';
            const total = filtered.length;
            const start = (currentPage - 1) * perPage;
            const end = Math.min(start + perPage, total);
            const pageData = filtered.slice(start, end);

            if (pageData.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td class="p-3 text-center" colspan="7">No data found</td>
                    </tr>`;
            } else {
                pageData.forEach((row, idx) => {
                    tbody.innerHTML += `
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <td class="p-3 text-center font-medium">${start + idx + 1}</td>
                        <td class="p-3 text-center">${escapeHtml(row.kode)}</td>
                        <td class="p-3 text-center">${escapeHtml(row.status)}</td>
                        <td class="p-3">${escapeHtml(row.name)}</td>
                        <td class="p-3 text-center text-teal-600">${row.subkategori !== '-' ? `<a href="${baseUrl}/${row.id}/subkategori" class="text-teal-600">${escapeHtml(row.subkategori)}</a>` : escapeHtml(row.subkategori)}</td>
                        <td class="p-3 text-center"><a href="#" data-id="${row.id}" class="kategori-edit text-blue-600">Edit</a></td>
                        <td class="p-3 text-center">
                            <form action="${baseUrl}/${row.id}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="text-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>`;
                });
            }

            if (total === 0) {
                infoEl.textContent = 'Showing 0 to 0 of 0 entries';
            } else {
                infoEl.textContent = `Showing ${start + 1} to ${end} of ${total} entries`;
            }

            renderPagination({
                containerId: 'paginationKategori',
                currentPage,
                perPage,
                totalData: total,
                onChange: (p) => { changePage(p); }
            });
        }

        function changePage(p) {
            const totalPages = Math.ceil(filtered.length / perPage) || 1;
            if (p < 1 || p > totalPages) return;
            currentPage = p;
            renderTable();
        }

        // Save or Update kategori (demo client-side)
        document.getElementById('btnSaveKategori').addEventListener('click', () => {
            const kode = document.getElementById('kodeKategori').value.trim();
            const status = document.getElementById('statusKategori').value || 'keluar';
            const name = document.getElementById('namaKategori').value.trim();
            const child = document.getElementById('childKategori').value;

            if (!kode || !name) {
                alert('Mohon isi Kode dan Kategori.');
                return;
            }

            if (editingId !== null) {
                const idx = kategoriData.findIndex(k => k.id === editingId);
                if (idx !== -1) {
                    kategoriData[idx].kode = kode;
                    kategoriData[idx].status = status;
                    kategoriData[idx].name = name;
                    kategoriData[idx].subkategori = child === '1' ? 'subkategori' : '-';
                    applyFilter();
                    resetForm();
                    return;
                }
            }

            const maxId = kategoriData.reduce((m, r) => Math.max(m, r.id), 0);
            const newItem = { id: maxId + 1, kode, status, name, subkategori: child === '1' ? 'subkategori' : '-' };
            kategoriData.unshift(newItem);
            applyFilter();

            // clear form
            document.getElementById('kodeKategori').value = '';
            document.getElementById('statusKategori').value = '';
            document.getElementById('namaKategori').value = '';
            document.getElementById('childKategori').value = '0';
        });

        // init
        filtered = kategoriData.slice();
        renderTable();
    });
    </script>
@endsection