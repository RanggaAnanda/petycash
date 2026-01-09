@extends('layouts.app')

@section('title', 'Master Kategori')
@section('page-title', 'Master - Kategori')

@section('content')
    <div class="space-y-6">

        {{-- ================= ALERT SUCCESS ================= --}}
        @if (session('success'))
            <div class="p-3 bg-green-100 text-green-700 rounded-lg border border-green-200 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- ================= ALERT ERROR ================= --}}
        @if (session('error'))
            <div class="p-3 bg-red-100 text-red-700 rounded-lg border border-red-200 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- ================= FORM KATEGORI ================= --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-lg mb-4 text-gray-700 dark:text-gray-200">
                {{ isset($editKategori) ? 'Edit Kategori Utama' : 'Form Kategori Utama Baru' }}
            </h3>

            <form
                action="{{ isset($editKategori) ? route('master.kategori.update', $editKategori->id) : route('master.kategori.store') }}"
                method="POST" class="space-y-4">
                @csrf
                @if (isset($editKategori))
                    @method('PATCH')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- STATUS ALIRAN KAS --}}
                    <div>
                        <x-input-label name="Status Aliran Kas" />
                        <x-dropdown name="status" id="statusKategori" :options="['masuk' => 'Uang Masuk', 'keluar' => 'Uang Keluar']" :value="old('status', $editKategori->status ?? '')" />
                    </div>

                    {{-- KODE INPUT --}}
                    <div>
                        <x-input-label name="Kode Kategori (2 Digit)" />
                        <x-input id="kode_input" name="kode_input" placeholder="01" maxlength="2"
                            value="{{ old('kode_input', isset($editKategori) ? substr($editKategori->kode_kategori, -2) : '') }}"
                            required />
                    </div>

                    {{-- NAMA KATEGORI --}}
                    <div>
                        <x-input-label name="Nama Kategori" />
                        <x-input name="name" placeholder="Misal: Biaya Listrik / Penjualan Produk"
                            value="{{ old('name', $editKategori->name ?? '') }}" required />
                    </div>

                    {{-- HAS CHILD --}}
                    <div>
                        <x-input-label name="Child" />
                        <x-dropdown name="has_child" id="hasChild" :options="['ya' => 'Ya', 'tidak' => 'Tidak']" :value="old('has_child', $editKategori->has_child ?? 'tidak')" />
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow transition">
                        {{ isset($editKategori) ? 'Update' : 'Simpan' }}
                    </button>
                    @if (isset($editKategori))
                        <a href="{{ route('master.kategori.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition">Batal</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ================= LIST KATEGORI ================= --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">

            <h3 class="font-semibold mb-4 text-lg text-gray-700 dark:text-gray-200">
                List Kategori Utama
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-base text-gray-700 dark:text-gray-200">
                    <thead class="bg-gray-100 dark:bg-gray-700 border-b">
                        <tr class="font-black uppercase text-gray-500 tracking-widest">
                            <th class="w-12 px-5 py-3 text-center">No</th>
                            <th class="px-5 py-3 text-center">Kode</th>
                            <th class="px-5 py-3 text-left">Nama Kategori</th>
                            <th class="px-5 py-3 text-left">Sub Kategori</th>
                            <th class="w-32 px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($kategoris as $idx => $k)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                                <td class="p-3 text-center font-medium">{{ $kategoris->firstItem() + $idx }}</td>
                                <td class="p-3 font-mono text-center">{{ $k->kode_kategori }}</td>
                                <td class="p-3">{{ $k->name }}</td>
                                <td class="p-3">
                                    @if ($k->has_child == 'ya')
                                        <a href="{{ route('master.subkategori.index', $k->id) }}"
                                            class="text-green-600 hover:text-green-800 font-bold flex items-center gap-1 group">
                                            <span>Sub Kategori</span>
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4 group-hover:translate-x-1 transition-transform"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="p-3 text-center">
                                    <div class="flex justify-center items-center space-x-3">
                                        <a href="{{ route('master.kategori.edit', $k->id) }}"
                                            class="text-blue-600 hover:text-blue-800 font-semibold">Edit</a>
                                        <form action="{{ route('master.kategori.destroy', $k->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:text-red-800 font-semibold">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500">Belum ada data kategori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $kategoris->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusDropdown = document.getElementById('statusKategori');
            const kodeInput = document.getElementById('kode_input');
            const isEditMode = {{ isset($editKategori) ? 'true' : 'false' }};

            statusDropdown.addEventListener('change', function() {
                if (isEditMode) return;

                const status = this.value;
                if (status) {
                    fetch(`/master/kategori/get-next-code/${status}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.next_code) kodeInput.value = data.next_code;
                        })
                        .catch(error => console.error('Error fetching code:', error));
                } else {
                    kodeInput.value = '';
                }
            });

            if (!isEditMode && statusDropdown.value) {
                statusDropdown.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection
