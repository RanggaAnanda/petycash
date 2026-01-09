@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto p-6 space-y-8">

        {{-- ================= HEADER ================= --}}
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800 dark:text-white tracking-wide uppercase">
                Buku Besar
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Detail transaksi per akun
            </p>
        </div>

        {{-- ================= MENU TAB ================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex divide-x divide-gray-200 dark:divide-gray-700 overflow-hidden rounded-xl">
                <a href="{{ route('laporan.pettycash.buku-besar') }}"
                    class="flex-1 text-center py-3 text-sm font-semibold transition
               {{ request()->is('laporan/pettycash/akuntansi/buku-besar') ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    Buku Besar
                </a>

                <a href="{{ route('laporan.pettycash.neraca') }}"
                    class="flex-1 text-center py-3 text-sm font-semibold transition
               {{ request()->is('laporan/pettycash/akuntansi/neraca-saldo') ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    Neraca Saldo
                </a>

                <a href="{{ route('laporan.pettycash.laba-rugi') }}"
                    class="flex-1 text-center py-3 text-sm font-semibold transition
               {{ request()->is('laporan/pettycash/akuntansi/laba-rugi') ? 'bg-blue-600 text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                    Laba Rugi
                </a>
            </div>
        </div>

        {{-- ================= FILTER ================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-6 space-y-6">
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200 uppercase">Filter Laporan</h3>

                <form action="{{ url()->current() }}" method="GET"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 items-end">
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

                    <div>
                        <x-input-label name="Tipe Transaksi" />
                        <x-dropdown name="tipe" :options="[
                            'all' => 'Semua',
                            'masuk' => 'Uang Masuk',
                            'keluar' => 'Uang Keluar',
                        ]" :selected="request('tipe')" />
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full h-[42px] rounded-lg bg-blue-600 text-white font-bold hover:bg-blue-700 transition">
                            Terapkan
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

        {{-- ================= DATA ================= --}}
        @foreach ($bukuBesar as $bb)
            <div
                class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 space-y-4">

                {{-- Header Akun --}}
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <h4 class="text-lg font-bold text-gray-700 dark:text-gray-200">
                        {{ $bb['account']->kode_akun }} — {{ $bb['account']->jenis_akun }}
                        <span class="text-sm text-gray-400 font-normal">
                            (Normal: {{ $bb['account']->normal_balance }})
                        </span>
                    </h4>

                    {{-- ================= FILTER TABLE ================= --}}
                    <div class="flex items-center gap-3">
                        <form id="tableFilterForm" method="GET" action="{{ url()->current() }}"
                            class="flex gap-3 items-center">

                            {{-- Simpan semua filter utama --}}
                            <input type="hidden" name="waktu" value="{{ request('waktu') }}">
                            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                            <input type="hidden" name="toko" value="{{ request('toko') }}">
                            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                            <input type="hidden" name="tipe" value="{{ request('tipe') }}">

                            {{-- Per Page --}}
                            <select name="per_page" onchange="document.getElementById('tableFilterForm').submit()"
                                class="border rounded-md px-2 py-1 text-sm">
                                @foreach ([10, 25, 50] as $size)
                                    <option value="{{ $size }}"
                                        {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-sm text-gray-500">entries</span>

                            {{-- Search --}}
                            <input type="text" name="search" placeholder="Cari transaksi..."
                                value="{{ request('search') }}" class="px-3 py-1.5 text-sm rounded-md border"
                                onkeydown="if(event.key === 'Enter'){document.getElementById('tableFilterForm').submit();}">
                        </form>
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <x-table>
                        <thead class="bg-gray-100 dark:bg-gray-700 text-sm">
                            <tr>
                                <th class="p-3 text-center">Tanggal</th>
                                <th class="p-3 text-center">No Bukti</th>
                                <th class="p-3 text-left">Keterangan</th>
                                <th class="p-3 text-right">Debit</th>
                                <th class="p-3 text-right">Kredit</th>
                                <th class="p-3 text-right">Saldo</th>
                            </tr>
                        </thead>

                        <tbody class="text-sm divide-y">
                            @php
                                $totalDebit = 0;
                                $totalKredit = 0;
                            @endphp

                            @foreach ($bb['rows'] as $row)
                                @php
                                    $totalDebit += $row['debit'];
                                    $totalKredit += $row['kredit'];
                                @endphp

                                <tr>
                                    <td class="p-3 text-center">{{ $row['tanggal'] }}</td>
                                    <td class="p-3 text-center">{{ $row['no_bukti'] }}</td>
                                    <td class="p-3">{{ $row['keterangan'] }}</td>
                                    <td class="p-3 text-right text-green-600 font-semibold">
                                        {{ number_format($row['debit'], 2) }}</td>
                                    <td class="p-3 text-right text-red-600 font-semibold">
                                        {{ number_format($row['kredit'], 2) }}</td>
                                    <td class="p-3 text-right font-bold">{{ number_format($row['saldo'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot class="bg-gray-50 dark:bg-gray-700 font-bold text-sm">
                            <tr>
                                <td colspan="3" class="p-3 text-right">TOTAL</td>
                                <td class="p-3 text-right text-green-700">{{ number_format($totalDebit, 2) }}</td>
                                <td class="p-3 text-right text-red-700">{{ number_format($totalKredit, 2) }}</td>
                                <td class="p-3 text-right">{{ number_format($totalDebit - $totalKredit, 2) }}</td>
                            </tr>
                        </tfoot>
                    </x-table>
                </div>

            </div>
        @endforeach

    </div>

    <script>
        function toggleCustomDate(val) {
            document.getElementById('customDateSection')
                .classList.toggle('hidden', val !== 'custom');
        }
    </script>
@endsection
