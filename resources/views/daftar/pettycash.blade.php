@extends('layouts.app')

@section('title', 'Daftar Petty Cash')
@section('page-title', 'Daftar Petty Cash')

@section('content')
    <div class="space-y-6">

        <div>
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 ">
                Daftar Petty Cash
            </h2>
        </div>

        <!-- FILTER -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <!-- Rentang Waktu -->
                <div>
                    <x-input-label name="Rentang Waktu" class="mb-1 text-sm" />
                    <x-dropdown name="filter" id="filterTime" class="w-full" :options="[
                        'all' => 'Semua',
                        'today' => 'Hari Ini',
                        'week' => '1 Minggu Terakhir',
                        'month' => '1 Bulan Terakhir',
                    ]" />
                </div>

                <!-- Jenis Transaksi -->
                <div>
                    <x-input-label name="Jenis Transaksi" class="mb-1 text-sm" />
                    <x-dropdown name="filterType" id="filterType" class="w-full" :options="[
                        'all' => 'Semua',
                        'masuk' => 'Uang Masuk',
                        'keluar' => 'Uang Keluar',
                    ]" />
                </div>

                <!-- Toko -->
                <div>
                    <x-input-label name="Toko" class="mb-1 text-sm" />
                    <x-dropdown name="filterToko" id="filterToko" class="w-full" :options="[
                        'all' => 'Semua Toko',
                        'Planet Fashion Bandung' => 'Planet Fashion Bandung',
                        'Planet Fashion Jakarta' => 'Planet Fashion Jakarta',
                        'Planet Fashion Bekasi' => 'Planet Fashion Bekasi',
                    ]" />
                </div>

            </div>
        </div>


        <!-- TABLE -->
        <x-table>
            <thead>
                <tr>
                    <x-th class="w-16 text-center">No</x-th>
                    <x-th>Tanggal</x-th>
                    <x-th>Toko</x-th>

                    <!-- Desktop only -->
                    <x-th class="hidden md:table-cell">Kode</x-th>
                    <x-th class="hidden md:table-cell">Kategori</x-th>
                    <x-th class="hidden md:table-cell">Sub Kategori</x-th>
                    <x-th class="hidden md:table-cell">Transaksi</x-th>
                    <x-th class="hidden md:table-cell">Saldo</x-th>
                    <x-th class="hidden md:table-cell text-center">Action</x-th>

                </tr>
            </thead>

            <x-tbody id="tableBody" />
        </x-table>

        <!-- PAGINATION -->
        <div class="flex justify-center">
            <div id="pagination" class="flex items-center gap-1 bg-white dark:bg-gray-800 border rounded-lg p-1 shadow-sm">
            </div>
        </div>
    </div>

    <script src="{{ asset('js/helpers/pagination.js') }}"></script>
    <script>
        const data = [];
        let saldo = 2000000;
        const perPage = 10;
        let currentPage = 1;
        let filteredData = [];

        // ===== DUMMY DATA =====

        const tokoList = [
            'Planet Fashion Bandung',
            'Planet Fashion Jakarta',
            'Planet Fashion Bekasi'
        ];

        // Kategori untuk transaksi KELUAR
        const kategoriKeluar = [{
                nama: 'ATK',
                sub: ['Solasi', 'Kertas', 'Pulpen', 'Tinta Printer']
            },
            {
                nama: 'Makan & Minum',
                sub: ['Air Mineral', 'Kopi', 'Snack', 'Makan Siang']
            },
            {
                nama: 'Operasional',
                sub: ['Listrik', 'Internet', 'Kebersihan']
            }
        ];

        for (let i = 0; i < 30; i++) {
            const date = new Date();
            date.setDate(date.getDate() - i);

            const toko = tokoList[i % tokoList.length];
            const isMasuk = i % 3 === 0; // 1 masuk, 2 keluar (lebih realistis)

            let masuk = 0;
            let keluar = 0;
            let kategori = '';
            let subKategori = null;
            let keterangan = '';

            if (isMasuk) {
                masuk = 1000000 + (Math.floor(Math.random() * 5) * 100000);
                kategori = 'Dari Keuangan';
                keterangan = 'Transfer dari bagian keuangan';
            } else {
                const kat = kategoriKeluar[i % kategoriKeluar.length];
                const sub = kat.sub[i % kat.sub.length];

                keluar = 50000 + (Math.floor(Math.random() * 10) * 25000);
                kategori = kat.nama;
                subKategori = sub;
                keterangan = sub;
            }

            saldo = saldo + masuk - keluar;

            data.push({
                tanggal: date,
                toko,
                kode: `${1000 + i}`,
                kategori,
                subKategori,
                keterangan,
                masuk,
                keluar,
                saldo
            });
        }

        // ===== FILTER =====
        function applyFilter() {
            const time = document.getElementById('filterTime').value;
            const type = document.getElementById('filterType').value;
            const toko = document.getElementById('filterToko').value;
            const now = new Date();

            filteredData = data.filter(row => {
                if (time === 'today' && row.tanggal.toDateString() !== now.toDateString()) return false;
                if (time === 'week' && Math.floor((now - row.tanggal) / 86400000) > 7) return false;
                if (time === 'month' && Math.floor((now - row.tanggal) / 86400000) > 30) return false;

                if (type === 'masuk' && row.masuk === 0) return false;
                if (type === 'keluar' && row.keluar === 0) return false;

                if (toko !== 'all' && row.toko !== toko) return false;

                return true;
            });

            currentPage = 1;
            renderTable();
        }

        document.getElementById('filterTime').addEventListener('change', applyFilter);
        document.getElementById('filterType').addEventListener('change', applyFilter);
        document.getElementById('filterToko').addEventListener('change', applyFilter);

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

                    <td class="p-3">${row.tanggal.toLocaleDateString('id-ID')}</td>
                    <td class="p-3">${row.toko}</td>

                    <!-- Desktop -->
                    <td class="p-3 hidden md:table-cell">${row.kode}</td>
                    <td class="p-3 hidden md:table-cell">${row.kategori ?? '-'}</td>
                    <td class="p-3 hidden md:table-cell">${row.subKategori ?? '-'}</td>
                    <td class="p-3 hidden md:table-cell 
                        ${row.masuk ? 'text-green-600' : 'text-red-600'}">
                        ${row.masuk 
                            ? '+' + row.masuk.toLocaleString('id-ID') 
                            : '-' + row.keluar.toLocaleString('id-ID')}
                    </td>
                    <td class="p-3 hidden md:table-cell font-semibold">
                        ${row.saldo.toLocaleString('id-ID')}
                    </td>
                    <td class="p-3 hidden md:table-cell text-center">
                        <a href="${row.masuk 
                            ? '{{ route('form.edit.uang-masuk') }}' 
                            : '{{ route('form.edit.uang-keluar') }}'}"
                        class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg
                        bg-blue-400 hover:bg-blue-500 text-white text-sm font-medium">
                            Edit
                        </a>
                    </td>
                </tr>
                `;


                tbody.innerHTML += `
                <tr id="${rowId}" class="hidden bg-gray-50 dark:bg-gray-800">
                    <td></td>
                    <td colspan="7" class="p-3 space-y-1 text-lg">
                        <div><strong>Kode:</strong> ${row.kode}</div>
                        <div><strong>Kategori:</strong> ${row.kategori ?? '-'}</div>
                        <div><strong>Sub Kategori:</strong> ${row.subKategori ?? '-'}</div>
                        <div><strong>Keterangan:</strong> ${row.keterangan}</div>
                        <div><strong>Transaksi:</strong>
                            <span class="${row.masuk ? 'text-green-600' : 'text-red-600'}">
                                ${row.masuk ? '+' : '-'}${(row.masuk || row.keluar).toLocaleString('id-ID')}
                            </span>
                        </div>
                        <div><strong>Saldo:</strong> ${row.saldo.toLocaleString('id-ID')}</div>
                        <div class="pt-2 mt-2 border-t flex w-full md:hidden">
                            <a href="{row.masuk 
                                ? '{{ route('form.edit.uang-masuk') }}' 
                                : '{{ route('form.edit.uang-keluar') }}'}"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-lg
                                    bg-blue-400 hover:bg-blue-500 text-white text-sm font-medium">
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
        }

        // ===== CHANGE PAGE =====
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
