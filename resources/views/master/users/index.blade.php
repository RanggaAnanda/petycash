@extends('layouts.app')

@section('title', 'Master User')
@section('page-title', 'Master - User')

@section('content')
    <div class="space-y-6">

        {{-- ================= HEADER ================= --}}
        <div
            class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="font-semibold text-lg text-gray-700 dark:text-gray-200">List Users</h3>
            <a href="{{ route('master.users.create') }}"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition">
                Add User
            </a>
        </div>

        {{-- ================= SEARCH + TABLE ================= --}}
        <div id="userContent"
            class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">

            {{-- Filter --}}
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 gap-3">
                <div class="flex items-center gap-3">
                    <select id="perPage" class="border rounded px-2 py-1 text-sm">
                        @foreach ([10, 25, 50] as $size)
                            <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                                {{ $size }}</option>
                        @endforeach
                    </select>
                    <span class="text-sm text-gray-500">entries</span>
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-sm">Search:</label>
                    <input type="text" id="searchUser" value="{{ request('search') }}"
                        placeholder="Cari nama atau email..." class="px-3 py-2 rounded border text-base">
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-base text-gray-700 dark:text-gray-200">
                    <thead class="bg-gray-100 dark:bg-gray-700 border-b">
                        <tr class="font-black uppercase text-gray-500 tracking-widest">
                            <th class="w-12 px-3 py-2 text-center">No</th>
                            <th class="px-3 py-2">Nama</th>
                            <th class="px-3 py-2">Email</th>
                            <th class="px-3 py-2 text-center">Toko</th>
                            <th class="px-3 py-2 text-center">Role</th>
                            <th class="w-32 px-3 py-2 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($users as $i => $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                                <td class="px-3 py-2 text-center font-medium">{{ $users->firstItem() + $i }}</td>
                                <td class="px-3 py-2">{{ $user->name }}</td>
                                <td class="px-3 py-2">{{ $user->email }}</td>
                                <td class="px-3 py-2 text-center">{{ $user->store->name ?? '-' }}</td>
                                <td class="px-3 py-2 text-center">{{ $user->role }}</td>
                                <td class="px-3 py-2 text-center space-x-2">
                                    <a href="{{ route('master.users.edit', $user->id) }}"
                                        class="text-blue-600 hover:underline font-semibold">Edit</a>

                                    <form action="{{ route('master.users.destroy', $user->id) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Hapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline font-semibold">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500 text-base">Data tidak
                                    ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="flex justify-between items-center mt-4 text-sm text-gray-500">
                <div>
                    Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }}
                    entries
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
        document.addEventListener('DOMContentLoaded', function() {
            let timer = null;

            const searchInput = document.getElementById('searchUser');
            const perPageSelect = document.getElementById('perPage');

            function loadUsers(url = null) {
                const search = searchInput.value;
                const perPage = perPageSelect.value;
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
                        document.getElementById('userContent').innerHTML = doc.getElementById('userContent')
                            .innerHTML;
                    });
            }

            searchInput.addEventListener('keyup', function() {
                clearTimeout(timer);
                timer = setTimeout(loadUsers, 300);
            });

            perPageSelect.addEventListener('change', loadUsers);

            document.addEventListener('click', function(e) {
                const link = e.target.closest('.pagination a');
                if (link) {
                    e.preventDefault();
                    loadUsers(link.href);
                }
            });
        });
    </script>
@endpush
