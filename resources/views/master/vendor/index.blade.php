@extends('layouts.app')

@section('title', 'Master Vendor')
@section('page-title', 'Master - Vendor')

@section('content')
    <div class="space-y-6">

        {{-- ================= NOTIFIKASI SUCCESS ================= --}}
        @if (session('success'))
            <div class="p-2 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- ================= FORM VENDOR (Tambah / Edit) ================= --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <h3 class="font-semibold text-lg mb-4" id="formTitle">Form Vendor</h3>

            <form id="vendorForm" action="{{ route('master.vendors.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="vendor_id" id="vendor_id">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>
                        <x-input-label name="Kode" />
                        <x-input name="kode" id="kodeVendor" value="{{ old('kode') }}" />
                        @error('kode')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-input-label name="Kategori" />
                        <x-dropdown name="kategori_id" id="kategoriVendor" :options="['' => 'Pilih Kategori..'] + $kategoris->pluck('name', 'id')->toArray()" :value="old('kategori_id')" />
                    </div>

                    <div>
                        <x-input-label name="Vendor" />
                        <x-input name="name" id="namaVendor" value="{{ old('name') }}" />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="mt-4 flex items-center gap-2">
                    <button type="submit" id="btnSaveVendor"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold">
                        Simpan
                    </button>
                    <button type="button" id="btnCancelVendor"
                        class="ml-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-xl hidden">Batal</button>
                </div>
            </form>
        </div>

        {{-- ================= LIST VENDOR ================= --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">

            <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 gap-3">
                <h3 class="font-semibold text-gray-700 dark:text-gray-200 text-lg">List Vendor</h3>

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
                        class="px-3 py-2 rounded border text-base" value="{{ request('search') }}">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-base">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <tr class="font-black text-gray-500 uppercase tracking-widest">
                            <th class="p-5 text-center w-12">No</th>
                            <th class="p-5 text-center">KODE</th>
                            <th class="p-5 text-left">VENDOR</th>
                            <th class="p-5 text-left">KATEGORI</th>
                            <th class="p-5 text-center w-20">EDIT</th>
                            <th class="p-5 text-center w-20">DELETE</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($vendors as $idx => $vendor)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/50 transition">
                                <td class="p-3 text-center font-medium">{{ $idx + 1 }}</td>
                                <td class="p-3 text-center">{{ $vendor->kode }}</td>
                                <td class="p-3">{{ $vendor->name }}</td>
                                <td class="p-3">{{ $vendor->kategori->name ?? '-' }}</td>
                                <td class="p-3 text-center">
                                    <button type="button" class="vendor-edit text-blue-600 hover:underline"
                                        data-id="{{ $vendor->id }}" data-kode="{{ $vendor->kode }}"
                                        data-nama="{{ $vendor->name }}" data-kategori="{{ $vendor->kategori_id }}">
                                        Edit
                                    </button>
                                </td>
                                <td class="p-3 text-center">
                                    <form action="{{ route('master.vendors.destroy', $vendor->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus vendor ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3">{{ $vendors->links() }}</div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('vendorForm');
            const btnSave = document.getElementById('btnSaveVendor');
            const btnCancel = document.getElementById('btnCancelVendor');
            const inputVendorId = document.getElementById('vendor_id');
            const kodeInput = document.getElementById('kodeVendor');
            const namaInput = document.getElementById('namaVendor');
            const kategoriInput = document.getElementById('kategoriVendor');
            const formTitle = document.getElementById('formTitle');

            document.querySelectorAll('.vendor-edit').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const kode = this.dataset.kode;
                    const nama = this.dataset.nama;
                    const kategori = this.dataset.kategori;

                    // set form untuk update
                    inputVendorId.value = id;
                    kodeInput.value = kode;
                    namaInput.value = nama;
                    kategoriInput.value = kategori;

                    form.action = `/master/vendor/${id}`;
                    btnSave.textContent = 'Update';
                    btnCancel.classList.remove('hidden');
                    formTitle.textContent = 'Edit Vendor';
                });
            });

            btnCancel.addEventListener('click', function() {
                // reset form ke tambah baru
                inputVendorId.value = '';
                kodeInput.value = '';
                namaInput.value = '';
                kategoriInput.value = '';
                form.action = "{{ route('master.vendors.store') }}";
                btnSave.textContent = 'Simpan';
                btnCancel.classList.add('hidden');
                formTitle.textContent = 'Form Vendor';
            });
        });
    </script>
@endsection
