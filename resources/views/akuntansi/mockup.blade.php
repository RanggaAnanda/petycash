<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Mockup Laporan Akuntansi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
        }
        header {
            background: #1f2937;
            color: #fff;
            padding: 15px 20px;
        }
        nav button {
            margin-right: 10px;
            padding: 8px 14px;
            border: none;
            background: #374151;
            color: #fff;
            cursor: pointer;
        }
        nav button.active {
            background: #2563eb;
        }
        .container {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }
        th {
            background: #f9fafb;
        }
        .text-right {
            text-align: right;
        }
        h2 {
            margin-bottom: 10px;
        }
        .saldo {
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<header>
    <h1>Sistem Akuntansi — Mockup</h1>
    <nav>
        <button onclick="showView('jurnal')" class="active">Jurnal Harian</button>
        <button onclick="showView('buku')">Buku Besar</button>
        <button onclick="showView('arus')">Arus Kas</button>
        <button onclick="showView('neraca')">Neraca</button>
    </nav>
</header>

<div class="container">

    <!-- JURNAL -->
    <div id="jurnal" class="view">
        <h2>Jurnal Harian</h2>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode</th>
                    <th>Akun</th>
                    <th class="text-right">Debit</th>
                    <th class="text-right">Kredit</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody id="jurnalBody"></tbody>
        </table>
    </div>

    <!-- BUKU BESAR -->
    <div id="buku" class="view" style="display:none">
        <h2>Buku Besar</h2>
        <select id="akunSelect" onchange="renderBukuBesar()"></select>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th class="text-right">Debit</th>
                    <th class="text-right">Kredit</th>
                </tr>
            </thead>
            <tbody id="bukuBody"></tbody>
        </table>
        <div class="saldo" id="saldoAkun"></div>
    </div>

    <!-- ARUS KAS -->
    <div id="arus" class="view" style="display:none">
        <h2>Laporan Arus Kas</h2>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th class="text-right">Masuk</th>
                    <th class="text-right">Keluar</th>
                </tr>
            </thead>
            <tbody id="arusBody"></tbody>
        </table>
    </div>

    <!-- NERACA -->
    <div id="neraca" class="view" style="display:none">
        <h2>Neraca</h2>
        <div id="neracaBody"></div>
    </div>

</div>

<script>
/* ==========================
   DUMMY JURNAL (SUMBER UTAMA)
========================== */
const jurnal = [
    { tanggal:'2025-01-01', kode:'JU-001', akun:'Kas', akun_id:'101', debit:5000000, kredit:0, ket:'Modal awal' },
    { tanggal:'2025-01-01', kode:'JU-001', akun:'Modal', akun_id:'301', debit:0, kredit:5000000, ket:'Modal awal' },

    { tanggal:'2025-01-02', kode:'JU-002', akun:'Beban Operasional', akun_id:'501', debit:750000, kredit:0, ket:'Beli ATK' },
    { tanggal:'2025-01-02', kode:'JU-002', akun:'Kas', akun_id:'101', debit:0, kredit:750000, ket:'Beli ATK' },

    { tanggal:'2025-01-03', kode:'JU-003', akun:'Kas', akun_id:'101', debit:2000000, kredit:0, ket:'Penjualan' },
    { tanggal:'2025-01-03', kode:'JU-003', akun:'Pendapatan', akun_id:'401', debit:0, kredit:2000000, ket:'Penjualan' }
];

/* ==========================
   NAVIGATION
========================== */
function showView(id) {
    document.querySelectorAll('.view').forEach(v => v.style.display = 'none');
    document.getElementById(id).style.display = 'block';

    document.querySelectorAll('nav button').forEach(b => b.classList.remove('active'));
    event.target.classList.add('active');
}

/* ==========================
   JURNAL
========================== */
function renderJurnal() {
    const body = document.getElementById('jurnalBody');
    body.innerHTML = '';
    jurnal.forEach(j => {
        body.innerHTML += `
            <tr>
                <td>${j.tanggal}</td>
                <td>${j.kode}</td>
                <td>${j.akun}</td>
                <td class="text-right">${j.debit.toLocaleString()}</td>
                <td class="text-right">${j.kredit.toLocaleString()}</td>
                <td>${j.ket}</td>
            </tr>
        `;
    });
}

/* ==========================
   BUKU BESAR
========================== */
function renderAkunSelect() {
    const select = document.getElementById('akunSelect');
    const akunUnik = [...new Set(jurnal.map(j => j.akun))];
    akunUnik.forEach(a => {
        select.innerHTML += `<option value="${a}">${a}</option>`;
    });
}

function renderBukuBesar() {
    const akun = document.getElementById('akunSelect').value;
    const body = document.getElementById('bukuBody');
    let saldo = 0;
    body.innerHTML = '';

    jurnal.filter(j => j.akun === akun).forEach(j => {
        saldo += j.debit - j.kredit;
        body.innerHTML += `
            <tr>
                <td>${j.tanggal}</td>
                <td>${j.ket}</td>
                <td class="text-right">${j.debit.toLocaleString()}</td>
                <td class="text-right">${j.kredit.toLocaleString()}</td>
            </tr>
        `;
    });

    document.getElementById('saldoAkun').innerText = 'Saldo Akhir: ' + saldo.toLocaleString();
}

/* ==========================
   ARUS KAS
========================== */
function renderArusKas() {
    const body = document.getElementById('arusBody');
    body.innerHTML = '';
    jurnal.filter(j => j.akun === 'Kas').forEach(j => {
        body.innerHTML += `
            <tr>
                <td>${j.tanggal}</td>
                <td>${j.ket}</td>
                <td class="text-right">${j.debit.toLocaleString()}</td>
                <td class="text-right">${j.kredit.toLocaleString()}</td>
            </tr>
        `;
    });
}

/* ==========================
   NERACA
========================== */
const kategoriAkun = {
    'Kas': 'ASET',
    'Modal': 'EKUITAS',
    'Pendapatan': 'EKUITAS',
    'Beban Operasional': 'EKUITAS'
};

function renderNeraca() {
    const neraca = {};
    jurnal.forEach(j => {
        if (!neraca[j.akun]) {
            neraca[j.akun] = { kategori: kategoriAkun[j.akun], saldo: 0 };
        }
        neraca[j.akun].saldo += j.debit - j.kredit;
    });

    let html = '';
    ['ASET', 'EKUITAS'].forEach(kat => {
        html += `<h3>${kat}</h3><ul>`;
        Object.keys(neraca).forEach(a => {
            if (neraca[a].kategori === kat) {
                html += `<li>${a} : ${neraca[a].saldo.toLocaleString()}</li>`;
            }
        });
        html += '</ul>';
    });

    document.getElementById('neracaBody').innerHTML = html;
}

/* ==========================
   INIT
========================== */
renderJurnal();
renderAkunSelect();
renderBukuBesar();
renderArusKas();
renderNeraca();
</script>

</body>
</html>
