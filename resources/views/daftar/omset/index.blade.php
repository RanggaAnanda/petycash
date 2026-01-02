@extends('layouts.app')

@section('title', 'Daftar Omset')
@section('page-title', 'Daftar Omset')

@section('content')
    <div class="space-y-6">

        <div>
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 ">
                Daftar Omset
            </h2>
        </div>

        <!-- ================= FILTER ================= -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

                <div>
                    <x-input-label name="Rentang Waktu" />
                    <x-dropdown name="rentang" id="filterTime" :options="[
                        'all' => 'Semua',
                        'today' => 'Hari Ini',
                        'week' => '1 Minggu Terakhir',
                        'month' => '1 Bulan Terakhir',
                    ]" />
                </div>

                <div>
                    <x-input-label name="Toko" />
                    <x-dropdown name="toko" id="filterToko" :options="[
                        'all' => 'Semua Toko',
                        'Planet Fashion Bandung' => 'Planet Fashion Bandung',
                        'Planet Fashion Jakarta' => 'Planet Fashion Jakarta',
                        'Planet Fashion Bekasi' => 'Planet Fashion Bekasi',
                    ]" />
                </div>

            </div>
        </div>

        <x-table>
            <thead>
                <tr>
                    <x-th class="w-16 text-center">No</x-th>
                    <x-th>Tanggal</x-th>
                    <x-th>Toko</x-th>

                    <!-- Desktop only -->
                    <x-th class="hidden md:table-cell text-center">Omset</x-th>
                    <x-th class="hidden md:table-cell text-center">Action</x-th>
                </tr>
            </thead>

            <x-tbody id="tableBody" />
        </x-table>

        <!-- TOTAL OMSET -->
        <div id="totalOmset"
            class="bg-white dark:bg-gray-800 border rounded-lg p-4 shadow flex justify-between items-center">
            <span class="font-semibold text-gray-700 dark:text-gray-200">
                Total Omset
            </span>
            <span id="totalOmsetValue" class="text-green-600 font-bold text-lg">
                Rp 0
            </span>
        </div>



        <!-- ================= PAGINATION ================= -->
        <div class="flex justify-center">
            <div id="pagination" class="flex items-center gap-1 bg-white dark:bg-gray-800 border rounded-lg p-1 shadow-sm">
            </div>
        </div>

    </div>

    <script src="{{ asset('js/helpers/pagination.js') }}"></script>
    <script>
        const perPage = 10;
        let currentPage = 1;
        let filteredData = [];

        // ===== DUMMY DATA =====
        const data = [];
        const tokoList = [
            'Planet Fashion Bandung',
            'Planet Fashion Jakarta',
            'Planet Fashion Bekasi'
        ];

        for (let i = 0; i < 10; i++) {
            const date = new Date();
            date.setDate(date.getDate() - i);

            tokoList.forEach((toko, idx) => {
                data.push({
                    tanggal: new Date(date),
                    toko,
                    omset: 1500000 + (idx * 300000) + (i * 50000)
                });
            });
        }

        // ===== FILTER =====
        function applyFilter() {
            const time = document.getElementById('filterTime').value;
            const toko = document.getElementById('filterToko').value;
            const now = new Date();

            filteredData = data.filter(row => {
                const diffDay = Math.floor((now - row.tanggal) / 86400000);

                if (time === 'today' && diffDay !== 0) return false;
                if (time === 'week' && diffDay > 7) return false;
                if (time === 'month' && diffDay > 30) return false;

                if (toko !== 'all' && row.toko !== toko) return false;

                return true;
            });

            currentPage = 1;
            renderTable();
        }

        document.getElementById('filterTime').addEventListener('change', applyFilter);
        document.getElementById('filterToko').addEventListener('change', applyFilter);

        // ===== RENDER OMSET =====
        function renderTotalOmset() {
            const total = filteredData.reduce((sum, row) => sum + row.omset, 0);
            document.getElementById('totalOmsetValue').textContent =
                'Rp ' + total.toLocaleString('id-ID');
        }

        // ===== RENDER TABLE =====
        function renderTable() {
            const tbody = document.getElementById('tableBody');
            tbody.innerHTML = '';

            const start = (currentPage - 1) * perPage;
            const pageData = filteredData.slice(start, start + perPage);

            pageData.forEach((row, index) => {
                const rowId = `row-${start + index}`;

                tbody.innerHTML += `
            <tr class="border-b hover:bg-gray-100 dark:hover:bg-gray-700">
                <td class="p-3 flex items-center gap-2">
                        <button 
                            onclick="toggleDetail('${rowId}')" 
                            class="toggle-btn md:hidden transition-transform duration-200"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 text-gray-600 dark:text-gray-300"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        ${start + index + 1}
                    </td>

                <td class="p-3">
                    ${row.tanggal.toLocaleDateString('id-ID')}
                </td>

                <td class="p-3">
                    ${row.toko}
                </td>

                <!-- Desktop -->
                <td class="p-3 hidden md:table-cell text-green-600 font-semibold text-center">
                    Rp ${row.omset.toLocaleString('id-ID')}
                </td>
                <td class="p-3 hidden md:table-cell text-center">
                    <a href="{{ route('forms.omset.edit') }}"
                    class="inline-flex items-center justify-center px-3 py-1 rounded-lg
                    bg-blue-400 hover:bg-blue-500 text-white text-lg font-medium">
                        Edit
                    </a>
                </td>
            </tr>

            <!-- MOBILE DETAIL -->
            <tr id="${rowId}" class="hidden bg-gray-50 dark:bg-gray-800 md:hidden">
                <td></td>
                <td colspan="3" class="p-3 text-lg">
                    <div class="flex">
                        <span class="font-medium">Omset :</span>
                        <span class="text-green-600 font-semibold ml-2">
                            Rp ${row.omset.toLocaleString('id-ID')}
                        </span>
                    </div>
                    <div class="pt-2 mt-2 border-t flex w-full md:hidden">
                        <a href="{{ route('forms.omset.edit') }}"
                        class="inline-flex items-center justify-center px-6 py-1 rounded-lg
                                bg-blue-400 hover:bg-blue-500 text-white text-lg font-medium">
                            Edit
                        </a>
                    </div>
                </td>
            </tr>
            `;
            });

            renderPagination({
                containerId: 'pagination',
                currentPage,
                perPage,
                totalData: filteredData.length,
                onChange: changePage
            });

            renderTotalOmset()
        }

        // ===== PAGINATION =====
        window.changePage = function(page) {
            const totalPages = Math.ceil(filteredData.length / perPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderTable();
        }

        function toggleDetail(id) {
            const detailRow = document.getElementById(id);
            const btn = detailRow.previousElementSibling.querySelector('.toggle-btn');

            if (detailRow.classList.contains('hidden')) {
                detailRow.classList.remove('hidden');
                btn.classList.add('rotate-90');
            } else {
                detailRow.classList.add('hidden');
                btn.classList.remove('rotate-90');
            }
        }

        // ===== INIT =====
        filteredData = data;
        renderTable();
    </script>

@endsection
