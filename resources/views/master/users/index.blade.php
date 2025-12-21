@extends('layouts.app')

@section('title', 'Master User')
@section('page-title', 'Master - User')



@php
    // Build toko map from $tokos if available, otherwise use a default map
    if (isset($tokos) && is_object($tokos) && method_exists($tokos, 'pluck')) {
        $tokoMap = $tokos->pluck('name', 'id')->toArray();
    } else {
        $tokoMap = [
            1 => 'Toko A',
            2 => 'Toko B',
            3 => 'Toko C',
        ];
    }
@endphp

@section('content')
<div class="space-y-6">

    <!-- FILTER -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
        <div class="flex flex-wrap gap-4 items-end">

            <div>
                <x-input-label name="Cari" />
                <input id="filterSearch" type="text" class="px-3 py-2 rounded border w-64" placeholder="Cari nama, email, role..." />
            </div>

            <div>
                <x-input-label name="Toko" />
                <x-dropdown name="filterToko" id="filterToko" :options="[
                    'all' => 'Semua Toko',
                    'Toko A' => 'Toko A',
                    'Toko B' => 'Toko B',
                    'Toko C' => 'Toko C',
                ]" />
            </div>

            <div class="ml-auto">
                <a href="{{ route('master.users.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">Tambah User</a>
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <x-table>
        <thead>
            <tr>
                <x-th class="w-12 text-center">No</x-th>
                <x-th class="w-10 text-center">#</x-th>
                <x-th class="text-left">Nama</x-th>
                <x-th class="text-left">Email</x-th>
                <x-th class="text-center">Role</x-th>
                <x-th class="text-center">Toko</x-th>
                <x-th class="text-center">Aksi</x-th>
            </tr>
        </thead>
        <x-tbody id="tableBodyUsers" />
    </x-table>

    <!-- PAGINATION -->
    <div class="flex justify-center">
        <div id="paginationUsers" class="flex items-center gap-1 bg-white dark:bg-gray-800 border rounded-lg p-1 shadow-sm"></div>
    </div>

</div>

<script src="{{ asset('js/helpers/pagination.js') }}"></script>
<script>
    const perPageUsers = 10;
    let currentPageUsers = 1;
    let filteredUsers = [];

    const usersData = [];
    const tokoList = ['Toko A','Toko B','Toko C'];
    const roles = ['admin','user','repot','superadmin'];
    for (let i=1;i<=37;i++){
        usersData.push({id:i, name:'User '+i, email:'user'+i+'@example.com', role: roles[i%roles.length], toko: tokoList[i%tokoList.length]});
    }

    const baseUrlUsers = '{{ url('/master/users') }}';
    const csrfToken = '{{ csrf_token() }}';

    function applyUsersFilter(){
        const q = document.getElementById('filterSearch').value.trim().toLowerCase();
        const toko = document.getElementById('filterToko').value;

        filteredUsers = usersData.filter(row => {
            if (toko !== 'all' && row.toko !== toko) return false;
            if (q && !(row.name.toLowerCase().includes(q) || row.email.toLowerCase().includes(q) || row.role.toLowerCase().includes(q))) return false;
            return true;
        });

        currentPageUsers = 1; renderUsersTable();
    }

    document.getElementById('filterSearch').addEventListener('input', applyUsersFilter);
    document.getElementById('filterToko').addEventListener('change', applyUsersFilter);

    function renderUsersTable(){
        const tbody = document.getElementById('tableBodyUsers'); tbody.innerHTML='';
        const start = (currentPageUsers-1)*perPageUsers; const pageData = filteredUsers.slice(start, start+perPageUsers);

        pageData.forEach((row, idx)=>{
            const rowId = 'u-'+row.id;
            tbody.innerHTML += `
            <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <td class="p-3 text-center font-medium">${start+idx+1}</td>
                <td class="p-3 text-center"><button onclick="toggleUserDetail('${rowId}')" class="font-bold">+</button></td>
                <td class="p-3">${row.name}</td>
                <td class="p-3">${row.email}</td>
                <td class="p-3 text-center">${row.role}</td>
                <td class="p-3 text-center">${row.toko}</td>
                <td class="p-3 text-center">
                    <a href="${baseUrlUsers}/${row.id}/edit" class="inline-flex items-center px-3 py-1.5 border rounded text-sm text-white bg-yellow-500 hover:bg-yellow-600">Edit</a>
                    <form action="${baseUrlUsers}/${row.id}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border rounded text-sm text-white bg-red-600 hover:bg-red-700">Hapus</button>
                    </form>
                </td>
            </tr>

            <tr id="${rowId}" class="detail-row hidden bg-gray-50 dark:bg-gray-800">
                <td></td>
                <td colspan="6" class="p-3">
                    <div class="space-y-1">
                        <div><strong>Nama:</strong> ${row.name}</div>
                        <div><strong>Email:</strong> ${row.email}</div>
                        <div><strong>Role:</strong> ${row.role}</div>
                        <div><strong>Toko:</strong> ${row.toko}</div>
                    </div>
                </td>
            </tr>
            `;
        });

        renderPagination({containerId:'paginationUsers', currentPage: currentPageUsers, perPage: perPageUsers, totalData: filteredUsers.length, onChange: changeUsersPage});
    }

    window.changeUsersPage = function(p){ const total = Math.ceil(filteredUsers.length / perPageUsers); if (p<1 || p>total) return; currentPageUsers = p; renderUsersTable(); }

    function toggleUserDetail(id){ const row = document.getElementById(id); const btn = row.previousElementSibling.querySelector('button'); if (row.classList.contains('hidden')){ row.classList.remove('hidden'); btn.textContent='-'; } else { row.classList.add('hidden'); btn.textContent='+'; } }

    filteredUsers = usersData; renderUsersTable();
</script>
@endsection