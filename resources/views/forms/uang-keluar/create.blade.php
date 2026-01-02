@extends('layouts.app')

@section('title', 'Form Uang Keluar')
@section('page-title', 'Form Uang Keluar')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow w-full">

        <form action="#" class="space-y-6">

            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 ">
                    Form Uang Keluar
                </h2>
                <hr class="mt-3 border-gray-200 dark:border-gray-700">
            </div>

            <!-- Tanggal -->
            <div>
                <x-input-label name="Tanggal" />
                <x-input-date name="tanggal" readonly />
            </div>

            <!-- Kategori -->
            <div>
                <x-input-label name="Kategori" />
                <x-dropdown name="kategori" id="kategori" :options="[
                    'atk' => 'ATK',
                    'kebersihan' => 'Kebersihan',
                    'makan_minum' => 'Makan & Minum',
                ]" placeholder="Pilih Kategori" />
            </div>

            <!-- Sub Kategori -->
            <div>
                <x-input-label name="Sub Kategori" />
                <x-dropdown name="sub_kategori" id="subKategori" :options="[]" placeholder="Pilih Sub Kategori" />
            </div>


            <!-- Jumlah -->
            <div>
                <x-input-label name="Jumlah" />
                <div class="flex">
                    <x-input-rp-label type="text" value="Rp" />
                    <x-input-rp type="text" placeholder="Masukkan nominal" />
                </div>
            </div>

            <!-- Keterangan -->
            <div class="mt-6">
                <x-input-label name="Keterangan" />
                <x-input-text name="keterangan" placeholder="Masukan keterangan" />
            </div>
            <!-- Save Button -->
            <div class="pt-4">

                <a href="{{ route('daftar.petycash.index') }}">
                    <button type="button"
                        class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        Simpan
                    </button>
                </a>
            </div>

        </form>

        <script>
            const subKategoriMap = {
                atk: {
                    solasi: 'Solasi',
                    kertas: 'Kertas',
                    pulpen: 'Pulpen',
                },
                kebersihan: {
                    sabun: 'Sabun Pembersih',
                    pel: 'Pel Lantai',
                    pewangi: 'Pewangi Ruangan',
                },
                makan_minum: {
                    air: 'Air Mineral',
                    makan: 'Makan',
                    snack: 'Snack',
                }
            };

            const kategoriSelect = document.getElementById('kategori');
            const subKategoriSelect = document.getElementById('subKategori');

            kategoriSelect.addEventListener('change', function() {
                const selected = this.value;

                // reset sub kategori
                subKategoriSelect.innerHTML = '<option value="">Pilih Sub Kategori</option>';

                if (!subKategoriMap[selected]) return;

                Object.entries(subKategoriMap[selected]).forEach(([value, label]) => {
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = label;
                    subKategoriSelect.appendChild(option);
                });
            });
        </script>

    </div>
@endsection
