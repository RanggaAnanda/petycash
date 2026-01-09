@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto p-6 space-y-8">

        {{-- ================= HEADER ================= --}}
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800 dark:text-white tracking-wide uppercase">
                Neraca Saldo
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Laporan Neraca Saldo Petty Cash
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
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-end">

                    {{-- Rentang Waktu --}}
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
                            onchange="toggleCustomDate(this.value)" />
                    </div>

                    {{-- Toko --}}
                    <div>
                        <x-input-label name="Toko / Cabang" />
                        <x-dropdown name="toko" :options="['all' => 'Semua Toko'] + $tokos->pluck('name', 'id')->toArray()" :selected="request('toko')" />
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <x-input-label name="Kategori" />
                        <x-dropdown name="kategori" :options="['all' => 'Semua Kategori'] + $categories->pluck('name', 'id')->toArray()" :selected="request('kategori')" />
                    </div>

                    {{-- Apply --}}
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full py-2.5 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                            Terapkan Filter
                        </button>
                    </div>
                </form>

                {{-- Custom Date --}}
                <div id="customDateSection"
                    class="{{ request('waktu') === 'custom' ? '' : 'hidden' }}
                        grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-dashed">

                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full rounded-lg border-gray-300 dark:bg-gray-700">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full rounded-lg border-gray-300 dark:bg-gray-700">
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= TABEL NERACA ================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
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
                                <td class="p-3 font-semibold">
                                    {{ $n['account']->kode_akun }} - {{ $n['account']->jenis_akun }}
                                </td>
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

            {{-- Status Neraca --}}
            <div class="mt-4">
                @if ($isBalanced)
                    <p class="text-green-600 font-bold">✔ Neraca saldo seimbang</p>
                @else
                    <p class="text-red-600 font-bold">✖ Neraca saldo TIDAK seimbang</p>
                @endif
            </div>
        </div>

    </div>

    <script>
        function toggleCustomDate(val) {
            document.getElementById('customDateSection').classList.toggle('hidden', val !== 'custom');
        }
    </script>
@endsection
