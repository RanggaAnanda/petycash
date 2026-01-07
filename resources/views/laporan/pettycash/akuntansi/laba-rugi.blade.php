@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-6">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white uppercase">Laporan Laba Rugi</h2>
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

        {{-- Konten Laporan --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 p-6">

            <h3 class="text-lg font-bold mb-2">Pendapatan</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <tr class="text-xs font-black text-gray-500 uppercase tracking-widest">
                            <th class="p-3">Akun</th>
                            <th class="p-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($pendapatan_totals as $p)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-colors">
                                <td class="p-3 font-semibold">{{ $p['akun']->kode_akun }} - {{ $p['akun']->jenis_akun }}
                                </td>
                                <td class="p-3 text-right font-bold text-green-600">{{ number_format($p['total'], 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-100 dark:bg-gray-700 font-bold">
                            <td class="p-3">Total Pendapatan</td>
                            <td class="p-3 text-right">{{ number_format($totalPendapatan, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h3 class="text-lg font-bold mt-6 mb-2">Beban</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <tr class="text-xs font-black text-gray-500 uppercase tracking-widest">
                            <th class="p-3">Akun</th>
                            <th class="p-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($beban_totals as $b)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-colors">
                                <td class="p-3 font-semibold">{{ $b['akun']->kode_akun }} - {{ $b['akun']->jenis_akun }}
                                </td>
                                <td class="p-3 text-right font-bold text-red-600">{{ number_format($b['total'], 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="bg-gray-100 dark:bg-gray-700 font-bold">
                            <td class="p-3">Total Beban</td>
                            <td class="p-3 text-right">{{ number_format($totalBeban, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h3 class="text-xl font-black mt-6">Laba Bersih: <span
                    class="text-blue-600">{{ number_format($laba_bersih, 2) }}</span></h3>

        </div>
    </div>
@endsection
