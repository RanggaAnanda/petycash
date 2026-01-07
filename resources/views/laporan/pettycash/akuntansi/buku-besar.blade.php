@extends('layouts.app')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">

        <form action="{{ url()->current() }}" method="GET">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">

                {{-- RENTANG WAKTU --}}
                <div>
                    <x-input-label name="Rentang Waktu" />
                    <x-dropdown name="waktu" id="waktu" :options="[
                        'all' => 'Semua Waktu',
                        'hari_ini' => 'Hari Ini',
                        'kemarin' => 'Kemarin',
                        'minggu_lalu' => '1 Minggu Terakhir',
                        'bulan_ini' => 'Bulan Ini',
                        'bulan_lalu' => '1 Bulan Terakhir',
                        'custom' => 'Custom Tanggal',
                    ]" :selected="request('waktu')"
                        onchange="handleWaktuChange(this.value)" />
                </div>

                {{-- TOKO --}}
                <div>
                    <x-input-label name="Toko / Cabang" />
                    <x-dropdown name="toko" :options="['all' => 'Semua Toko'] + $tokos->pluck('name', 'id')->toArray()" :selected="request('toko')" />
                </div>

                {{-- KATEGORI --}}
                <div>
                    <x-input-label name="Kategori" />
                    <x-dropdown name="kategori" :options="['all' => 'Semua Kategori'] + $categories->pluck('name', 'id')->toArray()" :selected="request('kategori')" />
                </div>

                {{-- TIPE TRANSAKSI --}}
                <div>
                    <x-input-label name="Tipe Transaksi" />
                    <x-dropdown name="tipe" :options="[
                        'all' => 'Semua',
                        'masuk' => 'Uang Masuk',
                        'keluar' => 'Uang Keluar',
                    ]" :selected="request('tipe')" />
                </div>

                {{-- APPLY --}}
                <div class="flex items-end col-span-full md:col-span-1">
                    <button type="submit"
                        class="w-full py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition">
                        APPLY FILTER
                    </button>
                </div>

            </div>

            {{-- CUSTOM DATE --}}
            <div id="customDateSection"
                class="{{ request('waktu') == 'custom' ? '' : 'hidden' }} grid grid-cols-2 gap-4 mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border-2 border-dashed border-gray-200">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full rounded-lg border-gray-300 dark:bg-gray-700">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full rounded-lg border-gray-300 dark:bg-gray-700">
                </div>
            </div>
        </form>
    </div>

    <script>
        function handleWaktuChange(val) {
            document.getElementById('customDateSection').classList.toggle('hidden', val !== 'custom');
        }
    </script>

    <div class="p-6 space-y-6">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white uppercase">Buku Besar</h2>
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

        @foreach ($bukuBesar as $bb)
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h4 class="text-lg font-semibold mb-4">
                    Akun: {{ $bb['account']->kode_akun }} - {{ $bb['account']->jenis_akun }}
                    (Normal: {{ $bb['account']->normal_balance }})
                </h4>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                            <tr class="text-xs font-black text-gray-500 uppercase tracking-widest">
                                <th class="p-3">Tanggal</th>
                                <th class="p-3">No Bukti</th>
                                <th class="p-3">Keterangan</th>
                                <th class="p-3 text-right">Debit</th>
                                <th class="p-3 text-right">Kredit</th>
                                <th class="p-3 text-right">Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($bb['rows'] as $row)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-colors">
                                    <td class="p-3">{{ $row['tanggal'] }}</td>
                                    <td class="p-3">{{ $row['no_bukti'] }}</td>
                                    <td class="p-3">{{ $row['keterangan'] }}</td>
                                    <td class="p-3 text-right font-bold text-green-600">
                                        {{ number_format($row['debit'], 2) }}</td>
                                    <td class="p-3 text-right font-bold text-red-600">
                                        {{ number_format($row['kredit'], 2) }}</td>
                                    <td class="p-3 text-right font-black text-gray-800 dark:text-gray-200">
                                        {{ number_format($row['saldo'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

    </div>
@endsection
