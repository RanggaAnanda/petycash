@extends('layouts.app')

@section('title', 'Form Uang Keluar')
@section('page-title', 'Form Uang Keluar')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow w-full">

        @php
            // === DEFAULT DATA (EDIT MODE) ===
            $defaultKategori = 'atk';
            $defaultSubKategori = 'kertas';
        @endphp

        <form action="#" class="space-y-6">

            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                    Edit Uang Keluar
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
                ]" :value="old('kategori', $defaultKategori)"
                    placeholder="Pilih Kategori" />
            </div>

            <!-- Sub Kategori -->
            <div>
                <x-input-label name="Sub Kategori" />
                <x-dropdown name="sub_kategori" id="subKategori" placeholder="Pilih Sub Kategori" />
            </div>

            <!-- Jumlah -->
            <div>
                <x-input-label name="Jumlah" />
                <div class="flex">
                    <x-input-rp-label  type="text" value="Rp" />
                    <x-input-rp type="text" placeholder="Masukkan nominal" value="50.000" />
                </div>
            </div>

            <!-- Keterangan -->
            <div class="mt-6">
                <x-input-label name="Keterangan" />
                <x-input-text name="keterangan" placeholder="Masukan keterangan" value="Pembelian Kertas" />
            </div>

            <!-- Action -->
            <div class="pt-4">
                <a href="{{ route('daftar.pettycash') }}"
                    class="inline-flex bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    Simpan
                </a>
            </div>

        </form>
    </div>

    <!-- ================= SCRIPT ================= -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

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

            const defaultKategori = "{{ old('kategori', $defaultKategori) }}";
            const defaultSubKategori = "{{ old('sub_kategori', $defaultSubKategori) }}";

            function loadSubKategori(kategori, selected = '') {
                subKategoriSelect.innerHTML =
                    '<option value="">Pilih Sub Kategori</option>';

                if (!subKategoriMap[kategori]) return;

                Object.entries(subKategoriMap[kategori]).forEach(([value, label]) => {
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = label;

                    if (value === selected) {
                        option.selected = true;
                    }

                    subKategoriSelect.appendChild(option);
                });
            }

            // Change handler
            kategoriSelect.addEventListener('change', function() {
                loadSubKategori(this.value);
            });

            // INIT (EDIT MODE / VALIDATION ERROR)
            if (defaultKategori) {
                loadSubKategori(defaultKategori, defaultSubKategori);
            }
        });
    </script>
@endsection
