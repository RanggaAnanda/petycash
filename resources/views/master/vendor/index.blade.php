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

        <!-- FILTER -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <div class="flex flex-wrap gap-4 items-end">
                <div>
                    <x-input-label name="Cari" />
                    <input id="filterVendor" type="text" class="px-3 py-2 rounded border w-64" placeholder="Cari nama atau contact..." />
                </div>

                <div class="ml-auto">
                    <a href="{{ route('master.vendor.edit', 0) }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Tambah Vendor</a>
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
                    <x-th>Contact</x-th>
                    <x-th class="text-center">Telepon</x-th>
                    <x-th class="text-center">Aksi</x-th>
                </tr>
            </thead>
            <x-tbody id="tableBodyVendor" />
        </x-table>

        <!-- PAGINATION -->
        <div class="flex justify-center">
            <div id="paginationVendor" class="flex items-center gap-1 bg-white dark:bg-gray-800 border rounded-lg p-1 shadow-sm"></div>
        </div>

    </div>

    <script src="{{ asset('js/helpers/pagination.js') }}"></script>
    <script>
        const perPageVendor = 8; let currentPageVendor = 1; let filteredVendor = [];
        const dataVendor = [];
        dataVendor.push({id:1, name:'Vendor A', contact:'Agus', phone:'0812345678'});
        dataVendor.push({id:2, name:'Vendor B', contact:'Sari', phone:'0812987654'});
        for(let i=3;i<=20;i++){ dataVendor.push({id:i, name:'Vendor '+i, contact:'CP '+i, phone:'0812'+(200000+i)}); }

        const baseVendorUrl = '{{ url('/master/vendor') }}';
        const csrfToken = '{{ csrf_token() }}';

        function applyVendor(){
            const q = document.getElementById('filterVendor').value.trim().toLowerCase();
            filteredVendor = dataVendor.filter(r => !q || r.name.toLowerCase().includes(q) || (r.contact && r.contact.toLowerCase().includes(q)));
            currentPageVendor = 1; renderVendor();
        }

        document.getElementById('filterVendor').addEventListener('input', applyVendor);

        function renderVendor(){
            const body = document.getElementById('tableBodyVendor'); body.innerHTML='';
            const start = (currentPageVendor-1)*perPageVendor; const pageData = filteredVendor.slice(start, start+perPageVendor);

            pageData.forEach((row, idx)=>{
                const rid = 'v-'+row.id;
                body.innerHTML += `
                <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <td class="p-3 text-center font-medium">${start+idx+1}</td>
                    <td class="p-3 text-center"><button onclick="toggleVendorDetail('${rid}')">+</button></td>
                    <td class="p-3">${row.name}</td>
                    <td class="p-3">${row.contact}</td>
                    <td class="p-3 text-center">${row.phone}</td>
                    <td class="p-3 text-center">
                        <a href="${baseVendorUrl}/${row.id}/edit" class="inline-flex items-center px-3 py-1.5 border rounded text-sm text-white bg-yellow-500 hover:bg-yellow-600">Edit</a>
                        <form action="${baseVendorUrl}/${row.id}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Yakin ingin menghapus vendor ini?');">
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
                        <div><strong>Contact Person:</strong> ${row.contact}</div>
                        <div><strong>Phone:</strong> ${row.phone}</div>
                    </td>
                </tr>
                `;
            });

            renderPagination({containerId:'paginationVendor', currentPage: currentPageVendor, perPage: perPageVendor, totalData: filteredVendor.length, onChange: changeVendorPage});
        }

        window.changeVendorPage = function(p){ const total = Math.ceil(filteredVendor.length / perPageVendor); if (p<1 || p>total) return; currentPageVendor = p; renderVendor(); }

        function toggleVendorDetail(id){ const row = document.getElementById(id); const btn = row.previousElementSibling.querySelector('button'); if (row.classList.contains('hidden')){ row.classList.remove('hidden'); btn.textContent='-'; } else { row.classList.add('hidden'); btn.textContent='+';} }

        filteredVendor = dataVendor; renderVendor();
    </script>
@endsection