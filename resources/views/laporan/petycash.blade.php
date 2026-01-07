@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-6">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white uppercase">Daftar Petty Cash</h2>
            {{-- <div class="flex gap-2">
                <a href="{{ route('forms.uang-masuk.create') }}"
                    class="px-6 py-3 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 transition shadow-lg">
                    + UANG MASUK
                </a>
                <a href="{{ route('forms.uang-keluar.create') }}"
                    class="px-6 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition shadow-lg">
                    - UANG KELUAR
                </a>
            </div> --}}
        </div>

        {{-- Filter Panel --}}

        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">

            <form action="{{ route('laporan.filter') }}" method="POST">

                @csrf



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

                    {{-- Filter Tipe --}}
                    <div>
                        <x-input-label name="Tipe Transaksi" />
                        <x-dropdown name="tipe" :options="[
                            'all' => 'Semua (Masuk & Keluar)',
                            'masuk' => 'Hanya Uang Masuk',
                            'keluar' => 'Hanya Uang Keluar',
                        ]" :selected="$filter['tipe']" />
                    </div>



                    {{-- APPLY --}}

                    <div class="flex items-end">

                        <button type="submit"
                            class="w-full py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition">

                            APPLY FILTER

                        </button>

                    </div>



                </div>



                {{-- CUSTOM DATE --}}

                <div id="customDateSection"
                    class="{{ $filter['waktu'] === 'custom' ? '' : 'hidden' }}

                   grid grid-cols-2 gap-4 mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-xl border-2 border-dashed border-gray-200">



                    <div>

                        <label class="block text-[10px] font-bold text-gray-400 uppercase">

                            Dari Tanggal

                        </label>

                        <input type="date" name="start_date" value="{{ $filter['start_date'] }}"
                            class="w-full rounded-lg border-gray-300 dark:bg-gray-700">

                    </div>



                    <div>

                        <label class="block text-[10px] font-bold text-gray-400 uppercase">

                            Sampai Tanggal

                        </label>

                        <input type="date" name="end_date" value="{{ $filter['end_date'] }}"
                            class="w-full rounded-lg border-gray-300 dark:bg-gray-700">

                    </div>

                </div>



            </form>

        </div>

        {{-- Tabel Data --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <tr class="text-xs font-black text-gray-500 uppercase tracking-widest">
                            <th class="p-5">Tanggal</th>
                            <th class="p-5">Toko</th>
                            <th class="p-5">Kategori</th>
                            <th class="p-5">Sub Kategori</th>
                            <th class="p-5 text-right">Masuk</th>
                            <th class="p-5 text-right">Keluar</th>
                            <th class="p-5 text-right">Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($transaksi as $item)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-colors">
                                <td class="p-5 font-semibold">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                </td>
                                <td class="p-5">
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-sm">
                                        {{ $item->user->store->name ?? 'Pusat' }}
                                    </span>
                                </td>
                                {{-- Ganti dari $item->kategori->name menjadi: --}}
                                <td class="p-5">{{ $item->kategori_name }}</td>

                                {{-- Dan sub kategori: --}}
                                <td class="p-5 ">{{ $item->sub_name }}</td>

                                {{-- Kolom Masuk --}}
                                <td class="p-5 text-right font-bold text-green-600">
                                    {{ $item->tipe == 'masuk' ? number_format($item->nominal, 0, ',', '.') : '-' }}
                                </td>

                                {{-- Kolom Keluar --}}
                                <td class="p-5 text-right font-bold text-red-600">
                                    {{ $item->tipe == 'keluar' ? number_format($item->nominal, 0, ',', '.') : '-' }}
                                </td>

                                {{-- Running Saldo --}}
                                <td class="p-5 text-right font-black text-gray-800 dark:text-gray-200">
                                    {{ number_format($item->saldo_berjalan, 0, ',', '.') }}
                                </td>
                            </tr>

                            {{-- Detail Row --}}
                            <tr id="row-{{ $item->tipe }}-{{ $item->id }}"
                                class="hidden bg-gray-50 dark:bg-gray-900/30">
                                <td colspan="7" class="p-5 italic text-gray-500">
                                    Keterangan: {{ $item->keterangan ?? 'Tidak ada catatan.' }} | Diinput oleh:
                                    {{ $item->user->name ?? 'System' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-10 text-center text-gray-400">Belum ada transaksi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
