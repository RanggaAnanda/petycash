@extends('layouts.app')

@section('title', 'Daftar Petty Cash')
@section('page-title', 'Daftar Petty Cash')

@section('content')
    <div class="space-y-6">

        <div>
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 ">
                Laporan Petty Cash
            </h2>
        </div>

        <!-- FILTER -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

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

                <!-- Kategori -->
                <div>
                    <x-input-label name="Kategori" class="mb-1 text-sm" />
                    <x-dropdown name="filterKategori" id="filterKategori" class="w-full" :options="['all' => 'Semua']" />
                </div>

                <!-- Sub Kategori -->
                <div>
                    <x-input-label name="Sub Kategori" class="mb-1 text-sm" />
                    <x-dropdown name="filterSubKategori" id="filterSubKategori" class="w-full" :options="['all' => 'Semua']" />
                </div>
            </div>

            <!-- Export -->
            <div class="flex md:justify-end mt-4">
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

        <!-- TABLE -->
        <x-table>
            <thead>
                <tr>
                    <x-th class="w-16 text-center">No</x-th>
                    <x-th>Tanggal</x-th>
                    <x-th>Toko</x-th>
                    <x-th class="hidden md:table-cell">Kode</x-th>
                    <x-th class="hidden md:table-cell">Kategori</x-th>
                    <x-th class="hidden md:table-cell">Sub Kategori</x-th>
                    <x-th class="hidden md:table-cell">Transaksi</x-th>
                    <x-th class="hidden md:table-cell">Saldo</x-th>
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

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="{{ asset('js/helpers/pagination.js') }}"></script>

    <script>
        const data = [];
        let saldo = 2000000;
        const perPage = 10;
        let currentPage = 1;
        let filteredData = [];

        // ===== DUMMY DATA =====

        const tokoList = ['Planet Fashion Bandung', 'Planet Fashion Jakarta', 'Planet Fashion Bekasi'];
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

        for (let i = 0; i < 50; i++) {
            // Random tanggal dalam 30 hari terakhir
            const date = new Date();
            date.setDate(date.getDate() - Math.floor(Math.random() * 30));

            // Random toko
            const toko = tokoList[Math.floor(Math.random() * tokoList.length)];

            const isMasuk = Math.random() > 0.4; // 60% keluar, 40% masuk

            let masuk = 0,
                keluar = 0,
                kategori = '',
                subKategori = null,
                keterangan = '';

            if (isMasuk) {
                masuk = 1000000 + Math.floor(Math.random() * 5) * 100000;
                kategori = 'Dari Keuangan';
                keterangan = 'Transfer dari bagian keuangan';
            } else {
                const kat = kategoriKeluar[Math.floor(Math.random() * kategoriKeluar.length)];
                const sub = kat.sub[Math.floor(Math.random() * kat.sub.length)];
                keluar = 50000 + Math.floor(Math.random() * 10) * 25000;
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



        // ===== KATEGORI & SUB =====
        const allKategori = ['Dari Keuangan', ...kategoriKeluar.map(k => k.nama)];
        const kategoriSelect = document.getElementById('filterKategori');
        kategoriSelect.innerHTML = `<option value="all">Semua</option>` + allKategori.map(k =>
            `<option value="${k}">${k}</option>`).join('');

        const subKategoriSelect = document.getElementById('filterSubKategori');
        kategoriSelect.addEventListener('change', () => {
            const selected = kategoriSelect.value;
            let subOptions = [];
            if (selected === 'all') subOptions = ['all'];
            else if (selected === 'Dari Keuangan') subOptions = ['all'];
            else {
                const kat = kategoriKeluar.find(k => k.nama === selected);
                subOptions = kat ? kat.sub : [];
                subOptions.unshift('all');
            }
            subKategoriSelect.innerHTML = subOptions.map(s =>
                `<option value="${s}">${s==='all'?'Semua':s}</option>`).join('');
            applyFilter();
        });

        // ===== FILTER =====
        function applyFilter() {
            const time = document.getElementById('filterTime').value;
            const type = document.getElementById('filterType').value;
            const toko = document.getElementById('filterToko').value;
            const kategori = document.getElementById('filterKategori').value;
            const subKategori = document.getElementById('filterSubKategori').value;
            const now = new Date();

            filteredData = data.filter(row => {
                if (time === 'today' && row.tanggal.toDateString() !== now.toDateString()) return false;
                if (time === 'week' && Math.floor((now - row.tanggal) / 86400000) > 7) return false;
                if (time === 'month' && Math.floor((now - row.tanggal) / 86400000) > 30) return false;
                if (type === 'masuk' && row.masuk === 0) return false;
                if (type === 'keluar' && row.keluar === 0) return false;
                if (toko !== 'all' && row.toko !== toko) return false;
                if (kategori !== 'all' && row.kategori !== kategori) return false;
                if (subKategori !== 'all' && row.subKategori !== subKategori) return false;
                return true;
            });

            currentPage = 1;
            renderTable();
        }

        ['filterTime', 'filterType', 'filterToko', 'filterKategori', 'filterSubKategori'].forEach(id => {
            document.getElementById(id).addEventListener('change', applyFilter);
        });

        // ===== RENDER TABLE =====
        function renderTable() {
            const tbody = document.getElementById('tableBody');
            tbody.innerHTML = '';

            const start = (currentPage - 1) * perPage;
            const pageData = filteredData.slice(start, start + perPage);

            pageData.forEach((row, index) => {
                const rowId = `row-${start+index}`;
                tbody.innerHTML += `
<tr class="border-b hover:bg-gray-100 dark:hover:bg-gray-700">
<td class="p-3 flex items-center gap-2">
    <button onclick="toggleDetail('${rowId}')" class="toggle-btn md:hidden transition-transform duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>
    ${start+index+1}
</td>
<td class="p-3">${row.tanggal.toLocaleDateString('id-ID')}</td>
<td class="p-3">${row.toko}</td>
<td class="p-3 hidden md:table-cell">${row.kode}</td>
<td class="p-3 hidden md:table-cell">${row.kategori??'-'}</td>
<td class="p-3 hidden md:table-cell">${row.subKategori??'-'}</td>
<td class="p-3 hidden md:table-cell ${row.masuk?'text-green-600':'text-red-600'}">
    ${row.masuk?'+'+row.masuk.toLocaleString('id-ID'):'-'+row.keluar.toLocaleString('id-ID')}
</td>
<td class="p-3 hidden md:table-cell font-semibold">${row.saldo.toLocaleString('id-ID')}</td>
</tr>
<tr id="${rowId}" class="hidden bg-gray-50 dark:bg-gray-800">
<td></td>
<td colspan="7" class="p-3 space-y-1 text-lg">
<div><strong>Kode:</strong> ${row.kode}</div>
<div><strong>Kategori:</strong> ${row.kategori??'-'}</div>
<div><strong>Sub Kategori:</strong> ${row.subKategori??'-'}</div>
<div><strong>Keterangan:</strong> ${row.keterangan}</div>
<div><strong>Transaksi:</strong> ${row.masuk?'+':'-'}${(row.masuk||row.keluar).toLocaleString('id-ID')}</div>
<div><strong>Saldo:</strong> ${row.saldo.toLocaleString('id-ID')}</div>
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

        window.changePage = function(page) {
            const totalPages = Math.ceil(filteredData.length / perPage);
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            renderTable();
        }

        function toggleDetail(id) {
            const detailRow = document.getElementById(id);
            const btn = detailRow.previousElementSibling.querySelector('.toggle-btn');
            detailRow.classList.toggle('hidden');
            btn.classList.toggle('rotate-90');
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

        // ================== EXPORT EXCEL ==================
        function exportExcel() {
            const type = document.getElementById('filterType').value;

            const sheetData = type === 'all' ? ['No', 'Tanggal', 'Toko', 'Kode', 'Kategori', 'Sub Kategori', 'Uang Masuk',
                    'Uang Keluar', 'Saldo'
                ] :
                type === 'masuk' ? ['No', 'Tanggal', 'Toko', 'Kode', 'Kategori', 'Sub Kategori', 'Uang Masuk', 'Saldo'] : [
                    'No', 'Tanggal', 'Toko', 'Kode', 'Kategori', 'Sub Kategori', 'Uang Keluar', 'Saldo'
                ];

            const dataRows = [];
            filteredData.forEach((row, index) => {
                if (type === 'all') dataRows.push([index + 1, row.tanggal.toLocaleDateString('id-ID'), row.toko, row
                    .kode, row.kategori, row.subKategori, row.masuk.toLocaleString('id-ID'), row.keluar
                    .toLocaleString('id-ID'), row.saldo.toLocaleString('id-ID')
                ]);
                else if (type === 'masuk') dataRows.push([index + 1, row.tanggal.toLocaleDateString('id-ID'), row
                    .toko, row.kode, row.kategori, row.subKategori, row.masuk.toLocaleString('id-ID'), row
                    .saldo.toLocaleString('id-ID')
                ]);
                else dataRows.push([index + 1, row.tanggal.toLocaleDateString('id-ID'), row.toko, row.kode, row
                    .kategori, row.subKategori, row.keluar.toLocaleString('id-ID'), row.saldo
                    .toLocaleString('id-ID')
                ]);
            });

            const ws = XLSX.utils.aoa_to_sheet([sheetData, ...dataRows]);

            const headerCells = ['A1', 'B1', 'C1', 'D1', 'E1', 'F1', 'G1', 'H1', 'I1'];
            headerCells.forEach(cell => {
                if (ws[cell]) {
                    ws[cell].s = {
                        font: {
                            bold: true,
                            color: {
                                rgb: '000000'
                            }
                        },
                        fill: {
                            fgColor: {
                                rgb: 'C0C0C0'
                            }
                        },
                        border: {
                            top: {
                                style: 'thin',
                                color: {
                                    rgb: '000000'
                                }
                            },
                            bottom: {
                                style: 'thin',
                                color: {
                                    rgb: '000000'
                                }
                            },
                            left: {
                                style: 'thin',
                                color: {
                                    rgb: '000000'
                                }
                            },
                            right: {
                                style: 'thin',
                                color: {
                                    rgb: '000000'
                                }
                            }
                        }
                    }
                }
            });

            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Petty Cash');
            XLSX.writeFile(wb, 'laporan-petty-cash.xlsx');
        }

        // ================== EXPORT PDF ==================
        function exportPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const type = document.getElementById('filterType').value;
            const doc = new jsPDF('p', 'pt', 'a4');

            const tableColumn = type === 'all' ? ['No', 'Tanggal', 'Toko', 'Kode', 'Kategori', 'Sub Kategori', 'Uang Masuk',
                    'Uang Keluar', 'Saldo'
                ] :
                type === 'masuk' ? ['No', 'Tanggal', 'Toko', 'Kode', 'Kategori', 'Sub Kategori', 'Uang Masuk', 'Saldo'] : [
                    'No', 'Tanggal', 'Toko', 'Kode', 'Kategori', 'Sub Kategori', 'Uang Keluar', 'Saldo'
                ];

            const tableRows = [];
            filteredData.forEach((row, index) => {
                if (type === 'all') tableRows.push([index + 1, row.tanggal.toLocaleDateString('id-ID'), row.toko,
                    row.kode, row.kategori, row.subKategori, row.masuk.toLocaleString('id-ID'), row.keluar
                    .toLocaleString('id-ID'), row.saldo.toLocaleString('id-ID')
                ]);
                else if (type === 'masuk') tableRows.push([index + 1, row.tanggal.toLocaleDateString('id-ID'), row
                    .toko, row.kode, row.kategori, row.subKategori, row.masuk.toLocaleString('id-ID'), row
                    .saldo.toLocaleString('id-ID')
                ]);
                else tableRows.push([index + 1, row.tanggal.toLocaleDateString('id-ID'), row.toko, row.kode, row
                    .kategori, row.subKategori, row.keluar.toLocaleString('id-ID'), row.saldo
                    .toLocaleString('id-ID')
                ]);
            });

            doc.setFontSize(12);
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(0, 0, 0);
            doc.text('Laporan Petty Cash', 40, 30);

            doc.autoTable({
                head: [tableColumn],
                body: tableRows,
                startY: 50,
                styles: {
                    font: 'helvetica',
                    fontSize: 12,
                    textColor: [0, 0, 0]
                },
                headStyles: {
                    fillColor: [192, 192, 192],
                    textColor: [0, 0, 0],
                    fontStyle: 'bold'
                },
                theme: 'grid'
            });

            doc.save('laporan-petty-cash.pdf');
        }

        // ===== INIT =====
        filteredData = data;
        renderTable();
    </script>
@endsection
