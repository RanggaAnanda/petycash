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

    <!-- CARD: LIST USERS HEADER + ACTIONS -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold">LIST USERS</h3>
            <a href="{{ route('master.users.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Add Users</a>
        </div>

        <div class="flex items-center justify-between mt-4">
            <div class="flex items-center gap-3">
                <select id="perPageUsers" class="border rounded px-2 py-1">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <span class="text-sm text-gray-500">entries per page</span>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-sm">Search:</label>
                <input id="filterSearch" type="text" class="px-3 py-2 rounded border" placeholder="Cari nama, email, role..." />
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
        <x-table>
            <thead>
                <tr>
                    <x-th class="w-12 text-center">No</x-th>
                    <x-th class="text-left">NAMA</x-th>
                    <x-th class="text-left">EMAIL</x-th>
                    <x-th class="text-center">TOKO</x-th>
                    <x-th class="text-center">ROLE</x-th>
                    <x-th class="w-24 text-center">EDIT</x-th>
                </tr>
            </thead>
            <x-tbody id="tableBodyUsers" />
        </x-table>

        <div class="flex items-center justify-between mt-3">
            <div id="infoUsers" class="text-sm text-gray-500">Showing 0 to 0 of 0 entries</div>
            <div id="paginationUsers" class="flex items-center gap-1 bg-white dark:bg-gray-800 border rounded-lg p-1 shadow-sm"></div>
        </div>
    </div>

</div>

<script src="{{ asset('js/helpers/pagination.js') }}"></script>
<script>
    // pagination & filtering variables
    let perPage = parseInt(document.getElementById('perPageUsers').value);
    let currentPage = 1;
    let filteredUsers = [];

    // sample/dummy data (replace with server-side data later)
    const usersData = [];
    const tokoList = ['PLANET FASHION KARANG GETAS CIREBON','PLANET FASHION PGC CIREBON','PLANET FASHION WERU CIREBON','PLANET FASHION KUNINGAN'];
    const roles = ['user','superadmin'];
    for (let i=1;i<=8;i++){
        usersData.push({id:i, name:`Planetfashion ${i}`, email:`user${i}@planetfashion.id`, role: roles[i%roles.length], toko: tokoList[i%tokoList.length]});
    }

    const baseUrlUsers = '{{ url('/master/users') }}';

    // DOM refs
    const perPageSelect = document.getElementById('perPageUsers');
    const searchBox = document.getElementById('filterSearch');
    const tbody = document.getElementById('tableBodyUsers');
    const infoEl = document.getElementById('infoUsers');

    function applyFilter(){
        const q = searchBox.value.trim().toLowerCase();
        filteredUsers = usersData.filter(u => {
            if (!q) return true;
            return u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q) || u.role.toLowerCase().includes(q) || u.toko.toLowerCase().includes(q);
        });
        currentPage = 1; renderTable();
    }

    perPageSelect.addEventListener('change', ()=>{ perPage = parseInt(perPageSelect.value); currentPage = 1; renderTable(); });
    searchBox.addEventListener('input', applyFilter);

    function renderTable(){
        tbody.innerHTML = '';
        const total = filteredUsers.length;
        const start = (currentPage-1)*perPage;
        const end = Math.min(start + perPage, total);
        const pageData = filteredUsers.slice(start, end);

        pageData.forEach((row, idx)=>{
            tbody.innerHTML += `
            <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <td class="p-3 text-center font-medium">${start+idx+1}</td>
                <td class="p-3">${row.name}</td>
                <td class="p-3">${row.email}</td>
                <td class="p-3 text-center">${row.toko}</td>
                <td class="p-3 text-center">${row.role}</td>
                <td class="p-3 text-center"><a href="${baseUrlUsers}/${row.id}/edit" class="text-blue-600">Edit</a></td>
            </tr>`;
        });

        // update info text
        if (total === 0) {
            infoEl.textContent = 'Showing 0 to 0 of 0 entries';
        } else {
            infoEl.textContent = `Showing ${start+1} to ${end} of ${total} entries`;
        }

        // render pagination
        renderPagination({
            containerId: 'paginationUsers',
            currentPage: currentPage,
            perPage: perPage,
            totalData: total,
            onChange: (p) => { changePage(p); }
        });
    }

    function changePage(p){ const totalPages = Math.ceil(filteredUsers.length / perPage); if (p<1 || p>totalPages) return; currentPage = p; renderTable(); }

    // init
    filteredUsers = usersData;
    renderTable();
</script>
@endsection