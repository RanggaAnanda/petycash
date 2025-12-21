@extends('layouts.app')

@section('title', 'Daftar Petty Cash')
@section('page-title', 'Daftar Petty Cash')

@section('content')
<div class="space-y-6">

    <!-- FILTER -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
        <div class="flex flex-wrap gap-4 items-end">

            <div>
                <x-input-label name="Rentang Waktu" />
                <x-dropdown name="filter" id="filterTime" :options="[
                    'all' => 'Semua',
                    'today' => 'Hari Ini',
                    'week' => '1 Minggu Terakhir',
                    'month' => '1 Bulan Terakhir',
                ]" />
            </div>

            <div>
                <x-input-label name="Jenis Transaksi" />
                <x-dropdown name="filterType" id="filterType" :options="[
                    'all' => 'Semua',
                    'masuk' => 'Uang Masuk',
                    'keluar' => 'Uang Keluar',
                ]" />
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

        </div>
    </div>

    <!-- TABLE -->
    <x-table>
        <thead>
            <tr>
                <x-th class="w-12 text-center">No</x-th>
                <x-th class="w-10 text-center" colspan="2">Tanggal</x-th>
                <x-th class="text-center">Toko</x-th>
                <x-th class="text-center">transaksi</x-th>
                <x-th class="text-center">Saldo</x-th>
            </tr>
        </thead>
        <x-tbody id="tableBody" />
    </x-table>

    <!-- PAGINATION -->
    <div class="flex justify-center">
        <div id="pagination" class="flex items-center gap-1 bg-white dark:bg-gray-800 border rounded-lg p-1 shadow-sm"></div>
    </div>
</div>

<script src="{{ asset('js/helpers/pagination.js') }}"></script>
<script>
const perPage = 10;
let currentPage = 1;
let filteredData = [];

// ===== DUMMY DATA =====
const data = [];
const tokoList = ['Toko A', 'Toko B', 'Toko C'];
let saldo = 2000000; // saldo awal

for (let i = 0; i < 30; i++) {
    const date = new Date();
    date.setDate(date.getDate() - i);
    const toko = tokoList[i % tokoList.length];

    // TRANSAKSI POSITIF ATAU NEGATIF
    let masuk = 0;
    let keluar = 0;

    // Contoh transaksi acak
    if (i % 6 === 0) masuk = 15000000; // hijau
    else if (i % 4 === 0) keluar = 294000; // merah
    else if (i % 5 === 0) masuk = 500000;
    else if (i % 2 === 0) keluar = 100000;

    saldo = saldo + masuk - keluar;

    data.push({ tanggal: date, toko, masuk, keluar, saldo });
}


// ===== FILTER =====
function applyFilter() {
    const time = document.getElementById('filterTime').value;
    const type = document.getElementById('filterType').value;
    const toko = document.getElementById('filterToko').value;
    const now = new Date();

    filteredData = data.filter(row => {
        if (time === 'today' && row.tanggal.toDateString() !== now.toDateString()) return false;
        if (time === 'week' && Math.floor((now - row.tanggal)/86400000) > 7) return false;
        if (time === 'month' && Math.floor((now - row.tanggal)/86400000) > 30) return false;

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
            <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white transition">
                <td class="p-3 text-center font-medium">${start + index + 1}</td>
                <td class="p-3 text-center font-medium">
                    <button onclick="toggleDetail('${rowId}')" class="font-bold text-lg">+</button>
                </td>
                <td class="p-3">${row.tanggal.toLocaleDateString('id-ID')}</td>
                <td class="p-3 text-center">${row.toko}</td>
                <td class="p-3 text-center ${row.masuk ? 'text-green-600' : row.keluar ? 'text-red-600' : ''}">
                    ${row.masuk ? '+' + row.masuk.toLocaleString('id-ID') : row.keluar ? '-' + row.keluar.toLocaleString('id-ID') : '-'}
                </td>
                <td class="p-3 text-center font-semibold">${row.saldo.toLocaleString('id-ID')}</td>
            </tr>`;

        tbody.innerHTML += `
            <tr id="${rowId}" class="detail-row hidden border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-800 transition">
                <td></td>
                <td colspan="5" class="p-3">
                    <div class="space-y-1">
                        <div><strong>Toko:</strong> ${row.toko}</div>
                        <div><strong>Jenis Transaksi:</strong> ${row.masuk ? 'Uang Masuk' : row.keluar ? 'Uang Keluar' : 'Tidak ada transaksi'}</div>
                        <div><strong>Jumlah Uang:</strong> 
                            <span class="${row.masuk ? 'text-green-600' : row.keluar ? 'text-red-600' : ''}">
                                ${row.masuk ? row.masuk.toLocaleString('id-ID') : row.keluar ? row.keluar.toLocaleString('id-ID') : '-'}
                            </span>
                        </div>
                        <div><strong>Kategori:</strong> ${row.masuk || row.keluar ? 'Atk' : '-'}</div>
                        <div><strong>Sisa Saldo:</strong> ${row.saldo.toLocaleString('id-ID')}</div>
                        ${
                            row.masuk 
                            ? `<a href="{{ route('form.edit.uang-masuk') }}">
                                    <button class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Edit</button>
                            </a>` 
                            : row.keluar 
                            ? `<a href="{{ route('form.edit.uang-keluar') }}">
                                    <button class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Edit</button>
                            </a>` 
                            : ''
                        }
                    </div>
                </td>
            </tr>`;

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
    const btn = detailRow.previousElementSibling.querySelector('button');

    if (detailRow.classList.contains('hidden')) {
        detailRow.classList.remove('hidden');
        btn.textContent = '-';
    } else {
        detailRow.classList.add('hidden');
        btn.textContent = '+';
    }
}

// ===== INIT =====
filteredData = data;
renderTable();
</script>
@endsection
