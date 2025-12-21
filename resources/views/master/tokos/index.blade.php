@extends('layouts.app')

@section('title', 'Master Toko')
@section('page-title', 'Master - Toko / Divisi')

@section('content')

    <div class="space-y-6">

        <!-- FILTER -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <div class="flex flex-wrap gap-4 items-end">
                <div>
                    <x-input-label name="Cari" />
                    <input id="filterTokoSearch" type="text" class="px-3 py-2 rounded border w-64" placeholder="Cari nama atau alamat..." />
                </div>

                <div class="ml-auto">
                    <a href="{{ route('master.toko.edit', 0) }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Tambah Toko</a>
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
                    <x-th>Alamat</x-th>
                    <x-th class="text-center">Telepon</x-th>
                    <x-th class="text-center">Aksi</x-th>
                </tr>
            </thead>
            <x-tbody id="tableBodyToko" />
        </x-table>

        <!-- PAGINATION -->
        <div class="flex justify-center">
            <div id="paginationToko" class="flex items-center gap-1 bg-white dark:bg-gray-800 border rounded-lg p-1 shadow-sm"></div>
        </div>

    </div>

    <script src="{{ asset('js/helpers/pagination.js') }}"></script>
    <script>
        const perPageToko = 8; let currentPageToko = 1; let filteredToko = [];
        const dataToko = [];
        for (let i=1;i<=15;i++){ dataToko.push({id:i, name:'Toko '+String.fromCharCode(64+i), alamat:'Jl. Contoh '+i, phone:'0812'+(100000+i)}); }

        const baseUrlToko = '{{ url('/master/toko') }}';
        const csrfToken = '{{ csrf_token() }}';

        function applyTokoFilter(){
            const q = document.getElementById('filterTokoSearch').value.trim().toLowerCase();
            filteredToko = dataToko.filter(r => !q || r.name.toLowerCase().includes(q) || (r.alamat && r.alamat.toLowerCase().includes(q)));
            currentPageToko = 1; renderTokoTable();
        }

        document.getElementById('filterTokoSearch').addEventListener('input', applyTokoFilter);

        function renderTokoTable(){
            const body = document.getElementById('tableBodyToko'); body.innerHTML='';
            const start = (currentPageToko-1)*perPageToko; const pageData = filteredToko.slice(start, start+perPageToko);

            pageData.forEach((row, idx)=>{
                const rid = 't-'+row.id;
                body.innerHTML += `
                <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <td class="p-3 text-center font-medium">${start+idx+1}</td>
                    <td class="p-3 text-center"><button onclick="toggleTokoDetail('${rid}')">+</button></td>
                    <td class="p-3">${row.name}</td>
                    <td class="p-3">${row.alamat}</td>
                    <td class="p-3 text-center">${row.phone}</td>
                    <td class="p-3 text-center">
                        <a href="${baseUrlToko}/${row.id}/edit" class="inline-flex items-center px-3 py-1.5 border rounded text-sm text-white bg-yellow-500 hover:bg-yellow-600">Edit</a>
                        <form action="${baseUrlToko}/${row.id}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Yakin ingin menghapus toko ini?');">
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
                    <td colspan="5" class="p-3">
                        <div><strong>Alamat:</strong> ${row.alamat}</div>
                        <div><strong>Telepon:</strong> ${row.phone}</div>
                    </td>
                </tr>
                `;
            });

            renderPagination({containerId:'paginationToko', currentPage: currentPageToko, perPage: perPageToko, totalData: filteredToko.length, onChange: changeTokoPage});
        }

        window.changeTokoPage = function(p){ const totalPages = Math.ceil(filteredToko.length / perPageToko); if (p<1 || p>totalPages) return; currentPageToko = p; renderTokoTable(); }

        function toggleTokoDetail(id){ const row = document.getElementById(id); const btn = row.previousElementSibling.querySelector('button'); if (row.classList.contains('hidden')){ row.classList.remove('hidden'); btn.textContent='-'; } else { row.classList.add('hidden'); btn.textContent='+'; } }

        filteredToko = dataToko; renderTokoTable();
    </script>
@endsection
