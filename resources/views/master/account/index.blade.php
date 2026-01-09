@extends('layouts.app')

@section('title', 'Master COA')
@section('page-title', 'Master - COA')

@section('content')
    <div class="space-y-6">

        {{-- ================= NOTIFIKASI SUCCESS ================= --}}
        @if (session('success'))
            <div class="p-2 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- ================= FORM COA ================= --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-lg mb-4">
                {{ $editAccount ? 'Edit Account' : 'Form Account Baru' }}
            </h3>

            <form
                action="{{ $editAccount ? route('master.accounts.update', $editAccount->id) : route('master.accounts.store') }}"
                method="POST" class="space-y-4">
                @csrf
                @if ($editAccount)
                    @method('PATCH')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>
                        <x-input-label name="Kode Akun" />
                        <x-input name="kode_akun" value="{{ old('kode_akun', $editAccount->kode_akun ?? '') }}" />
                    </div>

                    <div>
                        <x-input-label name="Jenis Akun" />
                        <x-dropdown name="jenis_akun" :options="[
                            'Aset' => 'Aset',
                            'Kewajiban' => 'Kewajiban',
                            'Modal' => 'Modal',
                            'Pendapatan' => 'Pendapatan',
                            'Beban' => 'Beban',
                        ]" :selected="$editAccount->jenis_akun ?? null" />
                    </div>

                    <div>
                        <x-input-label name="Normal Balance" />
                        <x-dropdown name="normal_balance" :options="['Debit' => 'Debit', 'Kredit' => 'Kredit']" :selected="$editAccount->normal_balance ?? null" />
                    </div>

                </div>

                <div class="mt-4 flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold">
                        {{ $editAccount ? 'Update' : 'Simpan' }}
                    </button>
                    @if ($editAccount)
                        <a href="{{ route('master.accounts.index') }}"
                            class="px-4 py-2 bg-gray-200 rounded-xl hover:bg-gray-300">
                            Batal
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ================= LIST COA ================= --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">

            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-700 dark:text-gray-200 text-lg">List Account</h3>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1">
                        <select id="perPage" class="border rounded px-2 py-1 text-sm">
                            @foreach ([10, 25, 50] as $size)
                                <option value="{{ $size }}"
                                    {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>
                        <span class="text-sm text-gray-500">entries</span>
                    </div>

                    <input id="searchToko" type="text" placeholder="Cari kode atau nama..."
                        class="px-3 py-2 rounded border text-base" value="{{ request('search') }}" />
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-base">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <tr class="font-black text-gray-500 uppercase tracking-widest">
                            <th class="p-5 text-center">No</th>
                            <th class="p-5 text-center">Kode Akun</th>
                            <th class="p-5 text-center">Jenis Akun</th>
                            <th class="p-5 text-center">Normal</th>
                            <th class="p-5 text-center">Edit</th>
                            <th class="p-5 text-center">Delete</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($accounts as $idx => $acc)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition-colors">
                                <td class="p-5 text-center">{{ $idx + 1 }}</td>
                                <td class="p-5 text-center">{{ $acc->kode_akun }}</td>
                                <td class="p-5 text-center">{{ $acc->jenis_akun }}</td>
                                <td class="p-5 text-center">{{ $acc->normal_balance }}</td>
                                <td class="p-5 text-center">
                                    <a href="{{ route('master.accounts.edit', $acc->id) }}"
                                        class="text-blue-600 hover:underline">Edit</a>
                                </td>
                                <td class="p-5 text-center">
                                    <form action="{{ route('master.accounts.destroy', $acc->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3">{{ $accounts->links() }}</div>
            </div>
        </div>

    </div>
@endsection
