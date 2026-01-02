@extends('layouts.app')

@section('title', 'Form Omset')
@section('page-title', 'Form Omset')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow w-full">

        <form action="#" class="space-y-6">

            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 ">
                    Form Omset
                </h2>
                <hr class="mt-3 border-gray-200 dark:border-gray-700">
            </div>

            <!-- Tanggal -->
            <div>
                <label class="block mb-1 text-lg font-medium text-gray-700 dark:text-gray-200">
                    Tanggal
                </label>
                <x-input-date name="tanggal" readonly />

            </div>

            <!-- Toko -->
            <div>
                <label class="block mb-1 text-lg font-medium text-gray-700 dark:text-gray-200">
                    Toko
                </label>
                <select
                    class="w-full rounded border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-700
                       text-gray-800 dark:text-gray-200
                       text-lg p-2">
                    <option>Pilih Toko</option>
                    <option>Toko A</option>
                    <option>Toko B</option>
                    <option>Toko C</option>
                </select>
            </div>

            <!-- Jumlah Omset -->
            <div>
                <label class="block mb-1 text-lg font-medium text-gray-700 dark:text-gray-200">
                    Jumlah Omset
                </label>
                <div class="flex">
                    <input type="text" value="Rp" disabled
                        class="w-16 rounded-l border border-gray-300 dark:border-gray-600
                           bg-gray-100 dark:bg-gray-600
                           text-gray-700 dark:text-gray-200
                           p-2 text-lg text-center">
                    <x-input-rp type="text" placeholder="Masukkan nominal" />
                </div>
            </div>

            <!-- Save Button -->
            <div class="pt-4">
                <a href="{{ route('daftar.omset.index') }}">
                    <button type="button"
                        class="w-full md:w-auto
                           bg-blue-600 hover:bg-blue-700
                           dark:bg-blue-500 dark:hover:bg-blue-600
                           text-white px-6 py-2 rounded-lg transition">
                        Simpan
                    </button>
                </a>
            </div>

        </form>
    </div>
@endsection
