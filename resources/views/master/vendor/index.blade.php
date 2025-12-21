@extends('layouts.app')

@section('title', 'Master Vendor')
@section('page-title', 'Master - Vendor')

@php
    if (empty($vendors) || (is_object($vendors) && method_exists($vendors, 'isEmpty') && $vendors->isEmpty())) {
        $vendors = collect([
            (object)['id'=>1,'name'=>'Vendor A','contact_person'=>'Agus','phone'=>'0812345678'],
            (object)['id'=>2,'name'=>'Vendor B','contact_person'=>'Sari','phone'=>'0812987654'],
        ]);
    }
@endphp

@section('content')
    <div class="space-y-6">

        <!-- FORM VENDOR -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <h3 class="font-semibold mb-4">Form Vendor</h3>

            @php
                // sample categories for the dropdown
                if (empty($kategoris) || (is_object($kategoris) && method_exists($kategoris, 'isEmpty') && $kategoris->isEmpty())) {
                    $kategoris = collect([
                        (object)['id'=>1,'name'=>'Kategori A'],
                        (object)['id'=>2,'name'=>'Kategori B'],
                    ]);
                }
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <x-input-label name="Kode" />
                    <x-input id="kodeVendor" />
                </div>

                <div>
                    <x-input-label name="Kategori" />
                    <x-dropdown id="kategoriVendor" name="kategori_id" :options="array_merge(['' => 'Pilih Kategori..'], $kategoris->pluck('name','id')->toArray())" />
                </div>

                <div>
                    <x-input-label name="Vendor" />
                    <x-input id="namaVendor" />
                </div>
            </div>

            <div class="mt-4">
                <button id="btnSaveVendor" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Simpan</button>
                <button id="btnCancelVendor" type="button" class="ml-2 px-3 py-2 bg-gray-200 rounded hidden">Batal</button>
            </div>
        </div>

        <!-- LIST VENDOR -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold">List Vendor</h3>
                <div class="flex items-center gap-3">
                    <select id="perPageVendor" class="border rounded px-2 py-1">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span class="text-sm text-gray-500">entries per page</span>
                </div>
            </div>

            <div class="flex items-center justify-end mt-3">
                <label class="text-sm mr-2">Search:</label>
                <input id="filterVendor" type="text" class="px-3 py-2 rounded border" placeholder="Cari..." />
            </div>

            <x-table class="mt-4">
                <thead>
                    <tr>
                        <x-th class="w-12 text-center">No</x-th>
                        <x-th class="text-center">KODE</x-th>
                        <x-th class="text-left">KATEGORI</x-th>
                        <x-th class="text-left">VENDOR</x-th>
                        <x-th class="w-20 text-center">EDIT</x-th>
                        <x-th class="w-20 text-center">DELETE</x-th>
                    </tr>
                </thead>
                <x-tbody id="tableBodyVendor" />
            </x-table>

            <div class="flex items-center justify-between mt-3">
                <div id="infoVendor" class="text-sm text-gray-500">Showing 0 to 0 of 0 entries</div>
                <div id="paginationVendor" class="flex items-center gap-1 bg-white dark:bg-gray-800 border rounded-lg p-1 shadow-sm"></div>
            </div>
        </div>

    </div>

    <script src="{{ asset('js/helpers/pagination.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const baseVendorUrl = '{{ url('/master/vendor') }}';
        const csrfToken = '{{ csrf_token() }}';

        function escapeHtml(s){ if (s === null || s === undefined) return ''; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;'); }

        // demo data (kode, kategori, name)
        const dataVendor = [];
        dataVendor.push({id:1, kode:'111', kategori:'Kategori A', name:'Sarif'});
        dataVendor.push({id:2, kode:'112', kategori:'Kategori B', name:'Agus'});
        for(let i=3;i<=20;i++){ dataVendor.push({id:i, kode:String(110+i), kategori: i%2 ? 'Kategori A' : 'Kategori B', name:'Vendor '+i}); }

        const perPageSelect = document.getElementById('perPageVendor');
        const searchBox = document.getElementById('filterVendor');
        const tbody = document.getElementById('tableBodyVendor');
        const infoEl = document.getElementById('infoVendor');
        const btnSave = document.getElementById('btnSaveVendor');
        const btnCancel = document.getElementById('btnCancelVendor');

        let perPage = parseInt(perPageSelect.value) || 10;
        let currentPage = 1;
        let filtered = [];
        let editingId = null;

        function applyFilter(){
            const q = searchBox.value.trim().toLowerCase();
            filtered = dataVendor.filter(r => !q || r.kode.includes(q) || r.kategori.toLowerCase().includes(q) || r.name.toLowerCase().includes(q));
            currentPage = 1; renderTable();
        }

        perPageSelect.addEventListener('change', ()=>{ perPage = parseInt(perPageSelect.value)||10; currentPage = 1; renderTable(); });
        searchBox.addEventListener('input', applyFilter);

        function resetForm(){
            document.getElementById('kodeVendor').value = '';
            document.getElementById('kategoriVendor').value = '';
            document.getElementById('namaVendor').value = '';
            editingId = null;
            btnSave.textContent = 'Simpan';
            btnCancel.classList.add('hidden');
        }

        tbody.addEventListener('click', function(e){
            const el = e.target.closest('.vendor-edit');
            if (!el) return;
            e.preventDefault();
            const id = parseInt(el.dataset.id);
            const item = dataVendor.find(d => d.id === id);
            if (!item) return;
            document.getElementById('kodeVendor').value = item.kode;
            // find kategori option by text or fallback
            const sel = document.getElementById('kategoriVendor');
            if (sel) {
                for (let opt of sel.options) { if (opt.text === item.kategori) { sel.value = opt.value; break; } }
            }
            document.getElementById('namaVendor').value = item.name;
            editingId = id; btnSave.textContent = 'Update'; btnCancel.classList.remove('hidden'); document.getElementById('kodeVendor').focus();
        });

        document.getElementById('btnCancelVendor').addEventListener('click', resetForm);

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
                    <td class="p-3">${escapeHtml(row.kategori)}</td>
                    <td class="p-3">${escapeHtml(row.name)}</td>
                    <td class="p-3 text-center"><a href="#" data-id="${row.id}" class="vendor-edit text-blue-600">Edit</a></td>
                    <td class="p-3 text-center"><button data-id="${row.id}" class="vendor-delete text-red-600">Delete</button></td>
                </tr>`;
            });

            if (total === 0) infoEl.textContent = 'Showing 0 to 0 of 0 entries';
            else infoEl.textContent = `Showing ${start+1} to ${end} of ${total} entries`;

            renderPagination({ containerId: 'paginationVendor', currentPage, perPage, totalData: total, onChange: (p) => { changePage(p); } });
        }

        function changePage(p){ const totalPages = Math.ceil(filtered.length / perPage)||1; if (p<1 || p>totalPages) return; currentPage = p; renderTable(); }

        // Save / Update vendor (demo)
        btnSave.addEventListener('click', ()=>{
            const kode = document.getElementById('kodeVendor').value.trim();
            const kategoriSel = document.getElementById('kategoriVendor');
            const kategoriText = kategoriSel ? kategoriSel.options[kategoriSel.selectedIndex].text : '';
            const name = document.getElementById('namaVendor').value.trim();
            if (!kode || !name) { alert('Mohon isi Kode dan Vendor.'); return; }

            if (editingId !== null) {
                const idx = dataVendor.findIndex(d => d.id === editingId);
                if (idx !== -1) { dataVendor[idx].kode = kode; dataVendor[idx].kategori = kategoriText; dataVendor[idx].name = name; applyFilter(); resetForm(); return; }
            }

            const maxId = dataVendor.reduce((m,r)=>Math.max(m,r.id),0);
            dataVendor.unshift({ id: maxId+1, kode, kategori: kategoriText || 'Umum', name });
            applyFilter(); resetForm();
        });

        // Delete handler (event delegation)
        tbody.addEventListener('click', function(e){
            const del = e.target.closest('.vendor-delete');
            if (!del) return;
            const id = parseInt(del.dataset.id);
            if (!confirm('Yakin ingin menghapus vendor ini?')) return;
            const idx = dataVendor.findIndex(d => d.id === id);
            if (idx !== -1) { dataVendor.splice(idx,1); applyFilter(); }
        });

        // init
        filtered = dataVendor.slice(); renderTable();
    });
    </script>
@endsection