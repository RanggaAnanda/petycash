@extends('akuntansi.layout')

@section('title','Jurnal Umum')

@section('content')
<h2>Jurnal Umum</h2>

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Kode</th>
            <th>Akun</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody id="jurnalBody"></tbody>
</table>
@endsection

@section('script')
<script>
const body = document.getElementById('jurnalBody');

getJurnal().forEach(j => {
    body.innerHTML += `
        <tr>
            <td>${j.tgl}</td>
            <td>${j.kode}</td>
            <td>${COA[j.akun].nama}</td>
            <td class="text-right">${j.debit ? j.debit.toLocaleString() : '-'}</td>
            <td class="text-right">${j.kredit ? j.kredit.toLocaleString() : '-'}</td>
            <td>${j.ket}</td>
        </tr>
    `;
});
</script>
@endsection
