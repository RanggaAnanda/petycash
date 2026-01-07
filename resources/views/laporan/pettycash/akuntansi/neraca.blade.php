@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white uppercase">Neraca Saldo</h2>
    </div>

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

    {{-- Tabel Neraca --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <tr class="text-xs font-black text-gray-500 uppercase tracking-widest">
                        <th class="p-3">Akun</th>
                        <th class="p-3 text-right">Debit</th>
                        <th class="p-3 text-right">Kredit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($neraca as $n)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-colors">
                        <td class="p-3 font-semibold">{{ $n['account']->kode_akun }} - {{ $n['account']->jenis_akun }}</td>
                        <td class="p-3 text-right font-bold">{{ number_format($n['debit'], 2) }}</td>
                        <td class="p-3 text-right font-bold">{{ number_format($n['kredit'], 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="bg-gray-100 dark:bg-gray-700 font-bold">
                        <td class="p-3">Total</td>
                        <td class="p-3 text-right">{{ number_format($totalDebit, 2) }}</td>
                        <td class="p-3 text-right">{{ number_format($totalKredit, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            @if ($isBalanced)
                <p class="text-green-600 font-bold">✔ Neraca saldo seimbang</p>
            @else
                <p class="text-red-600 font-bold">✖ Neraca saldo TIDAK seimbang</p>
            @endif
        </div>
    </div>

</div>
@endsection
