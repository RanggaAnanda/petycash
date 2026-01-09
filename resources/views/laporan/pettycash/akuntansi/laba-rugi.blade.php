@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto p-6 space-y-8">

        {{-- ================= HEADER ================= --}}
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800 dark:text-white tracking-wide uppercase">
                Laporan Laba Rugi
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Ringkasan pendapatan dan beban berdasarkan periode
            </p>
        </div>

        {{-- ================= MENU TAB ================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex divide-x divide-gray-200 dark:divide-gray-700 overflow-hidden rounded-xl">

                <a href="{{ route('laporan.pettycash.buku-besar') }}"
                    class="flex-1 text-center py-3 text-sm font-semibold transition
               {{ request()->is('laporan/pettycash/akuntansi/buku-besar')
                   ? 'bg-blue-600 text-white'
                   : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    Buku Besar
                </a>

                <a href="{{ route('laporan.pettycash.neraca') }}"
                    class="flex-1 text-center py-3 text-sm font-semibold transition
               {{ request()->is('laporan/pettycash/akuntansi/neraca-saldo')
                   ? 'bg-blue-600 text-white'
                   : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    Neraca Saldo
                </a>

                <a href="{{ route('laporan.pettycash.laba-rugi') }}"
                    class="flex-1 text-center py-3 text-sm font-semibold transition
               {{ request()->is('laporan/pettycash/akuntansi/laba-rugi')
                   ? 'bg-blue-600 text-white'
                   : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    Laba Rugi
                </a>

            </div>
        </div>

        {{-- ================= FILTER ================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-6 space-y-6">

                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200 uppercase">
                    Filter Laporan
                </h3>

                <form action="{{ url()->current() }}" method="GET"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                    <div>
                        <x-input-label name="Rentang Waktu" />
                        <x-dropdown name="waktu" id="waktu" :options="[
                            'all' => 'Semua Waktu',
                            'hari_ini' => 'Hari Ini',
                            'kemarin' => 'Kemarin',
                            'minggu_lalu' => '7 Hari Terakhir',
                            'bulan_ini' => 'Bulan Ini',
                            'bulan_lalu' => 'Bulan Lalu',
                            'custom' => 'Custom Tanggal',
                        ]" :selected="request('waktu')"
                            onchange="toggleCustomDate(this.value)" />
                    </div>

                    <div>
                        <x-input-label name="Toko / Cabang" />
                        <x-dropdown name="toko" :options="['all' => 'Semua Toko'] + $tokos->pluck('name', 'id')->toArray()" :selected="request('toko')" />
                    </div>

                    <div>
                        <x-input-label name="Kategori" />
                        <x-dropdown name="kategori" :options="['all' => 'Semua Kategori'] + $categories->pluck('name', 'id')->toArray()" :selected="request('kategori')" />
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full h-[42px] rounded-lg bg-blue-600 text-white font-bold
                                   hover:bg-blue-700 transition">
                            Terapkan Filter
                        </button>
                    </div>
                </form>

                {{-- Custom Date --}}
                <div id="customDateSection"
                    class="{{ request('waktu') === 'custom' ? '' : 'hidden' }}
                        grid grid-cols-1 sm:grid-cols-2 gap-4
                        p-4 rounded-lg bg-gray-50 dark:bg-gray-900 border border-dashed">

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">
                            Dari Tanggal
                        </label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full rounded-lg border-gray-300 dark:bg-gray-700">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">
                            Sampai Tanggal
                        </label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full rounded-lg border-gray-300 dark:bg-gray-700">
                    </div>
                </div>

            </div>
        </div>

        {{-- ================= KONTEN ================= --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 space-y-8">

            {{-- Pendapatan --}}
            <div>
                <h3 class="text-base font-bold text-gray-700 dark:text-gray-200 mb-3">
                    Pendapatan
                </h3>

                <table class="w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr class="text-xs font-bold uppercase text-gray-500">
                            <th class="p-3 text-left">Akun</th>
                            <th class="p-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($pendapatan_totals as $p)
                            <tr>
                                <td class="p-3">
                                    {{ $p['akun']->kode_akun }} — {{ $p['akun']->jenis_akun }}
                                </td>
                                <td class="p-3 text-right font-semibold text-green-600">
                                    {{ number_format($p['total'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr class="font-bold bg-gray-50 dark:bg-gray-700">
                            <td class="p-3">Total Pendapatan</td>
                            <td class="p-3 text-right">
                                {{ number_format($totalPendapatan, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Beban --}}
            <div>
                <h3 class="text-base font-bold text-gray-700 dark:text-gray-200 mb-3">
                    Beban
                </h3>

                <table class="w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr class="text-xs font-bold uppercase text-gray-500">
                            <th class="p-3 text-left">Akun</th>
                            <th class="p-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($beban_totals as $b)
                            <tr>
                                <td class="p-3">
                                    {{ $b['akun']->kode_akun }} — {{ $b['akun']->jenis_akun }}
                                </td>
                                <td class="p-3 text-right font-semibold text-red-600">
                                    {{ number_format($b['total'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr class="font-bold bg-gray-50 dark:bg-gray-700">
                            <td class="p-3">Total Beban</td>
                            <td class="p-3 text-right">
                                {{ number_format($totalBeban, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Laba Bersih --}}
            <div class="flex justify-end pt-4 border-t">
                <div class="text-lg font-extrabold">
                    Laba Bersih :
                    <span class="text-blue-600 ml-2">
                        {{ number_format($laba_bersih, 2) }}
                    </span>
                </div>
            </div>

        </div>
    </div>

    {{-- Script --}}
    <script>
        function toggleCustomDate(val) {
            document.getElementById('customDateSection')
                .classList.toggle('hidden', val !== 'custom');
        }
    </script>
@endsection
