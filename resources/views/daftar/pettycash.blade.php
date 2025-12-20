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
                ]" placeholder="Pilih Kategori" />
            </div>

            <div>
                <x-input-label name="Jenis Transaksi" />
                <x-dropdown name="filterType" id="filterType" :options="[
                    'all' => 'Semua',
                    'masuk' => 'Uang Masuk',
                    'keluar' => 'Uang Keluar',
                ]" placeholder="Pilih Jenis Transaksi" />
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
                <x-th>Tanggal</x-th>
                <x-th class="text-center">Toko</x-th>
                <x-th class="text-center">Masuk</x-th>
                <x-th class="text-center">Keluar</x-th>
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
const nominalMasuk = [300000, 500000, 750000, 1000000];
const nominalKeluar = [50000, 75000, 100000, 150000, 200000];
let saldo = 2000000; // saldo awal

for (let i = 0; i < 30; i++) {
    const date = new Date();
    date.setDate(date.getDate() - i);
    const toko = tokoList[i % tokoList.length];

    let masuk = 0;
    let keluar = 0;
    if (i % 5 === 0) masuk = nominalMasuk[i % nominalMasuk.length];
    else if (i % 2 === 0) keluar = nominalKeluar[i % nominalKeluar.length];

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
        tbody.innerHTML += `
        <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 dark:hover:text-white transition">
            <td class="p-3 text-center font-medium">${start + index + 1}</td>
            <td class="p-3">${row.tanggal.toLocaleDateString('id-ID')}</td>
            <td class="p-3 text-center">${row.toko}</td>
            <td class="p-3 text-green-600 text-center">${row.masuk ? row.masuk.toLocaleString('id-ID') : '-'}</td>
            <td class="p-3 text-red-600 text-center">${row.keluar ? row.keluar.toLocaleString('id-ID') : '-'}</td>
            <td class="p-3 text-center font-semibold">${row.saldo.toLocaleString('id-ID')}</td>
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

// ===== INIT =====
filteredData = data;
renderTable();
</script>
@endsection
