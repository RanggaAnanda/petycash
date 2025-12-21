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
@endphp

@section('content')
    <div class="space-y-6">

        <!-- FILTER -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <div class="flex flex-wrap gap-4 items-end">
                <div>
                    <x-input-label name="Cari" />
                    <input id="filterKategori" type="text" class="px-3 py-2 rounded border w-64" placeholder="Cari nama kategori..." />
                </div>

                <div class="ml-auto">
                    <a href="{{ route('master.kategori.edit', 0) }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Tambah Kategori</a>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <x-table>
            <thead>
                <tr>
                    <x-th class="w-12 text-center">No</x-th>
                    <x-th class="w-10 text-center">#</x-th>
                    <x-th>Nama</x-th>
                    <x-th class="text-center">Parent</x-th>
                    <x-th class="text-center">Aksi</x-th>
                </tr>
            </thead>
            <x-tbody id="tableBodyKategori" />
        </x-table>

        <!-- PAGINATION -->
        <div class="flex justify-center">
            <div id="paginationKategori" class="flex items-center gap-1 bg-white dark:bg-gray-800 border rounded-lg p-1 shadow-sm"></div>
        </div>

    </div>

    <script src="{{ asset('js/helpers/pagination.js') }}"></script>
    <script>
        const perPageKategori = 8; let currentPageKategori = 1; let filteredKategori = [];
        const dataKategori = [];
        dataKategori.push({id:1, name:'Kategori A', parent: null});
        dataKategori.push({id:2, name:'Kategori B', parent: 1});
        for(let i=3;i<=22;i++){ dataKategori.push({id:i, name:'Kategori '+i, parent: i%2? null:1}); }

        const baseKategoriUrl = '{{ url('/master/kategori') }}';
        const csrfToken = '{{ csrf_token() }}';

        function applyKategori(){
            const q = document.getElementById('filterKategori').value.trim().toLowerCase();
            filteredKategori = dataKategori.filter(r => !q || r.name.toLowerCase().includes(q));
            currentPageKategori = 1; renderKategori();
        }

        document.getElementById('filterKategori').addEventListener('input', applyKategori);

        function renderKategori(){
            const body = document.getElementById('tableBodyKategori'); body.innerHTML='';
            const start = (currentPageKategori-1)*perPageKategori; const pageData = filteredKategori.slice(start, start+perPageKategori);

            pageData.forEach((row, idx)=>{
                const rid = 'k-'+row.id;
                body.innerHTML += `
                <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <td class="p-3 text-center font-medium">${start+idx+1}</td>
                    <td class="p-3 text-center"><button onclick="toggleKategoriDetail('${rid}')">+</button></td>
                    <td class="p-3">${row.name}</td>
                    <td class="p-3 text-center">${row.parent ? 'Sub' : 'Root'}</td>
                    <td class="p-3 text-center">
                        <a href="${baseKategoriUrl}/${row.id}/edit" class="inline-flex items-center px-3 py-1.5 border rounded text-sm text-white bg-yellow-500 hover:bg-yellow-600">Edit</a>
                        <form action="${baseKategoriUrl}/${row.id}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 border rounded text-sm text-white bg-red-600 hover:bg-red-700">Hapus</button>
                        </form>
                    </td>
                </tr>
                `;

                body.innerHTML += `
                <tr id="${rid}" class="detail-row hidden bg-gray-50 dark:bg-gray-800">
                    <td></td>
                    <td colspan="4" class="p-3">
                        <div><strong>Parent ID:</strong> ${row.parent ?? '-'} </div>
                    </td>
                </tr>
                `;
            });

            renderPagination({containerId:'paginationKategori', currentPage: currentPageKategori, perPage: perPageKategori, totalData: filteredKategori.length, onChange: changeKategoriPage});
        }

        window.changeKategoriPage = function(p){ const total = Math.ceil(filteredKategori.length / perPageKategori); if (p<1 || p>total) return; currentPageKategori = p; renderKategori(); }

        function toggleKategoriDetail(id){ const row = document.getElementById(id); const btn = row.previousElementSibling.querySelector('button'); if (row.classList.contains('hidden')){ row.classList.remove('hidden'); btn.textContent='-'; } else { row.classList.add('hidden'); btn.textContent='+';} }

        filteredKategori = dataKategori; renderKategori();
    </script>
@endsection