@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-6">

        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white uppercase">
                Daftar Petty Cash
            </h2>
        </div>

        {{-- ================= FILTER ================= --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow border border-gray-200 dark:border-gray-700">

            <form action="{{ route('laporan.filter') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 items-end">

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
                        ]" :selected="$filter['waktu']"
                            onchange="handleWaktuChange(this.value)" />
                    </div>

                    {{-- TOKO --}}
                    <div>
                        <x-input-label name="Toko / Cabang" />
                        <x-dropdown name="toko" :options="['all' => 'Semua Toko'] + $tokos->pluck('name', 'id')->toArray()" :selected="$filter['toko']" />
                    </div>

                    {{-- KATEGORI --}}
                    <div>
                        <x-input-label name="Kategori" />
                        <x-dropdown name="kategori" :options="['all' => 'Semua Kategori'] + $categories->pluck('name', 'id')->toArray()" :selected="$filter['kategori']" />
                    </div>

                    {{-- TIPE --}}
                    <div>
                        <x-input-label name="Tipe Transaksi" />
                        <x-dropdown name="tipe" :options="[
                            'all' => 'Semua (Masuk & Keluar)',
                            'masuk' => 'Hanya Uang Masuk',
                            'keluar' => 'Hanya Uang Keluar',
                        ]" :selected="$filter['tipe']" />
                    </div>

                    {{-- BUTTON --}}
                    <div>
                        <button type="submit"
                            class="w-full py-2.5 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                            Terapkan Filter
                        </button>
                    </div>

                </div>

                {{-- CUSTOM DATE --}}
                <div id="customDateSection"
                    class="{{ $filter['waktu'] === 'custom' ? '' : 'hidden' }} grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-dashed">

                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">
                            Dari Tanggal
                        </label>
                        <input type="date" name="start_date" value="{{ $filter['start_date'] }}"
                            class="w-full mt-1 rounded-lg border-gray-300 dark:bg-gray-700">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">
                            Sampai Tanggal
                        </label>
                        <input type="date" name="end_date" value="{{ $filter['end_date'] }}"
                            class="w-full mt-1 rounded-lg border-gray-300 dark:bg-gray-700">
                    </div>

                </div>
            </form>
        </div>

        {{-- ================= TABLE ================= --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-xs font-bold uppercase tracking-wider">
                        <tr>
                            <th class="p-4">Tanggal</th>
                            <th class="p-4">Toko</th>
                            <th class="p-4">Kategori</th>
                            <th class="p-4">Sub Kategori</th>
                            <th class="p-4 text-right">Masuk</th>
                            <th class="p-4 text-right">Keluar</th>
                            <th class="p-4 text-right">Saldo Akhir</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($transaksi as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40 transition">
                                <td class="p-4 font-semibold">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-xs">
                                        {{ $item->user->store->name ?? 'Pusat' }}
                                    </span>
                                </td>
                                <td class="p-4">{{ $item->kategori_name }}</td>
                                <td class="p-4">{{ $item->sub_name }}</td>

                                <td class="p-4 text-right font-semibold text-green-600">
                                    {{ $item->tipe === 'masuk' ? number_format($item->nominal, 0, ',', '.') : '-' }}
                                </td>

                                <td class="p-4 text-right font-semibold text-red-600">
                                    {{ $item->tipe === 'keluar' ? number_format($item->nominal, 0, ',', '.') : '-' }}
                                </td>

                                <td class="p-4 text-right font-bold text-gray-800 dark:text-gray-200">
                                    {{ number_format($item->saldo_berjalan, 0, ',', '.') }}
                                </td>
                            </tr>

                            {{-- DETAIL --}}
                            <tr class="hidden bg-gray-50 dark:bg-gray-900/30">
                                <td colspan="7" class="p-4 italic text-gray-500">
                                    Keterangan: {{ $item->keterangan ?? 'Tidak ada catatan.' }} |
                                    Diinput oleh: {{ $item->user->name ?? 'System' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-10 text-center text-gray-400">
                                    Belum ada transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        function handleWaktuChange(val) {
            document.getElementById('customDateSection')
                .classList.toggle('hidden', val !== 'custom');
        }
    </script>
@endsection
