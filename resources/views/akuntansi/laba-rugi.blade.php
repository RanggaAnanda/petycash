@extends('akuntansi.layout')

@section('title','Laba Rugi')

@section('content')
<h2>Laporan Laba Rugi</h2>

<table>
    <tr>
        <td>Pendapatan</td>
        <td id="pendapatan" class="text-right"></td>
    </tr>
    <tr>
        <td>Beban</td>
        <td id="beban" class="text-right"></td>
    </tr>
    <tr>
        <th>Laba Bersih</th>
        <th id="laba" class="text-right"></th>
    </tr>
</table>
@endsection

@section('script')
<script>
const lr = labaRugi();

document.getElementById('pendapatan').innerText = lr.pendapatan.toLocaleString();
document.getElementById('beban').innerText = lr.beban.toLocaleString();
document.getElementById('laba').innerText = lr.laba.toLocaleString();
</script>
@endsection
