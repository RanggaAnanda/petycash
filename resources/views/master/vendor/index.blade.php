@extends('layouts.app')

@section('title', 'Master Vendor')
@section('page-title', 'Master - Vendor')

@section('content')
    <div class="space-y-6">

        @if (session('success'))
            <div class="p-2 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
        @endif

        <!-- FORM VENDOR (Tambah / Edit) -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <h3 class="font-semibold mb-4" id="formTitle">Form Vendor</h3>

            <form id="vendorForm" action="{{ route('master.vendor.store') }}" method="POST" class="space-y-4">
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

                <div class="mt-4">
                    <button type="submit" id="btnSaveVendor"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Simpan</button>
                    <button type="button" id="btnCancelVendor"
                        class="ml-2 px-3 py-2 bg-gray-200 rounded hidden">Batal</button>
                </div>
            </form>
        </div>

        <!-- LIST VENDOR -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow border">
            <h3 class="font-semibold">List Vendor</h3>

            <x-table class="mt-4">
                <thead>
                    <tr>
                        <x-th class="w-12 text-center">No</x-th>
                        <x-th class="text-center">KODE</x-th>
                        <x-th class="text-left">KATEGORI</x-th>
                        <x-th class="text-left">VENDOR</x-th>
                        <x-th class="w-20 text-center">EDIT</x-th>
                        <x-th class="w-20 text-center">DELETE</x-th>
                    </tr>
                </thead>
                <x-tbody>
                    @foreach ($vendors as $idx => $vendor)
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <td class="p-3 text-center font-medium">{{ $idx + 1 }}</td>
                            <td class="p-3 text-center">{{ $vendor->kode }}</td>
                            <td class="p-3">{{ $vendor->kategori->name ?? '-' }}</td>
                            <td class="p-3">{{ $vendor->name }}</td>
                            <td class="p-3 text-center">
                                <button type="button" class="vendor-edit text-blue-600" data-id="{{ $vendor->id }}"
                                    data-kode="{{ $vendor->kode }}" data-nama="{{ $vendor->name }}"
                                    data-kategori="{{ $vendor->kategori_id }}">
                                    Edit
                                </button>
                            </td>
                            <td class="p-3 text-center">
                                <form action="{{ route('master.vendor.destroy', $vendor->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus vendor ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </x-tbody>
            </x-table>

            <div class="mt-3">
                {{ $vendors->links() }}
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
                form.action = "{{ route('master.vendor.store') }}";
                btnSave.textContent = 'Simpan';
                btnCancel.classList.add('hidden');
                formTitle.textContent = 'Form Vendor';
            });
        });
    </script>
@endsection
