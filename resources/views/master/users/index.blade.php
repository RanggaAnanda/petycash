@extends('layouts.app')

@section('title', 'Master User')
@section('page-title', 'Master - User')

@section('content')
    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="bg-white p-4 rounded-lg shadow border flex justify-between items-center">
            <h3 class="font-semibold">LIST USERS</h3>
            <a href="{{ route('master.users.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">
                Add Users
            </a>
        </div>

        {{-- SATU KONTEN (SEARCH + TABLE + PAGINATION) --}}
        <div id="userContent" class="bg-white p-4 rounded-lg shadow border">

            {{-- FILTER --}}
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center gap-3">
                    <select id="perPage" class="border rounded px-2 py-1 text-sm">
                        @foreach ([10, 25, 50] as $size)
                            <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-sm text-gray-500">entries</span>
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-sm">Search:</label>
                    <input type="text" id="searchUser" value="{{ request('search') }}"
                        placeholder="Cari nama atau email..." class="px-3 py-2 border rounded text-sm">
                </div>
            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto">
                <x-table>
                    <thead>
                        <tr>
                            <x-th class="w-12 text-center">No</x-th>
                            <x-th>NAMA</x-th>
                            <x-th>EMAIL</x-th>
                            <x-th class="text-center">TOKO</x-th>
                            <x-th class="text-center">ROLE</x-th>
                            <x-th class="w-32 text-center">ACTION</x-th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $i => $user)
                            <tr class="border-b hover:bg-gray-100">
                                <td class="p-3 text-center">
                                    {{ $users->firstItem() + $i }}
                                </td>
                                <td class="p-3">{{ $user->name }}</td>
                                <td class="p-3">{{ $user->email }}</td>
                                <td class="p-3 text-center">
                                    {{ $user->store->name ?? '-' }}
                                </td>
                                <td class="p-3 text-center">{{ $user->role }}</td>
                                <td class="p-3 text-center space-x-2">
                                    <a href="{{ route('master.users.edit', $user->id) }}"
                                        class="text-blue-600 hover:underline">
                                        Edit
                                    </a>

                                    <form action="{{ route('master.users.destroy', $user->id) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Hapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">
                                    Data tidak ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </x-table>
            </div>

            {{-- PAGINATION --}}
            <div class="flex justify-between items-center mt-4 text-sm text-gray-500">
                <div>
                    Showing {{ $users->firstItem() ?? 0 }}
                    to {{ $users->lastItem() ?? 0 }}
                    of {{ $users->total() }} entries
                </div>
                <div>
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        let timer = null;

        function loadUsers(url = null) {
            const search = document.getElementById('searchUser').value;
            const perPage = document.getElementById('perPage').value;

            const fetchUrl = url ??
                `{{ route('master.users.index') }}?search=${encodeURIComponent(search)}&per_page=${perPage}`;

            fetch(fetchUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(res.html, 'text/html');

                    document.getElementById('userContent').innerHTML =
                        doc.getElementById('userContent').innerHTML;
                });
        }

        document.addEventListener('keyup', function(e) {
            if (e.target.id === 'searchUser') {
                clearTimeout(timer);
                timer = setTimeout(() => loadUsers(), 300);
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target.id === 'perPage') {
                loadUsers();
            }
        });

        document.addEventListener('click', function(e) {
            const link = e.target.closest('.pagination a');
            if (link) {
                e.preventDefault();
                loadUsers(link.href);
            }
        });
    </script>
@endpush
