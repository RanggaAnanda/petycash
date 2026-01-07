@extends('layouts.app')

@section('title', 'Daftar Omset')
@section('page-title', 'Daftar Omset')

@section('content')
    <div class="p-6 space-y-6">

        {{-- Header --}}
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold uppercase">Daftar Omset</h2>
            {{-- <a href="{{ route('forms.omset.create') }}"
                class="px-6 py-3 bg-green-600 text-white rounded-xl font-bold hover:bg-green-700 shadow">
                + TAMBAH OMSET
            </a> --}}
        </div>

        {{-- Filter Panel --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border">

            <form action="{{ route('daftar.omset.index') }}" method="GET">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">

                    {{-- Rentang Waktu --}}
                    <div>
                        <x-input-label name="Rentang Waktu" />
                        <x-dropdown name="waktu" :options="[
                            'hari_ini' => 'Hari Ini',
                            'minggu_ini' => 'Minggu Ini',
                            'bulan_ini' => 'Bulan Ini',
                            'custom' => 'Custom Tanggal',
                        ]" :selected="request('waktu')" />
                    </div>

                    {{-- Toko --}}
                    <div>
                        <x-input-label name="Toko" />
                        <x-dropdown name="toko" :options="['all' => 'Semua Toko'] + $tokos->pluck('name', 'id')->toArray()" :selected="request('toko')" />
                    </div>

                    {{-- Apply --}}
                    <div class="flex items-end">
                        <button class="w-full py-2.5 bg-blue-600 text-white rounded-xl font-bold">
                            APPLY FILTER
                        </button>
                    </div>

                </div>
            </form>

        </div>

        {{-- Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden border">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr class="text-xs font-black uppercase">
                        <th class="p-5">Tanggal</th>
                        <th class="p-5">Toko</th>
                        <th class="p-5 text-right">Omset</th>
                        <th class="p-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($items as $item)
                        <tr>
                            <td class="p-5">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                            </td>
                            <td class="p-5">
                                {{ $item->store->name }}
                            </td>
                            <td class="p-5 text-right font-bold text-green-600">
                                {{ number_format($item->nominal, 0, ',', '.') }}
                            </td>
                            <td class="p-5 text-center">
                                <a href="{{ route('forms.omset.edit', $item->id) }}"
                                    class="px-3 py-1 bg-blue-500 text-white rounded text-xs font-bold">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-400">
                                Belum ada data omset
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
