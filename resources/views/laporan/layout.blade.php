@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-white uppercase">Laporan Buku Besar</h2>

    {{-- Navigasi Laporan --}}
    <nav class="flex gap-4 mt-4 mb-6">
        <a href="{{ route('laporan.pettycash.buku-besar') }}"
           class="{{ request()->is('laporan/pettycash/akuntansi/buku-besar') ? 'font-bold text-blue-600' : '' }}">
            Buku Besar
        </a>
        <a href="{{ route('laporan.pettycash.neraca') }}"
           class="{{ request()->is('laporan/pettycash/akuntansi/neraca-saldo') ? 'font-bold text-blue-600' : '' }}">
            Neraca Saldo
        </a>
        <a href="{{ route('laporan.pettycash.laba-rugi') }}"
           class="{{ request()->is('laporan/pettycash/akuntansi/laba-rugi') ? 'font-bold text-blue-600' : '' }}">
            Laba Rugi
        </a>
    </nav>

    {{-- Konten laporan akan dimasukkan --}}
    @yield('laporan-content')
</div>
@endsection
