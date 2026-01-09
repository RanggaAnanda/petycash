@extends('layouts.app')

@section('title', 'Master Toko')
@section('page-title', 'Master - Toko')

@section('content')
    <div class="space-y-6">

        {{-- ================= FORM TOKO ================= --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-lg mb-4 text-gray-700 dark:text-gray-200">
                {{ isset($editStore) ? 'Edit Toko' : 'Form Toko' }}
            </h3>

            @if (session('success'))
                <div class="mb-4 text-green-600 text-base">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ isset($editStore) ? route('master.stores.update', $editStore) : route('master.stores.store') }}"
                method="POST" class="space-y-4">
                @csrf
                @isset($editStore)
                    @method('PATCH')
                @endisset

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label name="Code" />
                        <x-input name="code" value="{{ old('code', $editStore->code ?? '') }}" class="text-base"
                            required />
                    </div>

                    <div>
                        <x-input-label name="Nama Toko" />
                        <x-input name="name" value="{{ old('name', $editStore->name ?? '') }}" class="text-base"
                            required />
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow transition">
                        {{ isset($editStore) ? 'Update' : 'Simpan' }}
                    </button>

                    @isset($editStore)
                        <a href="{{ route('master.stores.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition">Batal</a>
                    @endisset
                </div>
            </form>
        </div>

        {{-- ================= LIST TOKO ================= --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">

            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 gap-3">
                <h3 class="font-semibold text-lg text-gray-700 dark:text-gray-200">
                    List Toko
                </h3>

                <div class="flex items-center gap-3">
                    <select id="perPage" class="border rounded px-2 py-1 text-sm">
                        @foreach ([10, 25, 50] as $size)
                            <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                                {{ $size }}</option>
                        @endforeach
                    </select>
                    <span class="text-sm text-gray-500">entries</span>
                    <input id="searchToko" type="text" placeholder="Cari code atau nama..."
                        class="px-3 py-2 rounded border text-base" value="{{ request('search') }}">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-base text-gray-700 dark:text-gray-200">
                    <thead class="bg-gray-100 dark:bg-gray-700 border-b">
                        <tr class="font-black uppercase text-gray-500 tracking-widest">
                            <th class="w-12 px-5 py-3 text-center">No</th>
                            <th class="px-5 py-3 text-center">CODE</th>
                            <th class="px-5 py-3 text-center">NAMA TOKO</th>
                            <th class="w-20 px-5 py-3 text-center">EDIT</th>
                            <th class="w-20 px-5 py-3 text-center">DELETE</th>
                        </tr>
                    </thead>

                    <tbody id="tableToko" class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($stores as $i => $store)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                                <td class="px-3 py-2 text-center font-medium">{{ $stores->firstItem() + $i }}</td>
                                <td class="px-3 py-2 text-center font-mono">{{ $store->code }}</td>
                                <td class="px-3 py-2 text-center">{{ $store->name }}</td>
                                <td class="px-3 py-2 text-center">
                                    <a href="{{ route('master.stores.index', ['edit' => $store->id]) }}"
                                        class="text-blue-600 hover:underline">Edit</a>
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <form method="POST" action="{{ route('master.stores.destroy', $store) }}"
                                        onsubmit="return confirm('Hapus data?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500 text-base">Data belum ada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-4">
                {{ $stores->links() }}
            </div>
        </div>
    </div>

    {{-- ================= LIVE SEARCH ================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchToko');
            const perPageSelect = document.getElementById('perPage');

            function fetchStores() {
                const query = searchInput.value;
                const perPage = perPageSelect.value;

                fetch(`{{ route('master.stores.index') }}?search=${query}&per_page=${perPage}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById('tableToko').innerHTML = html;
                    });
            }

            searchInput.addEventListener('input', fetchStores);
            perPageSelect.addEventListener('change', fetchStores);
        });
    </script>
@endsection
