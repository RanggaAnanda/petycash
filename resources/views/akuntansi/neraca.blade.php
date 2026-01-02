@extends('akuntansi.layout')

@section('title','Neraca')

@section('content')
<h2>Neraca</h2>

<table>
    <thead>
        <tr>
            <th>Kategori</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody id="neracaBody"></tbody>
</table>
@endsection

@section('script')
<script>
const body = document.getElementById('neracaBody');
const data = neraca();

Object.keys(data).forEach(tipe => {
    body.innerHTML += `
        <tr>
            <td>${tipe}</td>
            <td class="text-right">${data[tipe].toLocaleString()}</td>
        </tr>
    `;
});
</script>
@endsection
