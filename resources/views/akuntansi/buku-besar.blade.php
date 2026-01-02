@extends('akuntansi.layout')

@section('title','Buku Besar')

@section('content')
<h2>Buku Besar</h2>

<div id="bb"></div>
@endsection

@section('script')
<script>
const container = document.getElementById('bb');
const data = bukuBesar();

Object.keys(data).forEach(kode => {
    container.innerHTML += `<h3>${COA[kode].nama}</h3>`;

    let saldo = 0;
    let html = `
        <table>
            <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Debit</th>
                <th>Kredit</th>
                <th>Saldo</th>
            </tr>
    `;

    data[kode].forEach(j => {
        saldo += j.debit - j.kredit;
        html += `
            <tr>
                <td>${j.tgl}</td>
                <td>${j.ket}</td>
                <td class="text-right">${j.debit || '-'}</td>
                <td class="text-right">${j.kredit || '-'}</td>
                <td class="text-right">${saldo.toLocaleString()}</td>
            </tr>
        `;
    });

    html += '</table>';
    container.innerHTML += html;
});
</script>
@endsection
