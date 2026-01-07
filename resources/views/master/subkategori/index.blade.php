@extends('layouts.app')

@section('title', 'Master Sub Kategori')
@section('page-title', 'Master - Sub Kategori')

@section('content')
    <div class="space-y-6">
        @if (session('success'))
            <div class="p-3 bg-green-100 text-green-700 rounded-lg border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <h3 class="font-semibold mb-4 text-gray-700 dark:text-gray-200 text-lg">Form Sub Kategori</h3>

            <form action="{{ route('master.subkategori.store', $kategori->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label name="Kategori" />
                        <x-input value="{{ $kategori->kode_kategori }} - {{ $kategori->name }}" readonly
                            class="bg-gray-100" />
                    </div>

                    <div>
                        <x-input-label name="Kode Sub (Auto-Generate)" />
                        {{-- Nilai $nextCode dari Controller --}}
                        <x-input name="kode" value="{{ $nextCode }}" readonly
                            class="bg-gray-100 font-bold text-blue-600" />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label name="Nama Sub Kategori" />
                        <x-input name="nama" placeholder="Masukan nama sub kategori..." required />
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                        Simpan Sub Kategori
                    </button>
                    <a href="{{ route('master.kategori.index') }}"
                        class="ml-2 text-gray-500 hover:underline text-sm">Kembali ke List Utama</a>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <h3 class="font-semibold mb-4 text-gray-700 dark:text-gray-200">Sub Kategori dari: {{ $kategori->name }}</h3>

            <x-table>
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700">
                        <x-th class="w-14 text-center">No</x-th>
                        <x-th class="w-40">KODE</x-th>
                        <x-th class="text-left">SUB KATEGORI</x-th>
                        <x-th class="w-24 text-center">AKSI</x-th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subkategoris as $idx => $sub)
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-900 transition text-lg">
                            <td class="p-3 text-center text-gray-500">{{ $idx + 1 }}</td>
                            <td class="p-3 font-mono text-gray-700 dark:text-gray-200">
                                {{-- Output: 40100, 40101, dll --}}
                                {{ $kategori->kode_kategori }}{{ $sub->kode_sub }}
                            </td>
                            <td class="p-3 text-gray-700 dark:text-gray-200">{{ $sub->name }}</td>
                            <td class="p-3 text-center">
                                <form action="{{ route('master.subkategori.destroy', $sub->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-500 hover:text-red-700 font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-400">Belum ada data sub kategori untuk
                                kategori ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table>
        </div>
    </div>
@endsection
