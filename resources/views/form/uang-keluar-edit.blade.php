@extends('layouts.app')

@section('title', 'Form Uang Keluar')
@section('page-title', 'Form Uang Keluar')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow w-full">

        <form action="#" class="space-y-6">

            <!-- Tanggal -->
            <div>
                <x-input-label name="Tanggal" />
                <x-input-date name="tanggal" readonly />
            </div>

            <!-- Kategori -->
            <div>
                <x-input-label name="Kategori" />
                <x-dropdown name="kategori" :options="[
                    'atk' => 'Atk',
                    'gaji karyawan' => 'Gaji Karyawan',
                    'uang makan' => 'Uang Makan',
                ]" placeholder="Pilih Kategori" />
            </div>

            <!-- Keterangan -->
            <div>
                <x-input-label name="Keterangan" />
                <x-input-text name="keterangan" placeholder="Masukan keterangan" value="Pembelian Kertas" />
            </div>

            <!-- Jumlah -->
            <div>
                <x-input-label name="Jumlah" />
                <div class="flex">
                    <x-input-rp-label type="text" value="Rp" />
                    <x-input-rp type="text" placeholder="Masukkan nominal" value="50.000" />
                </div>
            </div>

            <!-- Save Button -->
            <div class="pt-4">
                <a href="{{ route('daftar.pettycash') }}">
                    <button type="button"
                        class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        Simpan
                    </button>
                </a>
            </div>

        </form>
    </div>
@endsection
