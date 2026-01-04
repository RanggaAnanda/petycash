@extends('layouts.app')

@section('title', 'Master COA')
@section('page-title', 'Master - COA')

@section('content')
    <div class="space-y-6">

        <!-- Notifikasi Success -->
        @if (session('success'))
            <div class="p-2 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
        @endif

        <!-- FORM COA -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <h3 class="font-semibold mb-4">
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
                        <x-input-label name="Nama Akun" />
                        <x-input name="nama_akun" value="{{ old('nama_akun', $editAccount->nama_akun ?? '') }}" />
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

                    <div>
                        <x-input-label name="Parent Akun (Opsional)" />
                        <x-dropdown name="parent_id" :options="['' => 'Pilih Parent'] + App\Models\Account::pluck('nama_akun', 'id')->toArray()" :selected="$editAccount->parent_id ?? null" />
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                        {{ $editAccount ? 'Update' : 'Simpan' }}
                    </button>
                    @if ($editAccount)
                        <a href="{{ route('master.accounts.index') }}" class="ml-2 px-3 py-2 bg-gray-200 rounded">Batal</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- LIST COA -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border mt-4">
            <h3 class="font-semibold">List Account</h3>

            <x-table class="mt-4">
                <thead>
                    <tr>
                        <x-th class="w-12 text-center">No</x-th>
                        <x-th>Kode Akun</x-th>
                        <x-th>Nama Akun</x-th>
                        <x-th>Jenis Akun</x-th>
                        <x-th>Normal</x-th>
                        <x-th class="w-32 text-center">Actions</x-th>
                    </tr>
                </thead>
                <x-tbody>
                    @foreach ($accounts as $idx => $acc)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td class="whitespace-nowrap">{{ $acc->kode_akun }}</td>
                            <td class="whitespace-nowrap">{{ $acc->nama_akun }}</td>
                            <td class="whitespace-nowrap">{{ $acc->jenis_akun }}</td>
                            <td class="whitespace-nowrap">{{ $acc->normal_balance }}</td>
                            <td class="text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('master.accounts.edit', $acc->id) }}" class="text-blue-600">Edit</a>
                                    <form action="{{ route('master.accounts.destroy', $acc->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-tbody>
            </x-table>


            <div class="mt-3">{{ $accounts->links() }}</div>
        </div>
    </div>
@endsection
