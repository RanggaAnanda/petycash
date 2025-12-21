@extends('layouts.app')

@section('title', 'Daftar Omset')
@section('page-title', 'Daftar Omset')

@section('content')
    <!-- LIBRARY -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

    <div class="space-y-6">

        <div>
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
                Laporan Omset
            </h2>
        </div>

        <!-- FILTER -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <!-- Rentang Waktu -->
                <div>
                    <x-input-label name="Rentang Waktu" />
                    <x-dropdown name="rentang" id="filterTime" :options="[
                        'all' => 'Semua',
                        'today' => 'Hari Ini',
                        'week' => '1 Minggu Terakhir',
                        'month' => '1 Bulan Terakhir',
                    ]" />
                </div>
                <!-- Toko -->
                <div>
                    <x-input-label name="Toko" />
                    <x-dropdown name="toko" id="filterToko" :options="[
                        'all' => 'Semua Toko',
                        'Planet Fashion Bandung' => 'Planet Fashion Bandung',
                        'Planet Fashion Jakarta' => 'Planet Fashion Jakarta',
                        'Planet Fashion Bekasi' => 'Planet Fashion Bekasi',
                    ]" />
                </div>
                <!-- Export -->
                <div class="flex md:justify-end">
                    <div class="relative inline-block text-left">
                        <button id="exportBtn"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700">
                            Export
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="exportMenu"
                            class="hidden absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 border rounded-lg shadow z-50">
                            <a href="#" data-type="excel"
                                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                Export Excel
                            </a>
                            <a href="#" data-type="pdf"
                                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                Export PDF
                            </a>
                        </div>
                    </div>
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
                    <x-th class="hidden md:table-cell text-center">Omset</x-th>
                </tr>
            </thead>
            <x-tbody id="tableBody" />
        </x-table>

        <!-- TOTAL -->
        <div id="totalOmset"
            class="bg-white dark:bg-gray-800 border rounded-lg p-4 shadow flex justify-between items-center">
            <span class="font-semibold text-gray-700 dark:text-gray-200">Total Omset</span>
            <span id="totalOmsetValue" class="text-green-600 font-bold text-lg">Rp 0</span>
        </div>

        <!-- PAGINATION -->
        <div class="flex justify-center">
            <div id="pagination" class="flex items-center gap-1 bg-white dark:bg-gray-800 border rounded-lg p-1 shadow-sm">
            </div>
        </div>

    </div>

    <script src="{{ asset('js/helpers/pagination.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const perPage = 10;
            let currentPage = 1;
            let filteredData = [];

            // ===== DUMMY DATA =====
            const data = [];
            const tokoList = ['Planet Fashion Bandung', 'Planet Fashion Jakarta', 'Planet Fashion Bekasi'];
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
            filteredData = [...data];

            // ===== FILTER =====
            function applyFilter() {
                const time = document.getElementById('filterTime').value;
                const toko = document.getElementById('filterToko').value;
                const now = new Date();

                filteredData = data.filter(row => {
                    const diff = Math.floor((now - row.tanggal) / 86400000);
                    if (time === 'today' && diff !== 0) return false;
                    if (time === 'week' && diff > 7) return false;
                    if (time === 'month' && diff > 30) return false;
                    if (toko !== 'all' && row.toko !== toko) return false;
                    return true;
                });
                currentPage = 1;
                renderTable();
            }
            filterTime.onchange = applyFilter;
            filterToko.onchange = applyFilter;

            // ===== RENDER TOTAL =====
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
                            <button onclick="toggleDetail('${rowId}')" class="toggle-btn md:hidden transition-transform duration-200">
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
                        <td class="p-3 hidden md:table-cell text-green-600 font-semibold text-center">
                            Rp ${row.omset.toLocaleString('id-ID')}
                        </td>
                    </tr>
                    <tr id="${rowId}" class="hidden bg-gray-50 dark:bg-gray-800 md:hidden">
                        <td></td>
                        <td colspan="3" class="p-3 text-lg">
                            <div class="flex justify-between">
                                <span class="font-medium">Omset</span>
                                <span class="text-green-600 font-semibold">
                                    Rp ${row.omset.toLocaleString('id-ID')}
                                </span>
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
                renderTotalOmset();
            }

            window.changePage = function(page) {
                const totalPages = Math.ceil(filteredData.length / perPage);
                if (page < 1 || page > totalPages) return;
                currentPage = page;
                renderTable();
            }

            window.toggleDetail = function(id) {
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

            // ===== EXPORT =====
            const exportBtn = document.getElementById('exportBtn');
            const exportMenu = document.getElementById('exportMenu');

            exportBtn.onclick = () => exportMenu.classList.toggle('hidden');

            exportMenu.querySelectorAll('a').forEach(item => {
                item.onclick = e => {
                    e.preventDefault();
                    const type = item.dataset.type;
                    if (type === 'excel') exportExcel();
                    if (type === 'pdf') exportPDF();
                    exportMenu.classList.add('hidden');
                }
            });

            document.addEventListener('click', e => {
                if (!exportBtn.contains(e.target) && !exportMenu.contains(e.target)) {
                    exportMenu.classList.add('hidden');
                }
            });

            // ===== EXPORT EXCEL =====
            function exportExcel() {
                const sheetData = [
                    ['No', 'Tanggal', 'Toko', 'Omset']
                ];

                filteredData.forEach((row, index) => {
                    sheetData.push([
                        index + 1,
                        row.tanggal.toLocaleDateString('id-ID'),
                        row.toko,
                        row.omset
                    ]);
                });

                const totalOmset = filteredData.reduce((sum, row) => sum + row.omset, 0);
                sheetData.push([]);
                sheetData.push(['', '', 'TOTAL OMSET', totalOmset]);

                const ws = XLSX.utils.aoa_to_sheet(sheetData);
                ws['!cols'] = [{
                    wch: 5
                }, {
                    wch: 15
                }, {
                    wch: 30
                }, {
                    wch: 18
                }];

                // Header style abu-abu + font hitam
                ['A1', 'B1', 'C1', 'D1'].forEach(cell => {
                    if (ws[cell]) ws[cell].s = {
                        font: {
                            bold: true,
                            color: {
                                rgb: "000000"
                            }
                        },
                        fill: {
                            fgColor: {
                                rgb: "C0C0C0"
                            }
                        },
                        border: {
                            top: {
                                style: "thin",
                                color: {
                                    rgb: "000000"
                                }
                            },
                            bottom: {
                                style: "thin",
                                color: {
                                    rgb: "000000"
                                }
                            },
                            left: {
                                style: "thin",
                                color: {
                                    rgb: "000000"
                                }
                            },
                            right: {
                                style: "thin",
                                color: {
                                    rgb: "000000"
                                }
                            }
                        }
                    }
                });

                const totalRow = sheetData.length;
                ['C', 'D'].forEach(col => {
                    const cell = ws[`${col}${totalRow}`];
                    if (cell) cell.s = {
                        font: {
                            bold: true
                        }
                    };
                });

                // Format currency
                for (let r = 2; r <= totalRow; r++) {
                    const cell = ws[`D${r}`];
                    if (cell && typeof cell.v === 'number') {
                        cell.t = 'n';
                        cell.z = '#,##0';
                    }
                }

                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, 'Laporan Omset');
                XLSX.writeFile(wb, 'laporan-omset.xlsx');
            }


            // ===== EXPORT PDF =====
            function exportPDF() {
                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF('p', 'pt', 'a4');

                doc.setFontSize(14);
                doc.setFont('helvetica', 'bold');
                doc.text("Laporan Omset", 40, 40);

                const tableColumn = ["No", "Tanggal", "Toko", "Omset"];
                const tableRows = [];

                filteredData.forEach((row, index) => {
                    tableRows.push([
                        index + 1,
                        row.tanggal.toLocaleDateString('id-ID'),
                        row.toko,
                        row.omset.toLocaleString('id-ID')
                    ]);
                });

                const totalOmset = filteredData.reduce((sum, row) => sum + row.omset, 0);
                tableRows.push(['', '', 'TOTAL OMSET', totalOmset.toLocaleString('id-ID')]);

                doc.autoTable({
                    head: [tableColumn],
                    body: tableRows,
                    startY: 60,
                    styles: {
                        font: 'helvetica',
                        fontSize: 12,
                        textColor: [0, 0, 0]
                    },
                    headStyles: {
                        fillColor: [192, 192, 192], // header abu-abu
                        textColor: [0, 0, 0],
                        fontStyle: 'bold'
                    },
                    footStyles: {
                        fontStyle: 'bold',
                        textColor: [0, 0, 0]
                    },
                    theme: 'grid'
                });

                doc.save('laporan-omset.pdf');
            }



            // ===== INIT TABLE =====
            renderTable();
        });
    </script>
@endsection
