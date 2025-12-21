@extends('layouts.app')

@section('title', 'Form Uang Masuk')
@section('page-title', 'Form Uang Masuk')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow w-full">

        <form action="#" class="space-y-6">

            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 ">
                    Form Uang Masuk
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
                <x-dropdown name="kategori" :options="[
                    'transfer dari keuangan' => 'Tranfer dari keuangan',
                    'lainnya' => 'Lainnya',
                ]" placeholder="Pilih Kategori" />
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
                <x-input-text name="keterangan" placeholder="Opsional" />
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
