@extends('layouts.app')

@section('title', 'Master Toko')
@section('page-title', 'Master - Toko')

@section('content')
    <div class="space-y-6">

        <!-- FORM TOKO -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <h3 class="font-semibold mb-4">{{ isset($editStore) ? 'Edit Toko' : 'Form Toko' }}</h3>

            @if (session('success'))
                <div class="mb-3 text-green-600 text-sm">{{ session('success') }}</div>
            @endif

            <form action="{{ isset($editStore) ? route('master.stores.update', $editStore) : route('master.stores.store') }}"
                method="POST">
                @csrf
                @if (isset($editStore))
                    @method('PATCH')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label name="Code" />
                        <x-input name="code" value="{{ old('code', $editStore->code ?? '') }}" class="text-sm" required />
                    </div>
                    <div>
                        <x-input-label name="Nama Toko" />
                        <x-input name="name" value="{{ old('name', $editStore->name ?? '') }}" class="text-sm"
                            required />
                    </div>
                </div>

                <div class="mt-4">
                    <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm">
                        {{ isset($editStore) ? 'Update' : 'Simpan' }}
                    </button>
                    @if (isset($editStore))
                        <a href="{{ route('master.stores.index') }}"
                            class="ml-2 px-3 py-2 bg-gray-200 rounded text-sm">Batal</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- LIST TOKO -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold">List Toko</h3>

                <div class="flex items-center gap-3 mt-4">
                    <select id="perPage" class="border rounded px-2 py-1 text-sm">
                        @foreach ([10, 25, 50] as $size)
                            <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-sm text-gray-500">entries per page</span>

                    <input id="searchToko" type="text" placeholder="Cari code atau nama..."
                        class="ml-4 px-3 py-2 rounded border text-sm" value="{{ request('search') }}" />
                </div>
            </div>

            <div class="mt-2 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="w-12 text-center p-3 border-b">No</th>
                            <th class="text-center p-3 border-b">CODE</th>
                            <th class="text-center p-3 border-b">NAMA TOKO</th>
                            <th class="w-20 text-center p-3 border-b">EDIT</th>
                            <th class="w-20 text-center p-3 border-b">DELETE</th>
                        </tr>
                    </thead>
                    <tbody id="tableToko">
                        @forelse($stores as $i => $store)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <td class="p-3 text-center text-sm">{{ $stores->firstItem() + $i }}</td>
                                <td class="p-3 text-center text-sm">{{ $store->code }}</td>
                                <td class="p-3 text-center text-sm">{{ $store->name }}</td>
                                <td class="p-3 text-center text-sm">
                                    <a href="{{ route('master.stores.index', ['edit' => $store->id]) }}"
                                        class="text-blue-600 hover:underline">Edit</a>
                                </td>
                                <td class="p-3 text-center text-sm">
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
                                <td colspan="5" class="p-4 text-center text-gray-500 text-sm">Data belum ada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4" id="paginationLinks">
                {{ $stores->links() }}
            </div>
        </div>
    </div>

    <!-- Live Search AJAX -->
    <script>
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
    </script>
@endsection
