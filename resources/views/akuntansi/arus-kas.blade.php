@extends('akuntansi.layout')

@section('title','Arus Kas')

@section('content')
<h2>Laporan Arus Kas</h2>

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Masuk</th>
            <th>Keluar</th>
        </tr>
    </thead>
    <tbody id="arusBody"></tbody>
</table>
@endsection

@section('script')
<script>
const body = document.getElementById('arusBody');

arusKas().forEach(j => {
    body.innerHTML += `
        <tr>
            <td>${j.tgl}</td>
            <td>${j.ket}</td>
            <td class="text-right">${j.debit || '-'}</td>
            <td class="text-right">${j.kredit || '-'}</td>
        </tr>
    `;
});
</script>
@endsection
